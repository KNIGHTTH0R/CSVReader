<?php

/**
 * Class CSVFile
 *
 * @version 1.0
 * @author Alexandre Padovan
 * @license GNU GENERAL PUBLIC LICENSE
 */
class CSVFile
{

    /**
     * CSVFile constructor. Only used for require_once call
     */
    public function __construct()
    {
        require_once "CSVException.php";
        require_once "CSVField.php";
        require_once "CSVHeader.php";
        require_once "CSVRow.php";
    }


    /**
     * @var string Local path to this csv file.
     */
    private $csvPath;

    /**
     * @var string csv context as a plain text file.
     */
    private $rawContent;

    /**
     * @var string Separator used to change row.
     */
    private $newLineDelimiter = "\n";

    /**
     * @var string Separator used to change column.
     */
    private $columnDelimiter = ";";

    /**
     * @var int Row count in this csv file.
     */
    private $rowCount = 0;

    /**
     * @var int Column count in this csv file.
     */
    private $columnCount = 0;

    /**
     * @var bool True if the first row of the file is the columns header.
     */
    private $firstRowAsHeader = false;

    /**
     * @var CSVHeader[] Headers of this CSV File.
     */
    private $headers;

    /**
     * @var CSVRow[] Rows of this CSV File.
     */
    private $rows;


    /**
     * @return string
     */
    public function getCsvPath()
    {
        return $this->csvPath;
    }

    /**
     * @return string
     */
    public function getRawContent()
    {
        return $this->rawContent;
    }

    /**
     * @return string
     */
    public function getNewLineDelimiter()
    {
        return $this->newLineDelimiter;
    }

    /**
     * @param string $newLineDelimiter
     * @return CSVFile
     */
    public function setNewLineDelimiter($newLineDelimiter)
    {
        $this->newLineDelimiter = $newLineDelimiter;
        return $this;
    }

    /**
     * @return string
     */
    public function getColumnDelimiter()
    {
        return $this->columnDelimiter;
    }

    /**
     * @param string $columnDelimiter
     * @return CSVFile
     */
    public function setColumnDelimiter($columnDelimiter)
    {
        $this->columnDelimiter = $columnDelimiter;
        return $this;
    }

    /**
     * @return int
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }

    /**
     * @return int
     */
    public function getColumnCount()
    {
        return $this->columnCount;
    }

    /**
     * @return bool
     */
    public function isFirstRowHeader()
    {
        return $this->firstRowAsHeader;
    }

    /**
     * @param bool $firstRowAsHeader
     * @return CSVFile
     */
    public function setFirstRowHeader($firstRowAsHeader)
    {
        $this->firstRowAsHeader = $firstRowAsHeader;
        return $this;
    }

    /**
     * @return CSVHeader[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param CSVHeader[] $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return CSVRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }


    /**
     * Import a CSV File and load it.
     *      Be sure to set all settings before loading a csv file.
     *
     * @param string $path_to_csv Path to the CSV file to import
     *
     * @throws CSVException
     */
    public function import($path_to_csv){
        if(substr($path_to_csv, 0, 1) != "/"){
            // Relative path
            $this->csvPath = __DIR__ . "/" . $path_to_csv;
        }else{
            // Absolute path
            $this->csvPath = $path_to_csv;
        }
        $this->rawContent = str_replace("\r", "", file_get_contents($this->csvPath));

        // Processing CSV

        $temporary_array = array();
        $lines = explode($this->newLineDelimiter, $this->rawContent);
        $this->rowCount = count($lines);

        for($row = 0 ; $row < $this->rowCount ; $row++){
            $columns = explode($this->columnDelimiter, $lines[$row]);
            $this->columnCount = count($columns);
            $temporary_array[$row] = $columns;
        }

        if($this->rowCount == 0 || $this->columnCount == 0){
            throw new CSVException("Empty CSV File.", 1, null, $this);
        }

        // -- Processing array

        if($this->firstRowAsHeader){
            for($col = 0 ; $col < $this->columnCount ; $col++){
                $this->headers[] = new CSVHeader($this, $temporary_array[0][$col], $col);
            }

            for($row = 1 ; $row < $this->rowCount ; $row++){
                $this->rows[] = new CSVRow($this, $row-1, $temporary_array[$row], $this->headers);
            }
        }else{
            for($col = 0 ; $col < $this->columnCount ; $col++){
                $this->headers[] = new CSVHeader($this, "Column" . ($col+1), $col);
            }

            for($row = 0 ; $row < $this->rowCount ; $row++){
                $this->rows[] = new CSVRow($this, $row, $temporary_array[$row], $this->headers);
            }
        }


    }


    public function save(){
        $finalRawContent = "";
        $header = array();
        foreach ($this->headers as $CSVHeader) {
            $header[] = $CSVHeader->getName();
        }
        $finalRawContent .= implode($this->columnDelimiter, $header) . $this->newLineDelimiter;
        $rows = array();
        foreach ($this->rows as $CSVRow) {
            $values = array();

            foreach ($CSVRow->getFields() as $CSVField) {
                $values[] = $CSVField->getValue();
            }

            $rows[] = implode($this->columnDelimiter, $values);
            
        }
        $finalRawContent .= implode($this->newLineDelimiter, $rows);
        file_put_contents($this->csvPath, $finalRawContent);
    }

    /**
     * @param int[] $ordering
     * @throws CSVException
     */
    public function reorder($ordering){
        if(count($ordering) != count($this->headers)) throw new CSVException("", 2, null, $this);

        $header = array();

        foreach ($ordering as $order) {
            $header[] = $this->headers[$order];
        }
        $this->headers = $header;

        foreach ($this->rows as $CSVRow) {
            $CSVRow->reorder($ordering);
        }

    }

    /**
     * Split column in two values. If values exceed 2, php will keep only the two firsts.
     *
     * @param $columnIndex
     * @param $splitText
     */
    public function splitColumn($columnIndex, $splitText){

        $header1 = new CSVHeader($this, $this->getHeaders()[$columnIndex]->getName() . "_split_1", $columnIndex);
        $header2 = new CSVHeader($this, $this->getHeaders()[$columnIndex]->getName() . "_split_2", $columnIndex);

        unset($this->headers[$columnIndex]);

        $this->headers[] = $header1;
        $this->headers[] = $header2;

        // Re-index array
        $this->headers = array_values($this->headers);

        // Fast rebuilding CSV Rows

        $rows = array();

        for($row = 0 ; $row < $this->getRowCount()-1 ; $row++){

            $values = array();

            foreach ($this->rows[$row]->getFields() as $CSVField) {
                $values[] = $CSVField->getValue();
            }

            $newValues = explode($splitText, $values[$columnIndex]);
            unset($values[$columnIndex]);
            $values[] = $newValues[0];
            $values[] = $newValues[1];

            // Re-index array
            $values = array_values($values);

            $rows[] = new CSVRow($this, $row, $values, $this->headers);
        }

        $this->rows = $rows;
        $this->columnCount++;

    }


}