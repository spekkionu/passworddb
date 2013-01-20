<?php

/**
 * Admin login model
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Model
 * @subpackage Admin
 */
class Model_Admin extends Model_Abstract
{

    /**
     * Returns Admin Logins for a website
     *
     * @param int $website_id
     * @return array
     * @throws Exception if website doesn't exist
     */
    public function getAdminLogins($website_id)
    {
        $sth = self::getConnection()->prepare("SELECT * FROM `admin_logins` WHERE `website_id` = :id");
        $sth->bindValue(":id", $website_id, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns admin login details
     * @param int $id
     * @param int $website_id
     * @return array
     */
    public function getAdminLoginDetails($id, $website_id = null)
    {
        if ($website_id) {
            $sth = self::getConnection()->prepare("SELECT * FROM `admin_logins` WHERE `id` = :id AND `website_id` = :website_id");
            $sth->bindValue(":website_id", $website_id, PDO::PARAM_INT);
        } else {
            $sth = self::getConnection()->prepare("SELECT * FROM `admin_logins` WHERE `id` = :id");
        }
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        return $result;
    }

    /**
     * Adds admin login credentials
     * @param int $website_id
     * @param array $data
     * @return array Added record
     */
    public function addAdminLogin($website_id, array $data)
    {
        $data = $this->filterData($data, array(
          'username', 'password', 'url', 'notes'
          ));
        $data['website_id'] = $website_id;
        $errors = $this->validate($data);
        if ($errors->hasErrors()) {
            throw new Validate_Exception("Data is invalid", 0, null, $errors);
        }
        $sth = self::getConnection()->prepare("INSERT INTO `admin_logins` (`username`,`password`,`url`,`notes`, `website_id`) VALUES(:username,:password,:url,:notes,:website_id)");
        $sth->execute($data);
        $id = self::getConnection()->lastInsertId();
        return $this->getAdminLoginDetails($id, $website_id);
    }

    /**
     * Updates Admin login credentials
     * @param int $id
     * @param array $data
     * @return array Updated record
     */
    public function updateAdminLogin($id, array $data, $website_id)
    {
        $data = $this->filterData($data, array(
          'username', 'password', 'url', 'notes'
          ));
        $data['website_id'] = $website_id;
        $errors = $this->validate($data, $id);
        unset($data['website_id']);
        if ($errors->hasErrors()) {
            throw new Validate_Exception("Data is invalid", 0, null, $errors);
        }
        $data['id'] = $id;
        $sth = self::getConnection()->prepare("UPDATE `admin_logins` SET `username` = :username,`password` = :password, `url` = :url, `notes` = :notes WHERE `id` = :id");
        $sth->execute($data);
        return $this->getAdminLoginDetails($id);
    }

    /**
     * Deletes admin login
     * @param int $id
     * @return void
     */
    public function deleteAdminLogin($id)
    {
        // Delete admin logins
        $sth = self::getConnection()->prepare("DELETE FROM `admin_logins` WHERE `id` = :id");
        $sth->bindValue(":id", $id, PDO::PARAM_INT);
        $sth->execute();
    }

    /**
     * Validates Data
     * Retuns an Error_Stack instance with any error messages
     * If all data is valid the Error_Stack will have no errors (Errorstack::hasErrors() will return false)
     *
     * @param array Data
     * @param int $id Exception for unique values
     * @return Error_Stack
     */
    public function validate(array $data, $id = null)
    {
        $errors = new Error_Stack();
        if (empty($data['website_id'])) {
            $errors->addError('website_id', 'Website is required.');
        } else {
            // Make sure website exists
            $sth = self::getConnection()->prepare("SELECT COUNT(*) FROM `websites` WHERE `id` = :id");
            $sth->bindValue(":id", $data['website_id'], PDO::PARAM_INT);
            $sth->execute();
            $count = $sth->fetchColumn();
            $sth->closeCursor();
            if ($count == 0) {
                $errors->addError('website_id', "Website does not exist.");
            }
        }
        if (mb_strlen($data['username']) > 100) {
            $errors->addError('username', 'Username must not be more than 100 characters.');
        }
        if (mb_strlen($data['password']) > 100) {
            $errors->addError('username', 'Password must not be more than 100 characters.');
        }
        if (mb_strlen($data['url']) > 255) {
            $errors->addError('name', 'URL must not be more than 255 characters.');
        }
        return $errors;
    }
}
