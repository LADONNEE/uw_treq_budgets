<?php
namespace Utilws\Aclkit;

class PathAuthorizer
{
    protected $basePaths = [];
    protected $rules;

    public function __construct(array $config, $basePaths = null)
    {
        if (is_array($basePaths)) {
            $this->basePaths = $basePaths;
        }
        $this->parseConfig($config);
    }

    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Return the most specific rule index that matches provided path
     * @param $path
     * @return string|null
     */
    public function matchedRule($path)
    {
        $path = $this->stripPrefixes($path);
        while (strlen($path) > 1) {
            if (isset($this->rules[$path])) {
                return $path;
            } else {
                $path = dirname($path);
            }
        }
        return (isset($this->rules[''])) ? '' : null;
    }

    /**
     * Returns the role required to access $path
     * @param string $path
     * @return string|null
     */
    public function requiredRole($path)
    {
        $index = $this->matchedRule($path);
        if ($index !== null) {
            return $this->rules[$index];
        }
        return null;
    }

    /**
     * Read an ACL configuration array into operational structure
     * @param $config
     */
    protected function parseConfig(array $config)
    {
        $this->rules = [];
        foreach ($config as $pattern => $requiredRole) {
            $this->rules[trim($pattern, '/')] = $requiredRole;
        }
    }

    /**
     * Return the application relative part of the URL $resource
     * @param $path
     * @return string
     */
    protected function stripPrefixes($path)
    {
        $regex = '/^https?:\/\/[^\/]+(.*)/';
        $matches = [];
        if (preg_match($regex, $path, $matches)) {
            $path = $matches[1];
        }
        $path =  trim($path, '/');
        foreach ($this->basePaths as $base) {
            if (strpos($path, $base) === 0) {
                $path = substr($path, strlen($base));
            }
        }
        return trim($path, '/');
    }

}
