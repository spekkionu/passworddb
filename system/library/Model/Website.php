<?php

/**
 * Website Model
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Model
 * @subpackage Website
 */
class Model_Website extends Model_Abstract
{

    public static $default = array(
      'name' => null,
      'domain' => null,
      'url' => null,
      'notes' => null,
    );

    /**
     * Returns array of websites
     *
     * @param boolean $with_details If true child data is also returned.
     * @return array
     */
    public function getWebsites($with_details = true)
    {
        $sth = self::getConnection()->query("SELECT * FROM `websites` ORDER BY `name` ASC");
        if (!$with_details) {
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        $websites = array();
        $admin_sth = self::getConnection()->prepare("SELECT * FROM `admin_logins` WHERE `website_id` = ?");
        $cp_sth = self::getConnection()->prepare("SELECT * FROM `control_panels` WHERE `website_id` = ?");
        $db_sth = self::getConnection()->prepare("SELECT * FROM `database_data` WHERE `website_id` = ?");
        $ftp_sth = self::getConnection()->prepare("SELECT * FROM `ftp_data` WHERE `website_id` = ?");
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $admin_sth->execute(array($row['id']));
            $row['admin'] = $admin_sth->fetchAll(PDO::FETCH_ASSOC);
            $cp_sth->execute(array($row['id']));
            $row['controlpanel'] = $cp_sth->fetchAll(PDO::FETCH_ASSOC);
            $db_sth->execute(array($row['id']));
            $row['database'] = $db_sth->fetchAll(PDO::FETCH_ASSOC);
            $ftp_sth->execute(array($row['id']));
            $row['ftp'] = $ftp_sth->fetchAll(PDO::FETCH_ASSOC);
            $websites[] = $row;
        }
        return $websites;
    }

    /**
     * Returns total number of websites
     *
     * @param string $search Search filter
     * @return int
     */
    public function countWebsites($search = null)
    {
        if (empty($search)) {
            $sth = self::getConnection()->query("SELECT COUNT(*) FROM `websites`");
        } else {
            $sth = self::getConnection()->prepare("SELECT COUNT(*) FROM `websites` WHERE (`name` LIKE :search) OR (`domain` LIKE :search) OR (`url` LIKE :search)");
            $searchstring = "%".str_replace("%", "\\%", $search)."%";
            $sth->bindValue(":search", $searchstring);
            $sth->execute();
        }
        $total = $sth->fetchColumn();
        $sth->closeCursor();
        return $total;
    }

    /**
     * Returns array of websites
     *
     * @param string $search Search filter
     * @param int $offset The number of records to skip
     * @param int $limit The maximum number of websites to return
     * @param string $sort id|name|domain|url The column to sort by
     * @param string $dir ASC|DESC The direction to sort
     * @return array
     */
    public function getWebsiteList($search = null, $limit = 25, $offset = 0, $sort = 'name', $dir = 'ASC')
    {
        $sort = mb_strtolower($sort);
        if(!in_array($sort, array('id','name', 'domain', 'url'))){
          $sort = 'name';
        }
        $dir = mb_strtoupper($dir);
        if(!in_array($dir, array('ASC','DESC'))){
          $dir = 'ASC';
        }
        $limit = (int)$limit;
        $offset = (int)$offset;
        $query = "SELECT * FROM `websites`";
        if (is_null($search) || $search == '') {
          $search = null;
        } else {
          $search = "%".str_replace("%", "\\%", $search)."%";
          $query .= " WHERE (`name` LIKE :search) OR (`domain` LIKE :search) OR (`url` LIKE :search)";
        }

        $query .= " ORDER BY `{$sort}` {$dir}";
        if($limit > 0){
          $query .= " LIMIT :offset, :limit";
        }
        $sth = self::getConnection()->prepare($query);
        if($limit > 0){
            $sth->bindValue(":offset", $offset, PDO::PARAM_INT);
            $sth->bindValue(":limit", $limit, PDO::PARAM_INT);
        }
        if (!is_null($search)) {
          $sth->bindValue(":search", $search);
        }
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * Returns data for a single website
     * @param int $id
     * @param boolean $with_details If true also return details
     * @return array
     */
    public function getWebsite($id, $with_details = true)
    {
        $sth = self::getConnection()->prepare("SELECT * FROM `websites` WHERE `id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        $website = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        if ($website && $with_details) {
            $sth = self::getConnection()->prepare("SELECT * FROM `admin_logins` WHERE `website_id` = :wid");
            $sth->bindValue(":wid", $id, PDO::PARAM_INT);
            $sth->execute();
            $website['admin'] = $sth->fetchAll(PDO::FETCH_ASSOC);
            $sth = self::getConnection()->prepare("SELECT * FROM `control_panels` WHERE `website_id` = :wid");
            $sth->bindValue(":wid", $id, PDO::PARAM_INT);
            $sth->execute();
            $website['controlpanel'] = $sth->fetchAll(PDO::FETCH_ASSOC);
            $sth = self::getConnection()->prepare("SELECT * FROM `database_data` WHERE `website_id` = :wid");
            $sth->bindValue(":wid", $id, PDO::PARAM_INT);
            $sth->execute();
            $website['database'] = $sth->fetchAll(PDO::FETCH_ASSOC);
            $sth = self::getConnection()->prepare("SELECT * FROM `ftp_data` WHERE `website_id` = :wid");
            $sth->bindValue(":wid", $id, PDO::PARAM_INT);
            $sth->execute();
            $website['ftp'] = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return $website;
    }

    /**
     * Returns true if website exists
     * @param int $website_id
     * @return boolean
     */
    public function websiteExists($website_id)
    {
        $sth = self::getConnection()->prepare("SELECT `id` FROM `websites` WHERE `id` = :id");
        $sth->bindValue(":id", $website_id, PDO::PARAM_INT);
        $sth->execute();
        $id = $sth->fetchColumn();
        $sth->closeCursor();
        return ($id > 0);
    }

    /**
     * Adds a new website
     *
     * @param array $data
     * @return array|Error_Stack Added Website data
     * @throws Validate_Exception
     */
    public function addWebsite(array $data)
    {
        $data = $this->filterData($data, array(
          'name', 'domain', 'url', 'notes'
          ));
        $errors = $this->validate($data);
        if ($errors->hasErrors()) {
            throw new Validate_Exception("Data is invalid", 0, null, $errors);
        }
        $sth = self::getConnection()->prepare("INSERT INTO `websites` (`name`,`domain`,`url`,`notes`) VALUES(:name,:domain,:url,:notes)");
        $sth->execute($data);
        $id = self::getConnection()->lastInsertId();
        return $this->getWebsite($id);
    }

    /**
     * Updates a website
     * @param int $id
     * @param array $data
     * @return array Update website data
     * @throws Validate_Exception
     */
    public function updateWebsite($id, array $data)
    {
        $data = $this->filterData($data, array(
          'name', 'domain', 'url', 'notes'
          ));
        $errors = $this->validate($data, $id);
        if ($errors->hasErrors()) {
            throw new Validate_Exception("Data is invalid", 0, null, $errors);
        }
        $data['id'] = $id;
        $sth = self::getConnection()->prepare("UPDATE `websites` SET `name` = :name,`domain` = :domain,`url` = :url,`notes` = :notes WHERE `id` = :id");
        $sth->execute($data);
        return $this->getWebsite($id);
    }

    /**
     * Deletes a website
     * @param int $id
     * @return void
     */
    public function deleteWebsite($id)
    {
        // Delete admin logins
        $sth = self::getConnection()->prepare("DELETE FROM `admin_logins` WHERE `website_id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        // Delete control panel logins
        $sth = self::getConnection()->prepare("DELETE FROM `control_panels` WHERE `website_id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        // Delete database credentials
        $sth = self::getConnection()->prepare("DELETE FROM `database_data` WHERE `website_id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        // Delete ftp info
        $sth = self::getConnection()->prepare("DELETE FROM `ftp_data` WHERE `website_id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        // Delete website
        $sth = self::getConnection()->prepare("DELETE FROM `websites` WHERE `id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * Validates Data
     * Returns an Error_Stack instance with any error messages
     * If all data is valid the Error_Stack will have no errors (Error_Stack::hasErrors() will return false)
     *
     * @param array Data
     * @param int $id Exception for unique values
     * @return Error_Stack
     */
    public function validate(array $data, $id = null)
    {
        $errors = new Error_Stack();
        if (empty($data['name'])) {
            $errors->addError('name', 'Website name is required.', 'required');
        } elseif (mb_strlen($data['name']) > 100) {
            $errors->addError('name', 'Website name must not be more than 100 characters.', 'maxlength');
        } else {
            // Make sure name is unique
            if ($id) {
                $sth = self::getConnection()->prepare("SELECT COUNT(*) FROM `websites` WHERE `name` LIKE :name AND `id` != :id");
                $sth->bindValue(":id", $id, PDO::PARAM_INT);
            } else {
                $sth = self::getConnection()->prepare("SELECT COUNT(*) FROM `websites` WHERE `name` LIKE :name");
            }
            $sth->bindValue(":name", $data['name']);
            $sth->execute();
            $count = $sth->fetchColumn();
            $sth->closeCursor();
            if ($count > 0) {
                $errors->addError('name', "Website with name {$data['name']} already exists.", 'unique');
            }
        }
        if (mb_strlen($data['domain']) > 100) {
            $errors->addError('domain', 'Website domain must not be more than 100 characters.', 'maxlength');
        }
        if (mb_strlen($data['url']) > 255) {
            $errors->addError('url', 'Website URL must not be more than 255 characters.', 'maxlength');
        }
        return $errors;
    }
}
