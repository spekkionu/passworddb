<?php

/**
 * Admin login api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Admin
 */
class Api_Controller_Admin
{

    /**
     * Returns list of admin logins
     * @url /api/admin/:website_id
     * @method GET
     */
    public static function listAction($website_id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->websiteExists($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
                return $response;
            }
            $mgr = new Model_Admin();
            $results = $mgr->getAdminLogins($website_id);
            $response->body(json_encode(array('success' => true, 'records' => $results)));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error listing admins for website ".$website_id.". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Returns details for an admin login
     * @url /api/admin/:website_id/:id
     * @method GET
     */
    public static function detailsAction($website_id, $id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->websiteExists($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
                return $response;
            }
            $mgr = new Model_Admin();
            $results = $mgr->getAdminLoginDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Admin login credentials not found")));
                return $response;
            }
            $response->body(json_encode(array('success' => true, 'record' => $results)));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error showing admin {$id} for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Adds a new admin login
     * @url /api/admin/:website_id
     * @method POST
     */
    public static function addAction($website_id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->websiteExists($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
                return $response;
            }
            $mgr = new Model_Admin();
            $results = $mgr->addAdminLogin($website_id, $app->request()->post());
            $app->getLog()->info("Admin {$results['id']} added for website " . $website_id . ".");
            $response->status(201);
            $response->body(json_encode(array('success' => true, 'message' => 'Admin Login has been added.', 'record' => $results)));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error adding admin for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
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
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
                return $response;
            }
            $mgr = new Model_Admin();
            $results = $mgr->getAdminLoginDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Admin login credentials not found")));
                return $response;
            }
            $results = array_merge($results, array_intersect_key($app->request()->post(), $results));
            $results = $mgr->updateAdminLogin($id, $results, $website_id);
            $app->getLog()->info("Admin {$id} updated for website " . $website_id . ".");
            $response->status(200);
            $response->body(json_encode(array('success' => true, 'message' => 'Admin login has been updated.', 'record' => $results)));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error updating admin {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
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
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
                return $response;
            }
            $mgr = new Model_Admin();
            $results = $mgr->getAdminLoginDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Admin login credentials not found")));
                return $response;
            }
            $results = $mgr->deleteAdminLogin($id);
            $app->getLog()->info("Admin {$id} deleted from website " . $website_id . ".");
            $response->status(204);
            $response->body(json_encode(array('success' => true, 'message' => 'Admin login has been deleted.')));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error deleting admin {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }
}
