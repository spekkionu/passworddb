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
     * Returns number of Websites
     * @url /api/website/count
     * @method GET
     */
    public static function countAction()
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $search = trim($app->request()->get('search'));
        if($search == ''){
          $search = null;
        }
        try {
            $mgr = new Model_Website();
            $count = $mgr->countWebsites($search);
            $response->body(json_encode(array("count"=>$count)));
            return $response;
        } catch (Exception $e) {
            $app->getLog()->error("Error counting websites. - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }

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
    $limit = (int) $app->request()->get('limit');
    if($limit <= 0){
      $limit = null;
    }
    $offset = (int) $app->request()->get('offset');
    if($offset < 0){
      $offset = 0;
    }
    $sort = trim($app->request()->get('sort'));
    $search = trim($app->request()->get('search'));
    if($search == ''){
      $search = null;
    }
    $dir = trim($app->request()->get('dir'));
    try {
      $mgr = new Model_Website();
      $websites = $mgr->getWebsiteList($search, $limit, $offset, $sort, $dir);
      $response->body(json_encode($websites));
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
            $website = self::getWebsite($id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $response->body(json_encode($website));
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
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $website = $mgr->addWebsite($data);
            $app->getLog()->info("Website {$website['id']} added.");
            $response->status(201);
            $response->body(json_encode($website));
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
            $website = self::getWebsite($id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
            }
            $data = ($app->request()->getMediaType() == 'application/json') ? json_decode($app->request()->getBody(), true) : $app->request()->post();
            $website = array_merge($website, array_intersect_key($data, $website));
            $website = $mgr->updateWebsite($id, $website);
            $app->getLog()->info("Website {$id} updated.");
            $response->status(200);
            $response->body(json_encode($website));
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
            $website = self::getWebsite($id);
            if ($website instanceof Slim_Http_Response) {
                return $website;
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

    /**
     * Checks if a website exists
     * @param int $website_id
     * @return array|Slim_Http_Response
     */
    public static function getWebsite($website_id)
    {
        $app = Slim::getInstance();
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        try {
            $mgr = new Model_Website();
            $website = $mgr->getWebsite($website_id);
            if (!$website) {
                $response->status(404);
                $response->body(json_encode(array('success' => false, 'message' => 'Website is not found.')));
                return $response;
            }
            return $website;
        } catch (Exception $e) {
            $app->getLog()->error("Error pulling website {$id}. - " . $e->getMessage());
            $response->status(500);
            $response->body(json_encode(array('success' => false, 'message' => $e->getMessage())));
            return $response;
        }
    }
}
