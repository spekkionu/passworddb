<?php

/**
 * Database login api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Database
 */
class Api_Controller_Database
{

    /**
     * Returns list of admin logins
     * @url /api/database/:website_id
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
            $mgr = new Model_Database();
            $results = $mgr->getDBLogins($website_id);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error listing databases for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Returns details for an admin login
     * @url /api/database/:website_id/:id
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
            $mgr = new Model_Database();
            $results = $mgr->getDBDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Database login credentials not found")));
                return $response;
            }
            return $response->body(json_encode($results));
        } catch (Exception $e) {
            $app->getLog()->error("Error showing database {$id} for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Adds a new admin login
     * @url /api/database/:website_id
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
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $mgr = new Model_Database();
            $results = $mgr->addDB($website_id, $data);
            $app->getLog()->info("Database {$results['id']} added for website " . $website_id . ".");
            $response->status(201);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error adding database for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Updates an admin login
     * @url /api/database/:website_id/:id
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
            $mgr = new Model_Database();
            $results = $mgr->getDBDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Database login credentials not found")));
                return $response;
            }
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $results = array_merge($results, array_intersect_key($data, $results));
            $results = $mgr->updateDB($id, $results, $website_id);
            $app->getLog()->info("Database {$id} updated for website " . $website_id . ".");
            $response->status(200);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error updating database {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Deletes an admin login
     * @url /api/database/:website_id/:id
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
            $mgr = new Model_Database();
            $results = $mgr->getDBDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Database login credentials not found")));
                return $response;
            }
            $results = $mgr->deleteDB($id);
            $app->getLog()->info("Database {$id} deleted from website " . $website_id . ".");
            $response->status(204);
            $response->body(json_encode(array('success' => true, 'message' => 'Database login has been deleted.')));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error deleting database {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }
}
