<?php

namespace Despark\Cms\Http\Controllers;

use Despark\Cms\Http\Requests\UserRequest;
use Despark\Cms\Http\Requests\UserUpdateRequest;
use Despark\Cms\Mail\UserExported;
use Illuminate\Http\Request;
use Response;
use Storage;

class UsersController extends AdminController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->viewData['form'] = \Entity::getForm($this->model);

        return view($this->defaultFormView, $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $input = $request->except('roles');

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->password);
        } else {
            unset($input['password']);
        }

        $record = $this->model->create($input);

        if (method_exists($record, 'getManyToManyFields')) {
            foreach ($this->model->getManyToManyFields() as $metod => $array) {
                $record->$metod()->sync($request->get($array, []));
            }
        }

        if ($request->has('roles')) {
            $record->syncRoles($request->roles);
        }

        $this->notify([
            'type' => 'success',
            'title' => 'Successful create user!',
            'description' => 'User is created successfully!',
        ]);

        return redirect(route('user.edit', ['id' => $record->id]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param int $id
     *
     * @return Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $input = $request->except('roles');

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->password);
        } else {
            unset($input['password']);
        }

        $record = $this->model->findOrFail($id);

        if ($request->has('roles')) {
            $record->syncRoles($request->roles);
        }

        if (method_exists($record, 'getManyToManyFields')) {
            foreach ($this->model->getManyToManyFields() as $metod => $array) {
                $record->$metod()->sync($request->get($array, []));
            }
        }

        $record->update($input);

        $this->notify([
            'type' => 'success',
            'title' => 'Successful update!',
            'description' => 'This user is updated successfully.',
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->model->findOrFail($id)->delete();

        $this->notify([
            'type' => 'danger',
            'title' => 'Successful deleted user!',
            'description' => 'The user is deleted successfully.',
        ]);

        return redirect()->back();
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restrict($id = null)
    {
        if ($id) {
            $this->model->findOrFail($id)->update(['is_restricted' => 1]);

        } else {
            auth()->user()->update(['is_restricted' => 1]);
        }

        $this->notify([
            'type' => 'info',
            'title' => 'Successful restricted user!',
            'description' => 'The user is restricted successfully.',
        ]);

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function free()
    {
        auth()->user()->update(['is_restricted' => 0]);

        $this->notify([
            'type' => 'info',
            'title' => 'Successful removal of restriction to user!',
            'description' => 'The user restriction remove is successful.',
        ]);

        return redirect()->back();
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function export($id = null)
    {
        $relationships = $this->model->relationships();

        if ($id) {
            $this->model = $this->model->findOrFail($id);

        } else {
            $this->model = auth()->user();
        }

        $this->model->load($relationships);
        $jsonData = collect($this->model, $this->model->getRelations())->toJson();
        $filename = $this->generateFilename();
        $fileFullPath = 'user-exports/' . $filename . '.json';
        Storage::disk('public')->put($fileFullPath, $jsonData);
        \Mail::to($this->model)->send(new UserExported($fileFullPath));

        $this->notify([
            'type' => 'info',
            'title' => 'Successful export!',
            'description' => 'The user\'s data is exported and sent successfully.',
        ]);

        return redirect()->back();
    }

    /**
     * @return string
     */
    protected function generateFilename()
    {
        $isUnique = false;
        $path = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . 'user-exports/';
        $filename = '';

        while (!$isUnique) {
            $filename = hash('md5', $this->model->id . date('Y-m-d H:i:s'));
            if (!file_exists($path . $filename . 'json')) {
                $isUnique = true;
            }
        }

        return $filename;
    }
}
