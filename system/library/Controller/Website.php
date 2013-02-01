<?php

/**
 * Website API methods
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Controller
 * @subpackage Website
 */
class Controller_Website
{

    /**
     * Returns list of Websites
     * @url /:page
     * @method GET
     */
    public static function listAction($page = 1)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $request = $app->request();
        $limit = 25;
        $search = trim($request->params('s'));
        try {
            $mgr = new Model_Website();
            $total = $mgr->countWebsites($search);
            if ($total > 0) {
                $websites = $mgr->getWebsiteList($search, $page, $limit);
            } else {
                $websites = array();
            }
            $pagination = new Pagination_Sliding(array(
                'totalItems' => $total,
                'page' => $page,
                'itemsPerPage' => $limit
              ));
            $pagination = $pagination->getData();
            $pagination['link'] = $app->urlFor('home') . "%d";
            if (!empty($search)) {
                $pagination['link'] .= "?s=" . str_replace("%", "%%", $search);
            }
            $app->render('website/list.twig', array('websites' => $websites, 'pagination' => $pagination, 'total' => $total, 'page' => $page, 'search' => $search));
            return $response;
        } catch (Exception $e) {
            $app->render('error/error.twig', array('exception' => $e, 'message' => "There was an error listing websites."), 500);
            return $response;
        }
    }

    /**
     * Returns details for a website
     * @url /website/:id
     * @method GET
     */
    public static function detailsAction($id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($id);
            if (!$website) {
                $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
                return $response;
            }
            $app->render('website/details.twig', array('website' => $website));
            return $response;
        } catch (Exception $e) {
            $app->render('error/error.twig', array('exception' => $e, 'message' => "Error showing website {$id}"), 500);
            return $response;
        }
    }

    /**
     * Adds a new website
     * @url /website/add
     * @method GET,POST
     */
    public static function addAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $website = Model_Website::$default;
        if ($app->request()->isPost()) {
            try {
                $website = array_merge($website, array_intersect_key($app->request()->post('website'), $website));
                $mgr = new Model_Website();
                $website = $mgr->addWebsite($website);
                $app->flash('success', "Website {$website['name']} added.");
                $response->redirect("/website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('website/add.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->render('error/error.twig', array('exception'=>$e, 'message' => 'Error adding website.'), 500);
                return $response;
            }
        }
        $app->render('website/add.twig', array('website' => $website));
    }

    /**
     * Updates a website
     * @url /api/website/:id/edit
     * @method GET,POST,PUT
     */
    public static function updateAction($id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($id);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isPut()) {
            try {
                $website = array_merge($website, array_intersect_key($app->request()->post('website'), $website));
                $website = $mgr->updateWebsite($id, $website);
                $app->flash('success', "Website {$website['name']} updated.");
                $response->redirect("/website/{$website['id']}");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('website/edit.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->flash('error', 'Error updating website.');
                $response->redirect("/website/{$id}/edit");
                return $response;
            }
        }
        $app->render('website/edit.twig', array('website' => $website));
    }

    /**
     * Deletes a website
     * @url /api/website/:id
     * @method GET,POST,DELETE
     */
    public static function deleteAction($id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $mgr = new Model_Website();
        $website = $mgr->getWebsite($id);
        if (!$website) {
            $app->render('error/not-found.twig', array('message' => 'The website you requested does not exist.'), 404);
            return $response;
        }
        if ($app->request()->isPost() || $app->request()->isDelete()) {
            try {
                $website = $mgr->deleteWebsite($id);
                $app->flash('info', "Website {$website['name']} deleted.");
                $response->redirect("/");
                return $response;
            } catch (Validate_Exception $e) {
                $app->render('website/delete.twig', array('website' => $website, 'errors' => $e->getErrors()));
                return $response;
            } catch (Exception $e) {
                $app->flash('error', 'Error deleting website.');
                $response->redirect("/website/{$id}/delete");
                return $response;
            }
        }
        $app->render('website/delete.twig', array('website' => $website));
    }
}
