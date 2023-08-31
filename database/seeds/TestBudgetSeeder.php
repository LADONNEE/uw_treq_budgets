<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\UwBudget;
use App\Models\Setting;

class TestBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Setting::firstOrCreate([
            'name' => 'current-biennium',
            'value' => 2021,
            'changed_by' => 1
        ]);
        
        Setting::firstOrCreate([
            'name' => 'fiscal-person-default',
            'value' => 'nbedani',
            'changed_by' => 1
        ]);

        Budget::firstOrCreate([
            'budgetno'=> '01-0001',
            'is_coe' => true,
            'pi_person_id' => 1,
            'fiscal_person_id' => 1,
            'reconciler_person_id' => 1,
            'business_person_id' => 1,
            'non_coe_name' => 'Test budget 01',
            'purpose_brief' => 'Test budget',
            'food' => 'Y',
        ]);

        UwBudget::firstOrCreate([
            'BienniumYear' => 2021,
            'BudgetNbr' => '010001',
            'BudgetName' => 'Test budget 01',
            'BudgetStatus' => 1,
            'BudgetType' => 01,
            'BudgetClass' => '1',
            'BudgetClassDesc' => 'Test budget class description',
            'EffectiveDate' => '20230101',
            'OrgCode' => 'UWORG',
            'PayrollUnitCode' => NULL,
            'PrincipalInvestigatorId' => NULL,
            'PrincipalInvestigator' => NULL,
            'TotalPeriodBeginDate' => '20230101',
            'TotalPeriodEndDate' => '20330101',
            'AccountingIndirectCostRate' => NULL,
            'budgetno' => '01-0001',
            'updating' => 0
        ]);

        
    }
}
