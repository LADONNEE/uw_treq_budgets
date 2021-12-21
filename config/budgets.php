<?php
/**
 * Criteria for UW Budget Data loaded from the EDW
 * @see App\Edw\BudgetDataSource
 */
return [
    'scope' => [
        'college-codes' => [
            '258' => 'COLLEGE OF EDUCATION',
        ],
        'org-codes' => [
            '2552158000' => 'AP DEG - EDUCATION',
            '2552258000' => 'AP CR CERT-EDUCATION',
            '2552558000' => 'AP NC STAL-EDUCATION',
            '2800258010' => 'SQ-EDUCATION',
        ],
        'budgets' => [
            '098270' => 'ISLANDWOOD-BAINBRIDGE',
            '098650' => 'NATIVE ED CONTRACT',
            '098442' => 'PRACTICUM BACB',
            '098647' => 'NATIVE ED CERT',
            '098669' => 'PRACTICE BASED COACH',
            '098703' => 'ISLANDWOOD -UW SEATTLE',
        ],
    ]
];
