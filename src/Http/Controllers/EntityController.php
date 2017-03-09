<?php


namespace Despark\Cms\Http\Controllers;


use Despark\Cms\Http\Requests\AdminFormRequest;

class EntityController extends AdminController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->viewData['form'] = $this->entityManager->getForm($this->model);

        return view($this->defaultFormView, $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminFormRequest $request
     *
     * @return Response
     */
    public function store(AdminFormRequest $request)
    {
        $input = $request->all();

        $record = $this->model->create($input);

        $this->notify([
            'type' => 'success',
            'title' => 'Successful create!',
            'description' => $this->getResourceConfig()['name'].' is created successfully!',
        ]);

        return redirect(route($this->getResourceConfig()['id'].'.edit', ['id' => $record->id]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $record = $this->model->findOrFail($id);

        $this->viewData['record'] = $record;

        $this->viewData['formMethod'] = 'PUT';
        $this->viewData['formAction'] = $this->getResourceConfig()['id'].'.update';

        return view($this->defaultFormView, $this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminFormRequest $request
     * @param int              $id
     *
     * @return Response
     */
    public function update(AdminFormRequest $request, $id)
    {
        $input = $request->all();

        $record = $this->model->findOrFail($id);

        $record->update($input);

        $this->notify([
            'type' => 'success',
            'title' => 'Successful update!',
            'description' => $this->getResourceConfig()['name'].' is updated successfully.',
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
            'title' => 'Successful delete!',
            'description' => $this->getResourceConfig()['name'].' is deleted successfully.',
        ]);

        return redirect()->back();
    }

}