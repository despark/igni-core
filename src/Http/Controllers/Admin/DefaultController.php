<?php


namespace Despark\Cms\Http\Controllers\Admin;


use Illuminate\Routing\Controller;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * Admin dashboard.
     *
     * @return mixed
     */
    public function adminHome()
    {
        return view('ignicms::admin.pages.home');
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


}