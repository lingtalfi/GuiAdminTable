<?php


namespace GuiAdminTable\Renderer;


use GuiAdminTable\Exception\GuiAdminTableException;


/**
 * The GuiAdminTableRenderer renders an admin table with (potentially)
 * a search row at the top (just below the header row containing the column names).
 * The search row requires an extra column to display the "search button".
 * By default, this column (searchButtonExtraColumn) is called _action,
 * and is appended to the existing content columns.
 *
 *
 *
 */
abstract class GuiAdminTableRenderer
{
    protected $useFilters;
    protected $useSort;
    protected $useCheckboxes;
    protected $headers;
    /**
     * by default, a header is visible unless otherwise specified
     */
    protected $headersVisibility;

    /**
     * @var array of colName => dir
     *          with dir one of:
     *              - true (asc)
     *              - false (desc)
     *              - null (by default, meaning neither asc nor desc)
     *
     */
    protected $headersOrder;
    protected $rows;

    /**
     * @var array of colName => transformers,
     *          with transformers: array of transformer
     *              with transformer: callable ( value )
     */
    protected $colTransformers;
    protected $htmlAttributes;
    protected $searchColumnGenerators;
    protected $searchValues;
    protected $searchButtonExtraColumnName;


    public function __construct()
    {
        $this->useFilters = true;
        $this->useSort = true;
        $this->useCheckboxes = true;
        $this->headers = [];
        $this->headersVisibility = [];
        $this->headersOrder = [];
        $this->rows = [];
        $this->colTransformers = [];
        $this->htmlAttributes = [
            "table" => [],
            "trSearch" => [],
            "trRow" => [],
        ];
        $this->searchColumnGenerators = [];
        $this->searchValues = [];
        $this->searchButtonExtraColumnName = "_action";
    }


    public static function create()
    {
        return new static();
    }

    //--------------------------------------------
    // CONFIG
    //--------------------------------------------
    public function setUseFilters($useFilters)
    {
        $this->useFilters = (bool)$useFilters;
        return $this;
    }

    public function setUseSort($useSort)
    {
        $this->useSort = (bool)$useSort;
        return $this;
    }

    public function setUseCheckboxes($useCheckboxes)
    {
        $this->useCheckboxes = (bool)$useCheckboxes;
        return $this;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function setHeadersVisibility($headersVisibility)
    {
        foreach ($headersVisibility as $col => $isVisible) {
            $this->headersVisibility[$col] = $isVisible;
        }
        return $this;
    }

    public function setHeadersOrder(array $headersOrder)
    {
        foreach ($headersOrder as $col => $order) {
            $this->headersOrder[$col] = $order;
        }
        return $this;
    }

    public function setRows(array $rows)
    {
        $this->rows = $rows;
        return $this;
    }

    public function addColTransformer($column, \Closure $colTransformer)
    {
        $this->colTransformers[$column][] = $colTransformer;
        return $this;
    }

    public function addHtmlAttributes($identifier, array $attributes)
    {
        if (array_key_exists($identifier, $this->htmlAttributes)) {
            $arr = $this->htmlAttributes[$identifier];
            foreach ($attributes as $k => $v) {
                if ('class' === $k && array_key_exists('class', $arr)) {
                    $arr['class'] .= ' ' . $v;
                } else {
                    $arr[$k] = $v;
                }
            }
            $this->htmlAttributes[$identifier] = $arr;
        } else {
            throw new GuiAdminTableException("Identifier not found in htmlAttributes: $identifier");
        }
    }

    public function addSearchColumnGenerator($column, \Closure $searchColumnGenerator)
    {
        $this->searchColumnGenerators[$column] = $searchColumnGenerator;
        return $this;
    }

    public function setSearchValues(array $searchValues)
    {
        $this->searchValues = $searchValues;
        return $this;
    }



    //--------------------------------------------
    // HELPERS
    //--------------------------------------------
    protected function getHtmlAttributes($identifier)
    {
        if (array_key_exists($identifier, $this->htmlAttributes)) {
            return $this->htmlAttributes[$identifier];
        }
        return [];
    }

    protected function headerIsVisible($col)
    {
        if (array_key_exists($col, $this->headersVisibility)) {
            if (true === (bool)$this->headersVisibility[$col]) {
                return true;
            }
            return false;
        }
        return true;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    abstract public function render();
}