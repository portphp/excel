<?php

namespace Port\Excel\Tests;

use Port\Excel\ExcelReader;

class ExcelReaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!extension_loaded('zip')) {
            $this->markTestSkipped();
        }
    }

    public function testCountWithoutHeaders()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file);
        $this->assertEquals(3, $reader->count());
    }

    public function testCountWithHeaders()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);
        $this->assertEquals(3, $reader->count());
    }

    public function testIterate()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);
        foreach ($reader as $row) {
            $this->assertInternalType('array', $row);
            $this->assertEquals(array('id', 'number', 'description'), array_keys($row));
        }
    }

    public function testMultiSheet()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_multi_sheet.xls');
        $sheet1reader = new ExcelReader($file, null, 0);
        $this->assertEquals(3, $sheet1reader->count());

        $sheet2reader = new ExcelReader($file, null, 1);
        $this->assertEquals(2, $sheet2reader->count());
    }

    public function testExtremeLastRow()
    {
        // Without $maxRows, this faulty file causes OOM because of an extremely
        //high last row number
        $file = new \SplFileObject(__DIR__.'/fixtures/data_extreme_last_row.xlsx');

        $max = 5;
        $sheet1reader = new ExcelReader($file, null, null, null, $max);
        $this->assertEquals($max, $sheet1reader->count());
    }
}
