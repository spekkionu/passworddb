<?php

/**
 * Database login api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Database
 */
class Controller_Database
{

    /**
     * Adds a new database login
     * @url /database/:website_id/add
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
        $results = Model_Database::$default;
        if ($app->request()->isPost()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('database'), $results));
                $mgr = new Model_Database();
                $results = $mgr->addDB($website_id, $results);
                $app->flash('success', "Database {$results['id']} added for website {$website['name']}.");
                $response->redirect("/website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('database/add.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error adding Database info.'), 500);
                return $response;
            }
        }
        $app->render('database/add.twig', array('website' => $website, 'results' => $results));
    }

    /**
     * Updates a database login
     * @url /database/:website_id/:id/edit
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
        $mgr = new Model_Database();
        $results = $mgr->getDBDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'Database login credentials not found.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isPut()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('database'), $results));
                $results = $mgr->updateDB($id, $results, $website_id);
                $app->flash('success', "Database login {$id} has been updated for website {$website['name']}.");
                $response->redirect("/website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('database/edit.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error updating database info.'), 500);
                return $response;
            }
        }
        $app->render('database/edit.twig', array('website' => $website, 'results' => $results));
    }

    /**
     * Deletes a database login
     * @url /database/:website_id/:id/delete
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
        $mgr = new Model_Database();
        $results = $mgr->getDBDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'Database login credentials not found.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isDelete()) {
            try {
                $results = $mgr->deleteDB($id);
                $app->flash('info', "Database {$id} deleted from website {$website['name']}.");
                $response->redirect("/website/{$website_id}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('database/delete.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => "Error deleting database {$id} for website {$website['name']}."), 500);
                return $response;
            }
        }
        $app->render('database/delete.twig', array('website' => $website, 'results' => $results));
    }
}
