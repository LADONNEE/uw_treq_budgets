<?php

/**
 * Form input collection with Laravel infrastructure
 */

namespace App\Forms;

use App\Core\Forms\AbstractForm as PcoreForm;
use App\Core\Forms\Input;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractForm extends PcoreForm
{

    /**
     * Map an array of values to this collection's Input values
     * Accepts array or Eloquent Model
     * @param $data
     */
    public function fill($data)
    {
        if ($data instanceof Model) {
            $data = $data->toArray();
        }
        foreach ($data as $name => $value) {
            if ($this->hasInput($name)) {
                $this->_inputs[$name]->value($value);
            }
        }
    }

    /**
     * Add a type ahead person matching widget to the form
     * @param string $valueInputName
     * @param string $widgetInputName
     * @return Input
     */
    public function newPersonTypeahead($valueInputName = 'person_id', $widgetInputName = 'person-typeahead')
    {
        $value = new Input($valueInputName, '', 'hidden');
        $this->addInput($value);
        // <input type="text" value="" class="person-typeahead" placeholder="Search by name or NetID" style="width:300px;margin-top:-3px;" />
        $widget = new Input($widgetInputName, 'Choose Person', 'text');
        $widget->setAttribute('class', 'person-typeahead');
        $widget->setAttribute('placeholder', 'Search by name or NetID');
        return $widget;
    }

    /**
     * Adds Laravel CRSF token to open tag
     * @param array $options
     * @return string
     */
    public function open($options = array())
    {
        return parent::open($options) . '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }

    /**
     * Makes $data argument optional, injects Laravel Http\Request::all() if missing
     * @param null $data
     * @return boolean
     */
    public function process($data = null)
    {
        if ($data === null) {
            $data = request()->all();
        }
        return parent::process($data);
    }

    /**
     * Providing user input indicates any error state should be cleared
     * @param $data
     */
    public function fillUserInput($data)
    {
        $this->clearErrors();
        parent::fillUserInput($data);
    }

    /**
     * Clear error state from inputs and from flash storage
     */
    public function clearErrors()
    {
        session()->forget('form');
        foreach ($this->_inputs as $input) {
            $input->error(null);
        }
    }

    /**
     * Store input values and error messages to session
     */
    public function flash()
    {
        $data = [];
        foreach ($this->_inputs as $input) {
            $data[$input->name] = [
                'value' => $input->value,
                'error' => $input->error,
            ];
        }
        session()->put('form', $data);
    }

    /**
     * Retrieve input values and error messages from session
     */
    public function unflash()
    {
        $data = session()->pull('form', []);
        foreach ($data as $name => $d) {
            if ($this->hasInput($name)) {
                $this->input($name)
                    ->value($d['value'])
                    ->error($d['error']);
            }
        }
    }

    /**
     * Use Laravel session tools to store form state
     */
    public function validationFailed()
    {
        $this->flash();
    }

}
