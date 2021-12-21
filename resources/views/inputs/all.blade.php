<?php
/**
 * @var $form App\Forms\Form
 */
foreach ($form->getInputs() as $input) {
    $_iv = $input->getInputView();
    echo $__env->make('inputs.block', $_iv->vars())->render();
}
