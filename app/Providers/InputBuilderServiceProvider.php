<?php
/**
 * @package edu.uw.uaa.college
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Forms\Builders\InputBuilder;

class InputBuilderServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('InputBuilder', function () {
            return new InputBuilder();
        });
    }

}
