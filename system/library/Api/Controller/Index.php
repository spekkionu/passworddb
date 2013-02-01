<?php

/**
 * List api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage API
 */
class Api_Controller_Index
{

    public static function listAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $response->body(json_encode(array('success' => true, 'methods' => array('api/website'))));
        return $response;
    }
}
