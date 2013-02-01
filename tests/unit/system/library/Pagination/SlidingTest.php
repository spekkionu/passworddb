<?php

/**
 * Test class for Validate_Exception.
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 * @subpackage Pagination
 */
class Pagination_SlidingTest extends PHPUnit_Framework_TestCase
{


    public function testPageCount()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 127,
          'delta' => 5,
          'page' => 1,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(13, $pagination->getLast());
    }

    public function testPageCountSmall()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 6,
          'delta' => 5,
          'page' => 1,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(1, $pagination->getLast());
    }

    public function testPageCountNoData()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 0,
          'delta' => 5,
          'page' => 1,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(1, $pagination->getLast());
    }

    public function testPageBeyondMax()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 127,
          'delta' => 5,
          'page' => 52,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(13, $pagination->getPage());
    }

    public function testRangeLeft()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 127,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $range = $pagination->getRange();
        $first = array_shift($range);
        $this->assertEquals(1, $first);
        $last = array_pop($range);
        $this->assertEquals(11, $last);
    }

    public function testRangeMiddle()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 127,
          'delta' => 5,
          'page' => 7,
        );
        $pagination = new Pagination_Sliding($params);
        $range = $pagination->getRange();
        $first = array_shift($range);
        $this->assertEquals(2, $first);
        $last = array_pop($range);
        $this->assertEquals(12, $last);
    }

    public function testRangeRight()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 127,
          'delta' => 5,
          'page' => 10,
        );
        $pagination = new Pagination_Sliding($params);
        $range = $pagination->getRange();
        $first = array_shift($range);
        $this->assertEquals(3, $first);
        $last = array_pop($range);
        $this->assertEquals(13, $last);
    }

    public function testRangeSmall()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 10,
        );
        $pagination = new Pagination_Sliding($params);
        $range = $pagination->getRange();
        $first = array_shift($range);
        $this->assertEquals(1, $first);
        $last = array_pop($range);
        $this->assertEquals(3, $last);
    }

    public function testIndex()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $data = $pagination->getData();
        $this->assertEquals(11, $data['firstItem']);
        $this->assertEquals(20, $data['lastItem']);
    }

    public function testIndexNoData()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 0,
          'delta' => 5,
          'page' => 3,
        );
        $pagination = new Pagination_Sliding($params);
        $data = $pagination->getData();
        $this->assertEquals(0, $data['firstItem']);
        $this->assertEquals(0, $data['lastItem']);
    }

    public function testIndexRight()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 3,
        );
        $pagination = new Pagination_Sliding($params);
        $data = $pagination->getData();
        $this->assertEquals(21, $data['firstItem']);
        $this->assertEquals(26, $data['lastItem']);
    }

    public function testNext()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 1,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(2, $pagination->getNext());
    }

    public function testNextRight()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 3,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(3, $pagination->getNext());
    }

    public function testPrevious()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(1, $pagination->getPrevious());
    }

    public function testPreviousLeft()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 1,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertEquals(1, $pagination->getPrevious());
    }

    public function testIsFirstTrue()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 1,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertTrue($pagination->isFirst());
    }

    public function testIsFirstFalse()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertFalse($pagination->isFirst());
    }

    public function testIsLastTrue()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 3,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertTrue($pagination->isLast());
    }

    public function testIsLastFalse()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertFalse($pagination->isLast());
    }


    public function testIsCurrentTrue()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertTrue($pagination->isCurrent(2));
    }

    public function testIsCurrentFalse()
    {
        $params = array(
          'itemsPerPage' => 10,
          'totalItems' => 26,
          'delta' => 5,
          'page' => 2,
        );
        $pagination = new Pagination_Sliding($params);
        $this->assertFalse($pagination->isCurrent(3));
    }
}

