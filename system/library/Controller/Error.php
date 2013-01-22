<?php

/**
 * Error controller
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Error
 */
class Controller_Error
{

    public static function notfoundAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->status(404);
        $response->header('Content-Type', 'application/json');
        $response->body(json_encode(array('success' => false, 'message' => 'Requested URI is not found.')));
        return $response;
    }
}
