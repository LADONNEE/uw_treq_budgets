<?php

namespace App\Forms;

use App\Forms\Validation\TypeaheadHasId;
use App\Forms\Validation\ValidateDateTrait;
use App\Models\Allocation;
use App\Models\Budget;

class AllocationForm extends Form
{
    use ValidateDateTrait;

    private $allocation;

    public function __construct(Allocation $allocation)
    {
        $this->allocation = $allocation;
    }

    public function createInputs()
    {
        $this->add('_action', 'hidden');

        $this->add('faculty_contact_id', 'hidden');

        $this->add('budget_id', 'hidden');
        $this->add('budget_typeahead')
            ->required();

        $this->add('type', 'radio')
            ->options(Allocation::$typeOptions);
        $this->add('additional_pay_category', 'select')
            ->label('Category')
            ->options(Allocation::$additionalPayCategories)
            ->firstOption('select one')
            ->required();
        $this->add('additional_pay_fixed_monthly', 'hidden')
            ->label('Amount (per month)')
            ->required();
        $this->add('allocation_category', 'select')
            ->label('Category')
            ->options(Allocation::$allocationCategories)
            ->firstOption('select one')
            ->required();
        $this->add('cross_unit_budget_no', 'text')
            ->label('CU Budget Number')
            ->required();
        $this->add('cross_unit_budget_name', 'text')
            ->label('CU Budget Name')
            ->required();
        $this->add('start_at', 'text')
            ->label('Start Date')
            ->date()
            ->required();
        $this->add('end_at', 'text')
            ->label('End Date')
            ->date()
            ->required();
        $this->add('allocation_percent', 'text')
            ->label('Percent Effort')
            ->required();
        $this->add('pca_code', 'text')
            ->label('PCA Code (optional)');
    }

    public function initValues()
    {
        $this->fill($this->allocation->toArray());

        $this->fill([
            'start_at' => edate($this->allocation->start_at),
            'end_at' => edate($this->allocation->end_at),
        ]);

        if ($this->allocation->allocation_category === 'CROSS UNIT EFFORT') {
            $this->fill([
                'cross_unit_budget_no' => $this->allocation->budget_id ? $this->allocation->budget->budgetno : '',
                'cross_unit_budget_name' => $this->allocation->budget_id ? $this->allocation->budget->non_coe_name : '',
            ]);
        } else {
            $this->fill([
                'budget_typeahead' =>  $this->allocation->budget_id ?
                    "{$this->allocation->budget->budgetno}" : '',
            ]);
        }
    }

    public function validate()
    {
        $this->check('start_at')->notEmpty();
        $this->check('end_at')->notEmpty();
        $this->validateDate('start_at');
        $this->validateDate('end_at');

        // if cross unit budget number is empty, make sure a budget number was provided, otherwise validate format
        if ($this->isEmpty('cross_unit_budget_no')) {
            $this->check('budget_typeahead')->notEmpty();
        } else if (!preg_match('/^\d{2}-\d{4}$/', $this->get('cross_unit_budget_no'))) {
            $this->error('cross_unit_budget_no', 'Budget number must be formatted ##-####');
        }

        // if budget search is empty, make sure cross unit budget number is provided
        if ($this->isEmpty('budget_typeahead')) {
            $this->check('cross_unit_budget_no')->notEmpty();
            $this->check('cross_unit_budget_name')->notEmpty();
        }

        if ($this->get('type') === 'ALLOCATION') {
            $this->check('allocation_percent')->notEmpty();
        }

        if ($this->get('type') === 'ADDITIONAL PAY') {
            $this->check('additional_pay_fixed_monthly')->notEmpty();
        }

        $id = $this->get('budget_id');
        $search = $this->get('budget_typeahead');

        $this->check('budget_typeahead')->using(new TypeaheadHasId($id, $search));
    }

    public function commit()
    {
        if ($this->get('_action') === 'delete') {
            $this->allocation->delete();
            return;
        }

        $this->allocation->fill($this->without('allocation_percent'));

        $this->allocation->allocation_percent = preg_replace('/[^0-9\.]/', '', $this->get('allocation_percent'));

        if ($this->get('allocation_category') !== 'CROSS UNIT EFFORT') {
            $this->set('cross_unit_budget_no', '');
            $this->set('cross_unit_budget_name', '');
        }

        if (!$this->isEmpty('cross_unit_budget_no')) {
            $budget = Budget::updateOrCreate(
                [
                    'budgetno' => $this->get('cross_unit_budget_no'),
                    'is_coe' => '0'
                ],
                ['non_coe_name' => $this->get('cross_unit_budget_name'),]
            );

            $this->allocation->budget_id = $budget->id;
        }

        if ($this->get('type') === 'ALLOCATION') {
            $this->allocation->fill([
                'additional_pay_category' => null,
                'additional_pay_fixed_monthly' => null
            ]);
        }

        if ($this->get('type') === 'ADDITIONAL PAY') {
            $this->allocation->fill([
                'allocation_category' => null,
                'allocation_percent' => null
            ]);
        }

        $this->allocation->editedBy(user());
        $this->allocation->save();
    }
}
