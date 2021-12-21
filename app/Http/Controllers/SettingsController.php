<?php
namespace App\Http\Controllers;

use App\Forms\SettingsForm;
use App\Models\Setting;

class SettingsController extends Controller
{
    protected $settings = [
        'current-biennium' => '',
        'fiscal-person-default' => '',
    ];

    public function index()
    {
        $this->authorize('budget:admin');
        $settings = Setting::orderBy('name')
            ->pluck('value', 'name')
            ->all();
        $settings = array_merge($this->settings, $settings);
        return view('settings/index', compact('settings'));
    }

    public function store()
    {
        $this->authorize('settings');
        $form = new SettingsForm();
        $form->process();
        if ($form->hasErrors()) {
            $debug = [
                'request' => request()->all(),
                'errors' => $form->getErrors(),
                'values' => $form->all(),
            ];
            dd($debug);
        }
        return redirect()->action('SettingsController@index');
    }

}
