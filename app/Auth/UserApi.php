<?php
/**
 * @package app.treq.school
 */

/**
 * API user object
 * Empty User provided for local API requests
 */

namespace App\Auth;

class UserApi extends UserAnonymous
{
    /**
     * API Client identifier
     * @var string
     */
    public $client;

    public function __construct($client)
    {
        $this->properties = [
            'id' => 0,
            'person_id' => 0,
            'uwnetid' => '(api)',
            'firstname' => '',
            'lastname' => 'API',
        ];
        $this->roles = [];
        $this->client = $client;
    }

}
