<?php

/**
 * Login model
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Model
 * @subpackage Login
 */
class Model_Login extends Model_Abstract
{

    /**
     * Returns Admin Logins for a website
     *
     * @param string $username
     * @return array Returns user with matching login credentials
     * @throws Auth_Exception if user doesn't exist or credentials don't match
     */
    public function checkLogin($username, $password)
    {
        $sth = self::getConnection()->prepare("SELECT * FROM `logins` WHERE `username` = :username");
        $sth->bindValue(":username", $username);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        if(!$row){
            throw new Auth_Exception("Invalid login credentials", Auth_Exception::NOT_FOUND);
        }
        if(!self::checkHash($password, $row['password'])){
            throw new Auth_Exception("Invalid login credentials", Auth_Exception::BAD_CREDENTIALS);
        }
        return $row;
    }


    /**
     * Hashes string
     *
     * @TODO Replace with actual secure hashing
     *
     * @param string $string String to be hashed
     * @return string The hashed string
     */
    public static function hash($string)
    {
        return $string;
    }

    /**
     * Checks if string matches hashed string
     *
     * @param $string The string to check
     * @param string $check The hash to check against
     * @return boolean
     */
    public static function checkHash($string, $check)
    {
        return (self::hash($string) == $check);
    }


}
