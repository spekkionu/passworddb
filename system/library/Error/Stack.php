<?php

/**
 * Error class
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Error
 */
class Error_Stack
{

    /**
     * Error messages
     * @var array $errors
     */
    protected $errors = array();

    /**
     * Adds an error message
     *
     * @param string $name The element the error is for
     * @param string $message The error message
     * @param string $label An optional key for the error. If the element already has an error with this label the existing one will be overwritten.
     * @return Error_Stack
     */
    public function addError($name, $message, $label = null)
    {
        if (!array_key_exists($name, $this->errors)) {
            $this->errors[$name] = array();
        }
        if ($label) {
            $this->errors[$name][$label] = $message;
        } else {
            $this->errors[$name][] = $message;
        }
        return $this;
    }

    /**
     * Returns all errors
     *
     * @param string $name If provided only return errors for this element
     * @return array
     */
    public function getErrors($name = null)
    {
        return ($name) ? $this->errors[$name] : $this->errors;
    }

    /**
     * Deletes errors
     *
     * @param string $name If provided only delete errors for this element
     * @return Error_Stack
     */
    public function clearErrors($name = null)
    {
        if ($name) {
            unset($this->errors[$name]);
        } else {
            $this->errors = array();
        }

        return $this;
    }

    /**
     * Returns true if there are errors
     *
     * @param string $name If provided only checks if this element has errors
     * @return boolean
     */
    public function hasErrors($name = null)
    {
        return ($name) ? (isset($this->errors[$name]) && count($this->errors[$name]) > 0) : (count($this->errors) > 0);
    }

    /**
     * Returns errors as JSON string
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->errors);
    }
}
