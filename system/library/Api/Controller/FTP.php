<?php

/**
 * FTP logins api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage FTP
 */
class Api_Controller_FTP
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
            $website = Api_Controller_Website::getWebsite($website_id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $mgr = new Model_FTP();
            $results = $mgr->getFTPLogins($website_id);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error listing ftp logins for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
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
            $website = Api_Controller_Website::getWebsite($website_id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $results = self::getFTP($id, $website_id);
            if ($results instanceof Slim_Http_Response) {
                return $results;
            }
            return $response->body(json_encode($results));
        } catch (Exception $e) {
            $app->getLog()->error("Error showing ftp {$id} for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
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
            $website = Api_Controller_Website::getWebsite($website_id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $mgr = new Model_FTP();
            $results = $mgr->addFTP($website_id, $data);
            $app->getLog()->info("FTP {$results['id']} added for website " . $website_id . ".");
            $response->status(201);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error adding ftp for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
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
            $website = Api_Controller_Website::getWebsite($website_id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $mgr = new Model_FTP();
            $results = self::getFTP($id, $website_id);
            if ($results instanceof Slim_Http_Response) {
                return $results;
            }
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $results = array_merge($results, array_intersect_key($data, $results));
            $results = $mgr->updateFTP($id, $results, $website_id);
            $app->getLog()->info("FTP {$id} updated for website " . $website_id . ".");
            $response->status(200);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error updating ftp {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
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
            $website = Api_Controller_Website::getWebsite($website_id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $mgr = new Model_FTP();
            $results = self::getFTP($id, $website_id);
            if ($results instanceof Slim_Http_Response) {
                return $results;
            }
            $results = $mgr->deleteFTP($id);
            $app->getLog()->info("FTP {$id} deleted from website " . $website_id . ".");
            $response->status(204);
            $response->body(json_encode(array('success' => true, 'message' => 'FTP login has been deleted.')));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error deleting ftp {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Gets ftp credentials
     * @param int $id
     * @param int $website_id
     * @return array|Slim_Http_Response
     */
    public static function getFTP($id, $website_id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_FTP();
            $results = $mgr->getFTPDetails($website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "FTP login credentials not found")));
                return $response;
            }
            return $results;
        } catch (Exception $e) {
            $app->getLog()->error("Error pulling ftp login credentials {$id}. - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }
}
