<?php

namespace Despark\Cms\Http\Controllers;

use Despark\Cms\Http\Requests\UserRequest;
use Despark\Cms\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Response;

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
        foreach ($this->model->getManyToManyFields() as $metod => $array) {
            $record->$metod()->sync($request->get($array, []));
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
     * @param Request $request
     * @param int     $id
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

        foreach ($this->model->getManyToManyFields() as $metod => $array) {
            $record->$metod()->sync($request->get($array, []));
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
}
