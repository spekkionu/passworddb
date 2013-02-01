<?php

/**
 * Website API methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Website
 */
class Api_Controller_Website
{

    /**
     * Returns list of Websites
     * @url /api/website
     * @method GET
     */
    public static function listAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $websites = $mgr->getWebsites();
            $response->body(json_encode(array('success' => true, 'records' => $websites)));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error listing websites. - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Returns details for a website
     * @url /api/website/:id
     * @method GET
     */
    public static function detailsAction($id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => 'Requested URI is not found.')));
                return $response;
            }
            $response->body(json_encode(array('success' => true, 'record' => $website)));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error showing website {$id}. - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Adds a new website
     * @url /api/website
     * @method POST
     */
    public static function addAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->addWebsite($app->request()->post());
            $app->getLog()->info("Website {$website['id']} added.");
            $response->status(201);
            $response->body(json_encode(array('success' => true, 'message' => 'Website has been added.', 'record' => $website)));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error adding website. - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Updates a website
     * @url /api/website/:id
     * @method PUT
     */
    public static function updateAction($id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
                return $response;
            }
            $website = array_merge($website, array_intersect_key($app->request()->post(), $website));
            $website = $mgr->updateWebsite($id, $website);
            $app->getLog()->info("Website {$id} updated.");
            $response->status(200);
            $response->body(json_encode(array('success' => true, 'message' => 'Website has been updated.', 'record' => $website)));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error updating website {$id}. - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Deletes a website
     * @url /api/website/:id
     * @method DELETE
     */
    public static function deleteAction($id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
                return $response;
            }
            $website = $mgr->deleteWebsite($id);
            $app->getLog()->info("Website {$id} deleted.");
            $response->status(204);
            $response->body(json_encode(array('success' => true, 'message' => 'Website has been deleted.')));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error deleting website {$id}. - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }
}
