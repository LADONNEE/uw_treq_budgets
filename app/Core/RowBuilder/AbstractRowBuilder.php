<?php
/**
 * @package edu.uw.environment.pcore
 */
/**
 * Converts an object graph into a simple array, for example report output to be presented as CSV view
 * Provides a strategy for reusing entity specific extraction logic. Define a row representation for
 * a Student, then reuse that Student component in a Major report, Placement report, TestScore report.
 */
namespace App\Core\RowBuilder;

abstract class AbstractRowBuilder
{
    /**
     * @var AbstractRowBuilder[]
     */
    protected $builders = [];
    protected $extractor;
    protected $headers;
    protected $model;
    protected $modelClass;
    protected $empty;

    public function __construct($extractor = null)
    {
        if (is_callable($extractor)) {
            $this->extractor = $extractor;
        }
    }

    public function add(AbstractRowBuilder $builder)
    {
        $this->builders[] = $builder;
    }

    public function build($row, array $out = [])
    {
        $this->extract($row);
        $out = array_merge($out, $this->getData());
        foreach ($this->builders as $builder) {
            $out = $builder->build($row, $out);
        }
        return $out;
    }

    public function headers(array $out = [])
    {
        $out = array_merge($out, $this->getHeaders());
        foreach ($this->builders as $builder) {
            $out = $builder->headers($out);
        }
        return $out;
    }

    abstract protected function getData();

    abstract protected function getHeaders();

    protected function extract($row)
    {
        if (is_callable($this->extractor)) {
            $this->model = call_user_func($this->extractor, $row);
        } else {
            $this->model = null;
        }
        if (!$this->model) {
            $this->model = $this->getEmpty();
        }
    }

    protected function getEmpty()
    {
        if (!$this->empty && $this->modelClass) {
            $classname = $this->modelClass;
            $this->empty = new $classname();
        }
        return $this->empty;
    }

}
