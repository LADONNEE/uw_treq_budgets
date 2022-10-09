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
            //return redirect()->away('/budgets/saml/login/' . urlencode($request->fullUrl()));
            return redirect()->away('https://ischool.uw.edu/uwlogin?shiblogin=1&target=' . urlencode($request->fullUrl()));
        }
        if (!hasRole('budget:user') && $request->path() != 'logout' && $request->path() != 'whoami') {
            abort(403, 'Not authorized');
        }
        return $next($request);
    }
}
