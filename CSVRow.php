<?php

/**
 * Class CSVFile
 *
 * @version 1.0
 * @author Alexandre Padovan
 * @license GNU GENERAL PUBLIC LICENSE
 */
class CSVRow
{

    /**
     * @var CSVFile CSV File instance for this CSV Row
     */
    private $csvFile;

    /**
     * @var int Index of this row
     */
    private $index;

    /**
     * @var CSVField[] Fields for this row
     */
    private $fields;

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return CSVField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return CSVFile
     */
    public function getCsvFile()
    {
        return $this->csvFile;
    }


    /**
     * CSVRow constructor.
     * @param CSVFile $csv
     * @param int $index
     * @param string[] $fields
     * @param CSVHeader[] $headers
     */
    public function __construct($csv, $index, $fields, $headers)
    {
        $this->csvFile = $csv;
        $this->index = $index;

        for($col = 0 ; $col < count($fields) ; $col++){
            $this->fields[] = new CSVField($this, $headers[$col], $fields[$col]);
        }
    }

}