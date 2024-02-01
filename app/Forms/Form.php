<?php
namespace App\Forms;

use Illuminate\Database\Eloquent\Model;
use Utilws\Formkit\Form as FormkitForm;

abstract class Form extends FormkitForm
{
    public function fill($data)
    {
        if ($data instanceof Model) {
            parent::fill($data->toArray());
        } else {
            parent::fill($data);
        }
    }

    public function fillUserInput($data = null)
    {
        if ($data === null) {
            parent::fillUserInput(request()->all());
        } else {
            parent::fillUserInput($data);
        }
    }

    public function process($data = null)
    {
        if ($data === null) {
            return parent::process(request()->all());
        } else {
            return parent::process($data);
        }
    }

    protected function storeState($key, $data)
    {
        session()->put($key, $data);
    }

    protected function retrieveState($key)
    {
        $out = session($key, []);
        session()->forget($key);
        return $out;
    }

    protected function clearState($key)
    {
        session()->forget($key);
    }
}
