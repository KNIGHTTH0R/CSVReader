<?php

/**
 * Class CSVFile
 *
 * @version 1.0
 * @author Alexandre Padovan
 * @license GNU GENERAL PUBLIC LICENSE
 */
class CSVHeader
{

    /**
     * @var CSVFile CSV File instance for this CSV Header
     */
    private $csvFile;

    /**
     * @var string Name of this header
     */
    private $name;

    /**
     * @var int Index of this header (column)
     */
    private $index;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return CSVFile
     */
    public function getCsvFile()
    {
        return $this->csvFile;
    }

    /**
     * CSVHeader constructor.
     * @param CSVFile $csv
     * @param string $name
     * @param int $index
     */
    public function __construct($csv, $name, $index)
    {
        $this->csvFile = $csv;
        $this->name = $name;
        $this->index = $index;
    }

}