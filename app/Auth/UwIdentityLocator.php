<?php
namespace App\Auth;

class UwIdentityLocator
{
    const SPOOF_SESSION_KEY = 'COLLEGE_SPOOF_UWNETID';
    /**
     * Prioritized methods for locating a login indentity
     * @var array
     */
    protected $strategies = [
        'getSpoofedIdentity',
        'getUwIdentity',
        'getUwStubIdentity',
    ];

    /**
     * Return string identifier of logged in user
     * In UW login environment returns user's UW NetID
     * @return string|false
     */
    public function getIdentity()
    {
        foreach ($this->strategies as $method) {
            $uwnetid = $this->$method();
            if ($uwnetid) {
                return $uwnetid;
            }
        }
        return false;
    }

    /**
     * Identity stored by application for testing
     * Spoof identity allows developer to run the application as another user
     * Application UI provides method to set a spoof identity with spoof() method
     * @return string|false
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function getSpoofedIdentity()
    {
        if (app('session')->has(self::SPOOF_SESSION_KEY)) {
            return app('session')->get(self::SPOOF_SESSION_KEY);
        }
        return false;
    }

    /**
     * UW Identity provide by web server authentication module
     * Returns REMOTE_USER value provided by PubCookie or Shibboleth
     * For multi-domain ids, returns only washington.edu, other domains return false
     * @return string|false
     */
    public function getUwIdentity()
    {
        if (empty($_SERVER['REMOTE_USER'])) {
            return false;
        }
        $identity = $_SERVER['REMOTE_USER'];
        if (strpos($identity, '@') !== false) {
            list($identity, $domain) = explode('@', $identity);
            if ($domain != 'washington.edu') {
                return false;
            }
        }
        return $identity;
    }

    /**
     * Stub login
     * For development environment that doesn't have PubCookie/Shibboleth installed
     * To use this add to Apache config
     * SetEnv PUBCOOKIE_STUB_UWNETID hanisko
     * @return string|false
     */
    public function getUwStubIdentity()
    {
        return getenv('PUBCOOKIE_STUB_UWNETID');
    }

    /**
     * Set user identity to be used for testing
     * Set null to clear spoofed identity
     * @param string $uwnetid
     */
    public function spoof($uwnetid)
    {
        if ($uwnetid === null) {
            app('session')->forget(self::SPOOF_SESSION_KEY);
        } else {
            app('session')->put([self::SPOOF_SESSION_KEY => $uwnetid]);
        }
    }
}
