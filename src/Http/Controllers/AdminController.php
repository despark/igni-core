<?php

namespace Despark\Cms\Http\Controllers;

use Despark\Cms\Admin\Sidebar;
use Despark\Cms\Models\AdminModel;
use Despark\Cms\Resource\ResourceManager;
use Despark\Cms\Traits\ManagesAssets;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use View;
use Yajra\Datatables\Datatables;

/**
 * Class AdminController.
 */
abstract class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ManagesAssets;
    /**
     * used for sending data to array.
     *
     * @array
     */
    public $viewData = [];

    /**
     * sidebar menu.
     *
     * @array
     */
    public $sidebarItems = [];

    /**
     * @var int
     */
    public $paginateLimit;

    /**
     * @var mixed
     */
    public $defaultFormView;

    /**
     * @var
     */
    protected $identifier;

    /**
     * @var AdminModel
     */
    protected $model;

    /**
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @var mixed
     */
    protected $resourceConfig;

    /**
     * AdminController constructor.
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;

        $this->resourceConfig = $this->resourceManager->getByController($this);

        $this->viewData['sidebar'] = app(Sidebar::class);

        if (! $this->resourceConfig) {
            // we don't have resource config, so we just return
            return;
        }

        $this->model = new $this->resourceConfig['model'];

        $this->paginateLimit = config('ignicms.paginateLimit');
        $this->defaultFormView = config('ignicms.defaultFormView');

        $this->viewData['inputs'] = \Request::all();

        $this->viewData['pageTitle'] = $this->getResourceConfig()['name'] ? : config('ignicms.projectName').' '.'Admin';

        $this->viewData['dataTablesAjaxUrl'] = $this->getDataTablesAjaxUrl();

        //Prepare view actions
        $this->prepareActions();
    }


    /**
     * @param Request    $request
     * @param Datatables $dataTable
     * @return \Illuminate\Http\JsonResponse|View
     */
    public function index(Request $request, Datatables $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->eloquent($this->prepareModelQuery())
                             ->addColumn('action', function ($record) {
                                 return $this->getActionButtons($record);
                             })->make(true);
        }

        $this->viewData['model'] = $this->model;

        return view('ignicms::admin.layouts.list', $this->viewData);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function prepareModelQuery()
    {
        $tableColumns = $this->model->getAdminTableColumns();
        $query = $this->model->newQuery();
        $table = $this->model->getTable();

        $keyName = $this->model->getKeyName();

        // What if model key is composite
        if (is_array($keyName)) {
            $select = [];
            foreach ($keyName as $key) {
                $select[] = $table.'.'.$key;
            }
            $query->select($select);
        } else {
            $query->select([
                $table.'.'.$this->model->getKeyName(),
            ]);
        }
        $with = [];

        foreach ($tableColumns as $name => $column) {
            // We already included the primary key so check the column and do nothing if exists.
            if (is_array($keyName)) {
                if (in_array($column, $keyName)) {
                    continue;
                }
            } else {
                if ($column == $this->model->getKeyName()) {
                    continue;
                }
            }

            // If it's a relation we need to eager load it.
            if (strstr($column, '.') !== false) {
                $relation = explode('.', $column);
                $relationField = array_pop($relation);
                $with[] = implode('.', $relation);
            } else {
                $query->addSelect($table.'.'.$column);
            }
        }

        if (! empty($with)) {
            $query->with($with);
            // We should refactor this and find actual related field.
            $query->select($table.'.*');
        }

        return $query;
    }

    /**
     * @return View
     */
    public function edit($id)
    {
        $this->viewData['record'] = $this->model->findOrFail($id);

        $this->viewData['formMethod'] = 'PUT';
        $this->viewData['formAction'] = $this->identifier.'.update';

        return view($this->defaultFormView, $this->viewData);
    }

    /**
     * Admin dashboard.
     *
     * @return mixed
     */
    public function forbidden()
    {
        $this->notify([
            'type' => 'warning',
            'title' => 'No access!',
            'description' => 'Sorry, you don\'t have access to manage this resources',
        ]);

        return redirect(route('adminHome'));
    }

    /**
     * set notification.
     *
     *
     * @param array $notificationInfo
     */
    public function notify(array $notificationInfo)
    {
        session()->flash('notification', $notificationInfo);
    }

    /**
     * @param $record
     * @return string
     */
    protected function getActionButtons($record)
    {
        $editBtn = '';
        $deleteBtn = '';
        if (isset($this->viewData['editRoute'])) {
            $editBtn = '<a href="'.route($this->viewData['editRoute'],
                    ['id' => $record->id]).'" class="btn btn-primary">'.trans('ignicms::admin.edit').'</a>';
        }

        if (isset($this->viewData['destroyRoute'])) {
            $deleteBtn = '<a href="#"  class="js-open-delete-modal btn btn-danger"
                    data-delete-url="'.route($this->viewData['destroyRoute'], ['id' => $record->id]).'">
                    '.trans('ignicms::admin.delete').'
                </a>';
        }

        $container = "<div class='action-btns'>{$editBtn}{$deleteBtn}</div>";

        return $container;
    }

    /**
     * @return string
     */
    public function getDataTablesAjaxUrl()
    {
        return route($this->getResourceConfig()['id'].'.index');
    }

    //    public function getModel(){
    //        if(!isset($this->model)){
    //            if(request()->is()){}
    //        }
    //    }

    /**
     * @return ResourceManager
     */
    public function getResourceManager()
    {
        return $this->resourceManager;
    }

    /**
     * @param ResourceManager $resourceManager
     * @return AdminController
     */
    public function setResourceManager($resourceManager)
    {
        $this->resourceManager = $resourceManager;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResourceConfig()
    {
        return $this->resourceConfig;
    }

    /**
     * @param mixed $resourceConfig
     * @return AdminController
     */
    public function setResourceConfig($resourceConfig)
    {
        $this->resourceConfig = $resourceConfig;

        return $this;
    }

    /**
     * Prepares controller actions
     * @return $this
     */
    protected function prepareActions()
    {
        $actions = isset($this->resourceConfig['actions']) ? $this->resourceConfig['actions'] : [
            'edit',
            'create',
            'destroy',
        ];

        $id = $this->resourceConfig['id'];
        foreach ($actions as $action) {
            $this->viewData[$action.'Route'] = $id.'.'.$action;
        }

        return $this;
    }


}
