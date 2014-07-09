<?php

/**
 * Control Panel api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage ControlPanel
 */
class Api_Controller_ControlPanel
{

    /**
     * Returns list of control panel logins
     * @url /api/controlpanel/:website_id
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
            $mgr = new Model_ControlPanel();
            $results = $mgr->getControlPanelLogins($website_id);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error listing control panels for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Returns details for an control panel login
     * @url /api/controlpanel/:website_id/:id
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
            $results = self::getControlPanel($id, $website_id);
            if ($results instanceof Slim_Http_Response) {
                return $results;
            }
            return $response->body(json_encode($results));
        } catch (Exception $e) {
            $app->getLog()->error("Error showing control panel {$id} for website " . $website_id . ". - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

    /**
     * Adds a new control panel login
     * @url /api/controlpanel/:website_id
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
            $mgr = new Model_ControlPanel();
            $results = $mgr->addControlPanelLogin($website_id, $data);
            $app->getLog()->info("Control panel {$results['id']} added for website " . $website_id . ".");
            $response->status(201);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error adding control panel for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Updates an control panel login
     * @url /api/controlpanel/:website_id/:id
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
            $mgr = new Model_ControlPanel();
            $results = self::getControlPanel($id, $website_id);
            if ($results instanceof Slim_Http_Response) {
                return $results;
            }
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $results = array_merge($results, array_intersect_key($data, $results));
            $results = $mgr->updateControlPanelLogin($id, $results, $website_id);
            $app->getLog()->info("Control panel {$id} updated for website " . $website_id . ".");
            $response->status(200);
            $response->body(json_encode($results));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error updating control panel {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Deletes an control panel login
     * @url /api/controlpanel/:website_id/:id
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
            $mgr = new Model_ControlPanel();
            $results = self::getControlPanel($id, $website_id);
            if ($results instanceof Slim_Http_Response) {
                return $results;
            }
            $results = $mgr->deleteControlPanelLogin($id);
            $app->getLog()->info("Control panel {$id} deleted from website " . $website_id . ".");
            $response->status(204);
            $response->body(json_encode(array('success' => true, 'message' => 'Control panel login has been deleted.')));
            return $response;
        } catch (Exception $e) {
            if ($e instanceof Validate_Exception) {
                $response->status(400);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage(), 'errors' => $e->getErrors()->getErrors())));
                return $response;
            } else {
                $app->getLog()->error("Error deleting control panel {$id} for website " . $website_id . ". - " . $e->getMessage());
                $response->status(500);
                $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
                return $response;
            }
        }
    }

    /**
     * Gets control panel login
     * @param int $id
     * @param int $website_id
     * @return array|Slim_Http_Response
     */
    public static function getControlPanel($id, $website_id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_ControlPanel();
            $results = $mgr->getControlPanelDetails($website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Control panel login credentials not found")));
                return $response;
            }
            return $results;
        } catch (Exception $e) {
            $app->getLog()->error("Error pulling control panel login {$id}. - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }
}
