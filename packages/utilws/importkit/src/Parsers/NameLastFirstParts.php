<?php
namespace Utilws\Importkit\Parsers;

/**
 * Adapter for NameLastFirst that takes $input argument for getters
 * Allows a multipart name value to be parsed once and values to be retrieved without reprocessing.
 *
 * $firstlast = new NameLastFirstParts;
 * $rowParser = new RowParser([
 *     'firstname' => [ 'index' => 1,  'parser' => [ $firstlast, 'getFirstName' ] ],
 *     'lastname'  => [ 'index' => 1,  'parser' => [ $firstlast, 'getLastName' ] ],
 * ]);
 */
class NameLastFirstParts extends BaseParser
{
    protected $firstlast;
    public $first;
    public $last;
    public $middle;
    public $middleInitial;

    public function __construct()
    {
        $this->firstlast = new NameLastFirst();
        $this->first = [ $this, 'getFirstName' ];
        $this->last = [ $this, 'getLastName' ];
        $this->middle = [ $this, 'getMiddle' ];
        $this->middleInitial = [ $this, 'getMiddleInitial' ];
    }

    protected function parseValue($input)
    {
        return $this->firstlast->parse($input);
    }
    
    public function getFirstName($input)
    {
        $this->firstlast->parse($input);
        return $this->firstlast->getFirstName();
    }

    public function getLastName($input)
    {
        $this->firstlast->parse($input);
        return $this->firstlast->getLastName();
    }

    public function getMiddle($input)
    {
        $this->firstlast->parse($input);
        return $this->firstlast->getMiddle();
    }

    public function getMiddleInitial($input)
    {
        $this->firstlast->parse($input);
        return $this->firstlast->getMiddleInitial();
    }
    
}
