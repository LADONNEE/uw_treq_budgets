<?php

namespace App\Forms;

use App\Models\Setting;

class SettingsForm extends Form
{
    public function createInputs()
    {
        $this->add('setting_name', 'Setting');
        $this->add('value', 'Value');
    }

    public function validate()
    {
        $this->check('setting_name')->notEmpty();
        $this->check('value')->notEmpty();
    }

    public function commit()
    {
        $setting = Setting::firstOrNew([
            'name' => $this->value('setting_name')
        ]);
        $setting->value = $this->value('value');
        $setting->changed_by = user()->person_id;
        $setting->save();
    }
}
