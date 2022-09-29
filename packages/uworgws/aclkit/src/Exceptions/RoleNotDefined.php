<?php
namespace Uworgws\Aclkit\Exceptions;

use Throwable;

class RoleNotDefined extends \Exception
{
    public function __construct($role, $code = 0, Throwable $previous = null)
    {
        $message = "No ACL configuration for role '$role'";
        parent::__construct($message, $code, $previous);
    }
}
