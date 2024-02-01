<?php
namespace App\Forms\Validation;

use Utilws\Formkit\Validators\BaseValidator;

class TypeaheadHasId extends BaseValidator
{
    private $idInput;
    private $searchInput;

    public function __construct($idInput, $searchInput)
    {
        $this->idInput = $idInput;
        $this->searchInput = $searchInput;
        parent::__construct('Please select from list');
    }

    public function isValid(string $value, array $options): bool
    {
        if ($this->idInput === null && $this->searchInput !== null) {
            return false;
        }

        return true;
    }
}
