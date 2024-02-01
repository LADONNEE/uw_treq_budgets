<?php
namespace Utilws\Formkit;

use Utilws\Formkit\Form as FormkitForm;

abstract class FormForLaravel extends FormkitForm
{
    public function fill($data)
    {
        if ($data instanceof \Illuminate\Database\Eloquent\Model) {
            parent::fill($data->toArray());
        } else {
            parent::fill($data);
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
