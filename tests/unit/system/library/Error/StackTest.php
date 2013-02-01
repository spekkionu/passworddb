<?php

/**
 * Test class for Error_Stack.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Error
 */
class Error_StackTest extends PHPUnit_Framework_TestCase
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * Error_Stack::addError
     */
    public function testAddError()
    {
        $error = new Error_Stack();
        $element = 'name';
        $message = 'error message';
        $error->addError($element, $message);
        $errors = $error->getErrors();
        $this->assertEquals(array($element => array($message)), $errors);
    }

    /**
     * Error_Stack::hasErrors
     */
    public function testHasErrors()
    {
        $error = new Error_Stack();
        $element = 'name';
        $message = 'error message';
        $label = 'label';
        $error->addError($element, $message, $label);
        $this->assertTrue($error->hasErrors());
    }

    /**
     * Error_Stack::getErrors
     */
    public function testGetErrors()
    {
        $error = new Error_Stack();
        $element = 'name';
        $message = 'error message';
        $message2 = 'error message 2';
        $error->addError($element, $message);
        $error->addError($element, $message2);
        $errors = $error->getErrors();
        $this->assertEquals(array($element => array($message, $message2)), $errors);
    }

    /**
     * Error_Stack::clearErrors
     */
    public function testClearErrors()
    {
        $error = new Error_Stack();
        $element = 'name';
        $message = 'error message';
        $error->addError($element, $message);
        $errors = $error->getErrors();
        $this->assertEquals(array($element => array($message)), $errors);
        $error->clearErrors();
        $this->assertFalse($error->hasErrors());
    }

    /**
     * Error_Stack::clearErrors
     */
    public function testClearErrorByName()
    {
        $error = new Error_Stack();
        $error->addError('one', 'message one');
        $error->addError('two', 'message two');
        $errors = $error->getErrors();
        $this->assertArrayHasKey('one', $errors);
        $this->assertArrayHasKey('two', $errors);
        $error->clearErrors('two');
        $errors = $error->getErrors();
        $this->assertArrayHasKey('one', $errors);
        $this->assertArrayNotHasKey('two', $errors);
    }

    /**
     * Error_Stack::toJson
     */
    public function testToJson()
    {
        $error = new Error_Stack();
        $element = 'name';
        $message = 'error message';
        $error->addError($element, $message);
        $errors = $error->getErrors();
        $json = $error->toJson();
        $this->assertEquals(json_decode($json, true), $errors);
    }

}
