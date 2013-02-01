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
        $app->render('error/not-found.twig', array(), 404);
        return $response;
    }

    public static function errorAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $app->render('error/error.twig', array(), 500);
        return $response;
    }
}
