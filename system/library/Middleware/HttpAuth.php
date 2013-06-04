<?php

/**
 * HTTP Authentication
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Auth
 * @subpackage Middleware
 */
class Middleware_HttpAuth extends Slim_Middleware
{

    /**
     * HTTP Auth Realm
     * @var string
     */
    protected $realm;

    /**
     * Login Credentials
     * @var array
     */
    protected $credentials = array();

    /**
     * Constructor
     *
     * @param   string  $username   The HTTP Authentication username
     * @param   string  $password   The HTTP Authentication password
     * @param   string  $realm      The HTTP Authentication realm
     */
    public function __construct($realm = 'Protected Area', array $credentials = array())
    {
        $this->realm = $realm;
        $this->credentials = $credentials;
    }

    /**
     * Call
     *
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     */
    public function call()
    {
        $req = $this->app->request();
        $res = $this->app->response();
        $authUser = $req->headers('PHP_AUTH_USER');
        $authPass = $req->headers('PHP_AUTH_PW');
        if (!empty($authUser) && !empty($authPass)) {
            // Check login
            if (array_key_exists($authUser, $this->credentials) && $this->credentials[$authUser] == $authPass) {
                $this->next->call();
            } else {
                $res->status(401);
                $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
            }
        } else {
            $res->status(401);
            $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
        }
    }
}
