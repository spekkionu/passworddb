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
     * Returns list of ftp logins
     * @url /api/ftp/:website_id
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
                return $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
            }
            $mgr = new Model_FTP();
            $results = $mgr->getFTPLogins($website_id);
            return $response->body(json_encode(array('success' => true, 'records' => $results)));
        } catch (Exception $e) {
            $app->getLog()->error("Error listing ftp logins for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
        }
    }

    /**
     * Returns details for an ftp login
     * @url /api/ftp/:website_id/:id
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
                return $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
            }
            $mgr = new Model_FTP();
            $results = $mgr->getFTPDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                return $response->body(json_encode(array('success' => false, 'message' => "FTP login credentials not found")));
            }
            return $response->body(json_encode(array('success' => true, 'record' => $results)));
        } catch (Exception $e) {
            $app->getLog()->error("Error showing ftp {$id} for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
        }
    }

    /**
     * Adds a new ftp login
     * @url /api/ftp/:website_id
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
                return $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
            }
            $mgr = new Model_FTP();
            $results = $mgr->addFTP($website_id, $_POST);
            $app->getLog()->info("FTP {$results['id']} added for website " . $website_id . ".");
            $response->status(201);
            return $response->body(json_encode(array('success' => true, 'message' => 'FTP Login has been added.', 'record' => $results)));
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
            } else {
                $app->getLog()->error("Error adding ftp for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            }
        }
    }

    /**
     * Updates an ftp login
     * @url /api/ftp/:website_id/:id
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
                return $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
            }
            $mgr = new Model_FTP();
            $results = $mgr->getFTPDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                return $response->body(json_encode(array('success' => false, 'message' => "FTP login credentials not found")));
            }
            $results = array_merge($results, array_intersect_key($_POST, $results));
            $results = $mgr->updateFTP($id, $results, $website_id);
            $app->getLog()->info("FTP {$id} updated for website " . $website_id . ".");
            $response->status(200);
            return $response->body(json_encode(array('success' => true, 'message' => 'FTP login has been updated.', 'record' => $results)));
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
            } else {
                $app->getLog()->error("Error updating ftp {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            }
        }
    }

    /**
     * Deletes an ftp login
     * @url /api/ftp/:website_id/:id
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
                return $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
            }
            $mgr = new Model_FTP();
            $results = $mgr->getFTPDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                return $response->body(json_encode(array('success' => false, 'message' => "FTP login credentials not found")));
            }
            $results = $mgr->deleteFTP($id);
            $app->getLog()->info("FTP {$id} deleted from website " . $website_id . ".");
            $response->status(204);
            return $response->body(json_encode(array('success' => true, 'message' => 'FTP login has been deleted.')));
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
            } else {
                $app->getLog()->error("Error deleting ftp {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                return $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            }
        }
    }
}
