<?php
/**
 * @package app.treq.school
 */
/** 
 * Factory for repositories
 * Builds instances and maintains references
 */

namespace App\Repositories;

class RepositoryFactory
{
    protected $config;
    protected $instances = [];

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function has($name)
    {
        return (isset($this->instances[$name]) || isset($this->config[$name]));
    }

    public function get($name)
    {
        if (!isset($this->instances[$name])) {
            $this->build($name);
        }
        return $this->instances[$name];
    }

    public function set($name, $repo)
    {
        $this->instances[$name] = $repo;
    }

    protected function build($name)
    {
        if (!isset($this->config[$name])) {
            throw new \Exception('No repository configured for "'.$name.'"');
        }
        $class = $this->config[$name];
        $this->instances[$name] = \App::make($class);
    }

}
