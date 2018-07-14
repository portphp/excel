<?php
/*
The MIT License (MIT)

Copyright (c) 2015 PortPHP

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */
namespace Port\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Port\Writer;

/**
 * Writes to an Excel file
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class ExcelWriter implements Writer
{
    /**
     * @var Spreadsheet
     */
    protected $excel;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var bool
     */
    protected $prependHeaderRow;

    /**
     * @var int
     */
    protected $row = 1;

    /**
     * @var null|string
     */
    protected $sheet;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param \SplFileObject $file             File
     * @param string         $sheet            Sheet title (optional)
     * @param string         $type             Excel file type (defaults to Xlsx)
     * @param bool           $prependHeaderRow
     */
    public function __construct(\SplFileObject $file, $sheet = null, $type = 'Xlsx', $prependHeaderRow = false)
    {
        $this->filename         = $file->getPathname();
        $this->sheet            = $sheet;
        $this->type             = $type;
        $this->prependHeaderRow = $prependHeaderRow;
    }

    /**
     * Wrap up the writer after all items have been written
     *
     * @return void Any returned value is ignored.
     */
    public function finish()
    {
        $writer = IOFactory::createWriter($this->excel, $this->type);
        $writer->save($this->filename);
    }

    /**
     * Prepare the writer before writing the items
     *
     * @return void Any returned value is ignored.
     */
    public function prepare()
    {
        $reader = IOFactory::createReader($this->type);
        if ($reader->canRead($this->filename)) {
            $this->excel = $reader->load($this->filename);
        } else {
            $this->excel = new Spreadsheet();
        }

        if (null !== $this->sheet) {
            if (!$this->excel->sheetNameExists($this->sheet)) {
                $this->excel->createSheet()->setTitle($this->sheet);
            }
            $this->excel->setActiveSheetIndexByName($this->sheet);
        }
    }

    /**
     * Write one data item
     *
     * @param array $item The data item with converted values
     *
     * @return void Any returned value is ignored.
     */
    public function writeItem(array $item)
    {
        $count = count($item);

        if ($this->prependHeaderRow && 1 === $this->row) {
            $headers = array_keys($item);

            for ($i = 0; $i < $count; $i++) {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($i + 1, $this->row, $headers[$i]);
            }
            $this->row++;
        }

        $values = array_values($item);

        for ($i = 0; $i < $count; $i++) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($i + 1, $this->row, $values[$i]);
        }

        $this->row++;
    }
}
