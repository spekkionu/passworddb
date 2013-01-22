<?php

/**
 * Control Panel api methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage ControlPanel
 */
class Controller_ControlPanel
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
            $mgr = new Model_Website();
            $website = $mgr->websiteExists($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
                return $response;
            }
            $mgr = new Model_ControlPanel();
            $results = $mgr->getControlPanelLogins($website_id);
            $response->body(json_encode(array('success' => true, 'records' => $results)));
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
            $mgr = new Model_Website();
            $website = $mgr->websiteExists($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
                return $response;
            }
            $mgr = new Model_ControlPanel();
            $results = $mgr->getControlPanelDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Control panel login credentials not found")));
                return $response;
            }
            return $response->body(json_encode(array('success' => true, 'record' => $results)));
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
            $mgr = new Model_Website();
            $website = $mgr->websiteExists($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found")));
                return $response;
            }
            $mgr = new Model_ControlPanel();
            $results = $mgr->addControlPanelLogin($website_id, $app->request()->post());
            $app->getLog()->info("Control panel {$results['id']} added for website " . $website_id . ".");
            $response->status(201);
            $response->body(json_encode(array('success' => true, 'message' => 'Control panel Login has been added.', 'record' => $results)));
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
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
                return $response;
            }
            $mgr = new Model_ControlPanel();
            $results = $mgr->getControlPanelDetails($id, $website_id);
            $app->getLog()->info("Control panel {$id} updated for website " . $website_id . ".");
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Control panel login credentials not found")));
                return $response;
            }
            $results = array_merge($results, array_intersect_key($app->request()->post(), $results));
            $results = $mgr->updateControlPanelLogin($id, $results, $website_id);
            $response->status(200);
            $response->body(json_encode(array('success' => true, 'message' => 'Control panel login has been updated.', 'record' => $results)));
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
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Website not found.")));
                return $response;
            }
            $mgr = new Model_ControlPanel();
            $results = $mgr->getControlPanelDetails($id, $website_id);
            if (!$results) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => "Control panel login credentials not found")));
                return $response;
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
}
