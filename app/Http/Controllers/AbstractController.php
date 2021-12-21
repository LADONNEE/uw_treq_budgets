<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    use ValidatesRequests;

    /**
     * If current user does not have $role abort response with 403 Not Authorized
     * @param string $role
     */
    public function authorize($role)
    {
        if (!\App::make('acl')->hasRole($role)) {
            abort(403);
        }
    }

}
