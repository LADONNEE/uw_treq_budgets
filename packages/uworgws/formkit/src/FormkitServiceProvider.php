<?php
namespace Uworgws\Formkit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormkitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('setForm', function($expression) {
            return "<?php \$_f=$expression; ?>";
        });
        Blade::directive('input', function($expression) {
            return "<?php \$_f=\$_f ?? \$form; \$_iv=\$_f->inputView({$expression}); echo \$__env->make(\$_iv->view(), \$_iv->vars())->render(); ?>\n";
        });
        Blade::directive('inputBlock', function($expression) {
            return "<?php \$_f=\$_f ?? \$form; \$_iv=\$_f->inputView({$expression}); echo \$__env->make('inputs.block', \$_iv->vars())->render(); ?>\n";
        });
        Blade::directive('inputError', function($expression) {
            return "<?php \$_f=\$_f ?? \$form; \$_iv=\$_f->inputView({$expression}); echo \$__env->make('inputs.error', \$_iv->vars())->render(); ?>\n";
        });
    }
}
