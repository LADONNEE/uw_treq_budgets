<?php
namespace App\Http\Controllers;

use App\Auth\UwIdentityLocator;

class WhoamiController extends Controller
{

    public function index()
    {
        return view('whoami/index');
    }

    public function update()
    {
        $this->authorize('who-am-i');
        $uwnetid = request('uwnetid');
        $identity = new UwIdentityLocator();
        $identity->spoof($uwnetid);
        return redirect()->action('WhoamiController@index');
    }

    public function logout()
    {
        $identity = new UwIdentityLocator();
        $identity->spoof(null);
        return redirect()->action('WhoamiController@index');
    }

}
