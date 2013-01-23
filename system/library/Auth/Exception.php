<?php
/**
 * Authentication Exception
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Auth
 * @subpackage Exception
 */
class Auth_Exception extends Exception {

    /**
     * User was not found
     */
    const NOT_FOUND = 1;

    /**
     * User's credentials were invalid
     */
    const BAD_CREDENTIALS = 2;

    /**
     * User account was not active
     */
    const NOT_ACTIVE = 3;

}