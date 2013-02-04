<?php

/**
 * FTP logins api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage FTP
 */
class Controller_FTP
{

    /**
     * Adds a new ftp login
     * @url /ftp/:website_id/add
     * @method GET,POST
     */
    public static function addAction($website_id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id, false);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $results = Model_FTP::$default;
        if ($app->request()->isPost()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('ftp'), $results));
                $mgr = new Model_FTP();
                $results = $mgr->addFTP($website_id, $results);
                $app->flash('success', "FTP added for website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('ftp/add.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error adding FTP info.'), 500);
                return $response;
            }
        }
        $app->render('ftp/add.twig', array('website' => $website, 'results' => $results));
    }

    /**
     * Updates an ftp login
     * @url /api/ftp/:website_id/:id
     * @method GET,POST,PUT
     */
    public static function updateAction($website_id, $id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id, false);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $mgr = new Model_FTP();
        $results = $mgr->getFTPDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'The FTP info you requested does not exist.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isPut()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('ftp'), $results));
                $results = $mgr->updateFTP($id, $results, $website_id);
                $app->flash('success', "FTP login {$id} has been updated for website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('ftp/edit.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error updating FTP info.'), 500);
                return $response;
            }
        }
        $app->render('ftp/edit.twig', array('website' => $website, 'results' => $results));
    }

    /**
     * Deletes an ftp login
     * @url /api/ftp/:website_id/:id
     * @method GET,POST,DELETE
     */
    public static function deleteAction($website_id, $id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id, false);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $mgr = new Model_FTP();
        $results = $mgr->getFTPDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'The FTP info you requested does not exist.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isDelete()) {
            try {
                $results = $mgr->deleteFTP($id);
                $app->flash('info', "FTP {$id} deleted from website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website_id}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('ftp/delete.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => "Error deleting ftp {$id} for website {$website['name']}."), 500);
                return $response;
            }
        }
        $app->render('ftp/delete.twig', array('website' => $website, 'results' => $results));
    }
}
