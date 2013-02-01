<?php

/**
 * Sliding Pagination Calculator
 *
 * @license MIT http://opensource.org/licenses/MIT
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @package Pagination
 */
class Pagination_Sliding
{

    /**
     * The number of items to show per page.
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * The total number of items
     * @var int
     */
    protected $totalItems = 0;

    /**
     * The maximum number of page numbers to show on each side of the current page.
     * @var int
     */
    protected $delta = 5;

    /**
     * The current page
     * @var int
     */
    protected $page = 1;

    /**
     * The total number of pages
     * @var int
     */
    protected $totalPages = 0;

    /**
     * Calculated Pagination Data
     * @var array
     */
    protected $data = null;

    /**
     * Class constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $params = array_merge(array(
          'itemsPerPage' => 10,
          'totalItems' => 0,
          'delta' => 5,
          'page' => 1,
          ), $params);
        if (!is_numeric($params['itemsPerPage']) || $params['itemsPerPage'] < 1) {
            throw new Exception("Invalid items per page.");
        }
        if (!is_numeric($params['page']) || $params['page'] < 1) {
            $page = 1;
        }
        if (!is_numeric($params['delta']) || $params['delta'] < 1) {
            throw new Exception("Invalid pagination delta.");
        }
        if (!is_numeric($params['totalItems']) || $params['totalItems'] < 0) {
            $params['totalItems'] = 0;
        }
        $this->itemsPerPage = $params['itemsPerPage'];
        $this->page = $params['page'];
        $this->delta = $params['delta'];
        $this->totalItems = $params['totalItems'];
        $this->build();
    }

    /**
     * Returns pagination data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Calculates pagination data
     */
    protected function build()
    {
        $this->totalPages = max(1, ceil($this->totalItems / $this->itemsPerPage));
        if($this->page > $this->totalPages){
            $this->page = $this->totalPages;
        }
        if ($this->page == $this->totalPages) {
            $firstIndex = max(1, $this->totalPages - ($this->delta * 2));
        } elseif ($this->page + $this->delta < $this->totalPages) {
            $firstIndex = max(1, $this->page - $this->delta);
        } else {
            $firstIndex = max(1, $this->totalPages - ($this->delta * 2));
        }
        if ($this->page - $this->delta > 1) {
            $lastIndex = min($this->totalPages, $this->page + $this->delta);
        } else {
            $lastIndex = min($this->totalPages, $this->delta * 2 + 1);
        }
        $this->data = array(
          'page' => $this->page,
          'totalItems' => $this->totalItems,
          'totalPages' => $this->totalPages,
          'itemsPerPage' => $this->itemsPerPage,
          'first' => 1,
          'last' => $this->totalPages,
          'previous' => max(1, $this->page - 1),
          'next' => min($this->totalPages, $this->page + 1),
          'firstItem' => ($this->totalItems > 0) ? ($this->page - 1) * $this->itemsPerPage + 1 : 0,
          'lastItem' => ($this->totalItems > 0) ? min($this->totalItems, $this->page * $this->itemsPerPage) : 0,
          'range' => range($firstIndex, $lastIndex),
        );
    }

    /**
     * Returns true if the current page is the first page
     *
     * @return boolean
     */
    public function isFirst()
    {
        return ($this->page == 1);
    }

    /**
     * Returns true if the current page is the last page
     *
     * @return boolean
     */
    public function isLast()
    {
        return ($this->page == $this->totalPages);
    }

    /**
     * Returns true if provided page is the current page
     *
     * @param int $page
     * @return boolean
     */
    public function isCurrent($page)
    {
        return ($page == $this->page);
    }

    /**
     * Returns the first page. Always 1
     *
     * @return int
     */
    public function getFirst()
    {
        return 1;
    }

    /**
     * Returns the last page
     *
     * @return int
     */
    public function getLast()
    {
        return $this->totalPages;
    }

    /**
     * Returns the previous page
     *
     * Will be the first page if already on the first page
     *
     * @return int
     */
    public function getPrevious()
    {
        return $this->data['previous'];
    }

    /**
     * Returns the next page
     *
     * Will be the last page if already on the last page
     *
     * @return int
     */
    public function getNext()
    {
        return $this->data['next'];
    }

    /**
     * Returns the displayed page range
     *
     * @return array Array of page numbers to display including the current page
     */
    public function getRange()
    {
        return $this->data['range'];
    }

    /**
     * Returns the current page
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
}
