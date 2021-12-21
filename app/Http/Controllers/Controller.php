<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        View::share('envCssClass', (config('app.env') === 'production') ? '' : 'env--dev');
    }

    /**
     * If current user does not have $role abort response with 403 Not Authorized
     * @param string $role
     */
    public function authorize($role)
    {
        if (!hasRole($role)) {
            abort(403);
        }
    }
}
