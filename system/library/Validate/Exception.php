<?php

/**
 * Validation exception class
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Validation
 */
class Validate_Exception extends Exception
{

    /**
     * Error stack
     * @var Error_Stack $errors
     */
    protected $errors;

    /**
     * Class constructor
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Exception $previous The previous exception
     * @param Error_Stack $errorstack An Error_Stack instance with error messages
     */
    public function __construct($message = "", $code = 0, Exception $previous = null, Error_Stack $errorstack = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setErrors($errorstack);
    }

    /**
     * Sets errorstack
     * @param Error_Stack $errors
     * @return Validate_Exception
     */
    public function setErrors(Error_Stack $errors = null)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Returns Error Messages
     * @return Error_Stack
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
