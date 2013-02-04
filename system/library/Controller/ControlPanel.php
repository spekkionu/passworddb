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
     * Adds a new control panel login
     * @url /api/controlpanel/:website_id
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
        $results = Model_ControlPanel::$default;
        if ($app->request()->isPost()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('controlpanel'), $results));
                $mgr = new Model_ControlPanel();
                $results = $mgr->addControlPanelLogin($website_id, $results);
                $app->flash('success', "Control panel {$results['id']} added for website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('controlpanel/add.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error adding control panel.'), 500);
                return $response;
            }
        }
        $app->render('controlpanel/add.twig', array('website' => $website, 'results' => $results));
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
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $mgr = new Model_ControlPanel();
        $results = $mgr->getControlPanelDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'Control panel credentials not found.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isPut()) {
            try {
                $results = array_merge($results, array_intersect_key($app->request()->post('controlpanel'), $results));
                $results = $mgr->updateControlPanelLogin($id, $results, $website_id);
                $app->flash('success', "Control panel credentials {$id} have been updated for website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('controlpanel/edit.twig', array('website' => $website, 'results' => $results, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => 'Error updating control panel.'), 500);
                return $response;
            }
        }
        $app->render('controlpanel/edit.twig', array('website' => $website, 'results' => $results));
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
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($website_id);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        $mgr = new Model_ControlPanel();
        $results = $mgr->getControlPanelDetails($id, $website_id);
        if (!$results) {
            $app->render('error/not-found.twig', array('message' => 'Control panel credentials not found.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isDelete()) {
            try {
                $results = $mgr->deleteControlPanelLogin($id);
                $app->flash('info', "Control panel {$id} deleted from website {$website['name']}.");
                $response->redirect($app->config('base_url')."website/{$website_id}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('controlpanel/delete.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception' => $e, 'message' => "Error deleting control panel {$id} for website {$website['name']}."), 500);
                return $response;
            }
        }
        $app->render('controlpanel/delete.twig', array('website' => $website, 'results' => $results));
    }
}
