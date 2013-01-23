<?php
/**
 * HTTP Authentication
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Auth
 * @subpackage Middleware
 */
class Middleware_HttpApplicationAuth extends Slim_Middleware
{
    /**
     * @var string
     */
    protected $realm;

    /**
     * Constructor
     *
     * @param   string  $username   The HTTP Authentication username
     * @param   string  $password   The HTTP Authentication password
     * @param   string  $realm      The HTTP Authentication realm
     */
    public function __construct($realm = 'Protected Area')
    {
        $this->realm = $realm;
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
        if ($authUser && $authPass) {
            // Check login
            $mgr = new Model_Login();
            try{
                $mgr->checkLogin($authUser, $authPass);
                $this->next->call();
            }catch(Exception $e){
                $res->status(401);
                $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
            }
        } else {
            $res->status(401);
            $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
        }
    }
}
