<?php

namespace Despark\Cms\Http\Controllers;

use Despark\Cms\Admin\Sidebar;
use Despark\Cms\Models\AdminModel;
use Despark\Cms\Resource\EntityManager;
use Despark\Cms\Traits\ManagesAssets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use View;
use Yajra\Datatables\Contracts\DataTableEngineContract;
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
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var mixed
     */
    protected $resourceConfig;

    /**
     * AdminController constructor.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->resourceConfig = $this->entityManager->getByController($this);

        $this->viewData['sidebar'] = $this->getSidebar();

        if ( ! $this->resourceConfig) {
            // we don't have resource config, so we just return
            return;
        }

        $this->model = new $this->resourceConfig['model'];

        $this->paginateLimit = config('ignicms.paginateLimit');

        $this->defaultFormView = $this->entityManager->getFormTemplate($this->model);

        $this->viewData['inputs'] = \Request::all();

        $this->viewData['pageTitle'] = $this->getResourceConfig()['name'] ? : config('ignicms.projectName').' '.'Admin';

        $this->viewData['dataTablesAjaxUrl'] = $this->getDataTablesAjaxUrl();

        $this->viewData['controller'] = $this;

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

            $dataTableEngine = $dataTable->eloquent($this->prepareModelQuery());

            if ($this->hasActionButtons()) {
                $dataTableEngine->addColumn('action', function ($record) {
                    return $this->getActionButtonsHtml($record);
                });
            }

            // Check for any fields that needs custom building.
            foreach ($this->model->getAdminTableColumns() as $column) {
                $columnName = studly_case($column);
                $method = 'build'.$columnName.'Column';
                if (method_exists($this, 'build'.$columnName.'Column')) {
                    $dataTableEngine->editColumn($column, function ($data) use ($method) {
                        return call_user_func([$this, $method], $data);
                    });
                }
            }

            $this->prepareDataTable($request, $dataTableEngine);

            return $dataTableEngine->make(true);
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
        $with = $this->withEagerLoad();

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
                // We need to make sure we transform the relation to studly camel calse
                foreach ($relation as &$value) {
                    $value = camel_case($value);
                }
                $with[] = implode('.', $relation);
            } else {
                $query->addSelect($table.'.'.$column);
            }
        }

        if ( ! empty($with)) {
            // Make sure we have unique relations.
            $with = array_unique($with);
            $query->with($with);
            // We should refactor this and find actual related field.
            $query->select($table.'.*');
        }

        return $query;
    }

    /**
     * Return relations that should be eager loaded for the index.
     * @return array
     */
    public function withEagerLoad()
    {
        return [];
    }

    /**
     * @return View
     */
    public function edit($id)
    {
        // TODO VERSION DEPENDANT + ROUTE BINDING
        if (is_object($id) && $id instanceof Model) {
            $this->viewData['record'] = $id;
        } else {
            $this->viewData['record'] = $this->model->findOrFail($id);
        }
        $this->viewData['formMethod'] = 'PUT';
        $this->viewData['formAction'] = $this->getResourceConfig()['id'].'.update';

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
            'type'        => 'warning',
            'title'       => 'No access!',
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
     * Returns an array ready to be fed into data tables.
     * @return array
     */
    public function getDataTableColumns()
    {
        if ( ! isset($this->dataTableColumns)) {
            foreach ($this->model->getAdminTableColumns() as $idx => $column) {
                if (strstr($column, '.') !== false) {
                    // We are not interested in the last part
                    $relationPath = explode('.', $column);
                    $relationColumn = array_pop($relationPath);
                    $relationPath = array_map('camel_case', $relationPath);
                    $this->dataTableColumns[$idx] = [
                        'data' => $column,
                        'name' => implode('.', $relationPath).'.'.$relationColumn,
                    ];
                } else {
                    $this->dataTableColumns[$idx] = [
                        'data' => $column,
                        'name' => $column,
                    ];
                }

                if ( ! is_numeric($idx)) {
                    $this->dataTableColumns[$idx]['title'] = $idx;
                }
            }
        }

        return $this->dataTableColumns;
    }

    /**
     * @param $record
     * @return string
     */
    protected function getActionButtons($record)
    {
        $buttons = [];
        if (isset($this->viewData['editRoute'])) {
            $buttons[] = '<a href="'.route($this->viewData['editRoute'],
                    ['id' => $record->id]).'" class="btn btn-primary">'.trans('ignicms::admin.edit').'</a>';
        }

        if (isset($this->viewData['destroyRoute'])) {
            $buttons[] = '<a href="#"  class="js-open-delete-modal btn btn-danger"
                    data-delete-url="'.route($this->viewData['destroyRoute'], ['id' => $record->id]).'">
                    '.trans('ignicms::admin.delete').'
                </a>';
        }

        return $buttons;
    }

    /**
     * @param $record
     * @return View
     */
    public function getActionButtonsHtml($record)
    {
        return view('ignicms::admin.layouts.datatable.actions',
            ['actions' => $this->getActionButtons($record)]);
    }

    /**
     * @return bool
     */
    public function hasActionButtons()
    {
        return isset($this->viewData['editRoute']) || isset($this->viewData['destroyRoute']);
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
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     * @return AdminController
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

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

    /**
     * Give chance for children to alter the data table.
     * @param Request                 $request
     * @param DataTableEngineContract $dataTableEngine
     */
    protected function prepareDataTable(Request $request, DataTableEngineContract $dataTableEngine) { }

    /**
     * @return Sidebar
     */
    public function getSidebar()
    {
        return app(Sidebar::class);
    }
}