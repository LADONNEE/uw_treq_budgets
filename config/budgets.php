<?php
/**
 * Criteria for UW Budget Data loaded from the EDW
 * @see App\Edw\BudgetDataSource
 */
return [
    'scope' => [
        'college-codes' => [
            '282' => 'UNDERGRADUATE ACADEMIC AFFAIRS',
        ],
        'org-codes' => [
            '2820001000' => 'UWORG ADMINISTRATION',
/*            '2552258000' => 'AP CR CERT-EDUCATION',
            '2552558000' => 'AP NC STAL-EDUCATION',
            '2800258010' => 'SQ-EDUCATION',
*/        ],
        'budgets' => [
            '061303' => 'UWORG ADMINISTRATION',
        ],
    ]
];
