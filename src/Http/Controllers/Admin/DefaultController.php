<?php


namespace Despark\Cms\Http\Controllers\Admin;


use Despark\Cms\Http\Controllers\AdminController;

/**
 * Class DefaultController.
 */
class DefaultController extends AdminController
{
    /**
     * Admin dashboard.
     *
     * @return mixed
     */
    public function adminHome()
    {
        return view('ignicms::admin.pages.home', ['sidebar' => $this->viewData['sidebar']]);
    }

}