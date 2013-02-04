<?php

/**
 * Admin login api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Admin
 */
class Controller_Admin
{

    /**
     * Adds a new admin login
     * @url /api/admin/:website_id
     * @method POST
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
        $results = Model_Admin::$default;
        if ($app->request()->isPost()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('admin'), $results));
                $mgr = new Model_Admin();
                $results = $mgr->addAdminLogin($website_id, $results);
                $app->flash('success', "Admin login {$results['id']} added for website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('admin/add.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error adding admin login.'), 500);
                return $response;
            }
        }
        $app->render('admin/add.twig', array('website' => $website, 'results' => $results));
    }

    /**
     * Updates an admin login
     * @url /api/admin/:website_id/:id
     * @method PUT
     */
    public static function updateAction($website_id, $id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $mgr = new Model_Admin();
        $results = $mgr->getAdminLoginDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'Admin login credentials not found.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isPut()) {
            try {

                $results = array_merge($results, array_intersect_key($app->request()->post('admin'), $results));
                $results = $mgr->updateAdminLogin($id, $results, $website_id);
                $app->flash('success', "Admin login {$id} has been updated for website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('admin/edit.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error updating admin login.'), 500);
                return $response;
            }
        }
        $app->render('admin/edit.twig', array('website' => $website, 'results' => $results));
    }

    /**
     * Deletes an admin login
     * @url /api/admin/:website_id/:id
     * @method DELETE
     */
    public static function deleteAction($website_id, $id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $mgr = new Model_Admin();
        $results = $mgr->getAdminLoginDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'Admin login credentials not found.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isDelete()) {
            try {

                $results = $mgr->deleteAdminLogin($id);
                $app->flash('info', "Admin login {$id} deleted from website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website_id}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('admin/delete.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => "Error deleting admin login {$id} for website {$website['name']}."), 500);
                return $response;
            }
        }
        $app->render('admin/delete.twig', array('website' => $website, 'results' => $results));
    }
}
