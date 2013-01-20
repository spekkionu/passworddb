<?php

/**
 * Test class for Validate_Exception.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Validate
 */
class Validate_ExceptionTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test exception throwing
     * @expectedException Validate_Exception
     */
    public function testException()
    {
        $error = new Error_Stack();
        $element = 'name';
        $message = 'error message';
        $error->addError($element, $message);
        throw new Validate_Exception("Testing exception", 1234, null, $error);
    }

    /**
     * Test setting errors
     */
    public function testSetErrors()
    {
        try {
            $error = new Error_Stack();
            $element = 'name';
            $message = 'error message';
            $error->addError($element, $message);
            throw new Validate_Exception("Testing exception", 1234, null, $error);
        } catch (Validate_Exception $e) {
            $verror = $e->getErrors();
            $this->assertInstanceOf("Error_Stack", $verror);
        }
    }

    /**
     * Test retreiving errors
     */
    public function testGetErrors()
    {
        try {
            $error = new Error_Stack();
            $element = 'name';
            $message = 'error message';
            $error->addError($element, $message);
            throw new Validate_Exception("Testing exception", 1234, null, $error);
        } catch (Validate_Exception $e) {
            $verror = $e->getErrors();
            $this->assertEquals($error->getErrors(), $verror->getErrors());
        }
    }

    /**
     * Test getting error code
     */
    public function testGetCode()
    {
        try {
            throw new Validate_Exception("Testing exception", 1234);
        } catch (Validate_Exception $e) {
            $this->assertEquals(1234, $e->getCode());
        }
    }

    /**
     * test getting error message
     */
    public function testGetMessage()
    {
        try {
            throw new Validate_Exception("Testing exception", 1234);
        } catch (Validate_Exception $e) {
            $this->assertEquals("Testing exception", $e->getMessage());
        }
    }

}

