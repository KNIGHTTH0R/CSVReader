<?php
/**
 * Class CSVFile
 *
 * @version 1.0
 * @author Alexandre Padovan
 * @license GNU GENERAL PUBLIC LICENSE
 */
class CSVField
{

    /**
     * @var CSVRow CSV Row parent for this field
     */
    private $row;

    /**
     * @var CSVHeader|null Header for this field. Null if no header.
     */
    private $header;

    /**
     * @var string Value for this field
     */
    private $value;

    /**
     * @return CSVRow
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return CSVHeader
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


    /**
     * CSVField constructor.
     * @param CSVRow $row
     * @param CSVHeader $header
     * @param string $value
     */
    public function __construct($row, $header, $value)
    {
        $this->row = $row;
        $this->header = $header;
        $this->value = $value;
    }

}