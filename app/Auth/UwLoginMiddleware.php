<?php
namespace App\Auth;

use Closure;
use Illuminate\Contracts\Container\Container;

class UwLoginMiddleware
{
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->app['user'];
        if ($user instanceof UserAnonymous) {
            return redirect()->to('/budgets/saml/login/' . $request->path());
            // return redirect()->away('/budgets/saml/login/' . urlencode($request->fullUrl()));
            //return redirect()->away('https://ischool.uw.edu/Shibboleth.sso/Login?target=' . $request->fullUrl());
        }
        if (!hasRole('budget:user') && $request->path() != 'budgets/logout' && $request->path() != 'budgets/whoami') {
            abort(403, 'Not authorized');
        }
        return $next($request);
    }
}
