<?php

namespace Port\Excel;

use Port\Reader\ReaderFactory;

/**
 * Factory that creates ExcelReaders
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class ExcelReaderFactory implements ReaderFactory
{
    /**
     * @var int
     */
    protected $headerRowNumber;

    /**
     * @var int
     */
    protected $activeSheet;

    /**
     * @param int $headerRowNumber
     * @param int $activeSheet
     */
    public function __construct($headerRowNumber = null, $activeSheet = null)
    {
        $this->headerRowNumber = $headerRowNumber;
        $this->activeSheet = $activeSheet;
    }

    /**
     * @param \SplFileObject $file
     *
     * @return ExcelReader
     */
    public function getReader(\SplFileObject $file)
    {
        return new ExcelReader($file, $this->headerRowNumber, $this->activeSheet);
    }
}
