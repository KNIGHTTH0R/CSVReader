<?php

/**
 * Class CSVFile
 *
 * @version 1.0
 * @author Alexandre Padovan
 * @license GNU GENERAL PUBLIC LICENSE
 */
class CSVException extends Exception
{

    /**
     * @var CSVFile CSV File instance throwing this error
     */
    private $csvFile;

    /**
     * CSVException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param CSVFile $csv
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, $csv)
    {
        parent::__construct($message, $code, $previous);
        $this->csvFile = $csv;
    }

    /**
     * Get the CSV File instance throwing this error.
     * @return CSVFile
     */
    public function getCsvFile()
    {
        return $this->csvFile;
    }


}