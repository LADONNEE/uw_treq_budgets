<?php
/**
 * Render an HTML input based on a Uwcoenvws\Formkit\Input object
 * Expects $input variable to contain either an Input object or string name of
 * an Input contained in the current Form object.
 */

if (is_string($input)) {
    $_f = $_f ?? $form;
    $input = $_f->input($input);
}

$_iv = $input->getInputView();

echo $__env->make($_iv->view(), $_iv->vars())->render();
