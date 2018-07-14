<?php

namespace Port\Excel\Tests;

use Port\Excel\ExcelReader;

/**
 * {@inheritDoc}
 */
class ExcelReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        if (!extension_loaded('zip')) {
            $this->markTestSkipped();
        }
    }

    /**
     *
     */
    public function testCountWithoutHeaders()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file);
        $this->assertEquals(3, $reader->count());
    }

    /**
     *
     */
    public function testCountWithHeaders()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);
        $this->assertEquals(3, $reader->count());
    }

    /**
     *
     */
    public function testIterateWithHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);

        $actualData   = array();
        $expectedData = array(
            array(
                'id'          => 50.0,
                'number'      => 123.0,
                'description' => 'Description',
            ),
            array(
                'id'          => 6.0,
                'number'      => 456.0,
                'description' => 'Another description',
            ),
            array(
                'id'          => 7.0,
                'number'      => 7890.0,
                'description' => 'Some more info',
            ),
        );

        foreach ($reader as $row) {
            $actualData[] = $row;
        }

        $this->assertEquals($expectedData, $actualData);
    }

    /**
     *
     */
    public function testIterateWithoutHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file);

        $actualData   = array();
        $expectedData = array(
            array(50.0, 123.0, "Description"),
            array(6.0, 456.0, 'Another description'),
            array(7.0, 7890.0, 'Some more info'),
        );

        foreach ($reader as $row) {
            $actualData[] = $row;
        }

        $this->assertEquals($expectedData, $actualData);
    }

    /**
     *
     */
    public function testMultiSheet()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_multi_sheet.xls');
        $sheet1reader = new ExcelReader($file, null, 0);
        $this->assertEquals(3, $sheet1reader->count());

        $sheet2reader = new ExcelReader($file, null, 1);
        $this->assertEquals(2, $sheet2reader->count());
    }

    /**
     *
     */
    public function testMaxRowNumb()
    {
        $file = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file, null, null, null, 1000);
        $this->assertEquals(3, $reader->count());

        // Without $maxRows, this faulty file causes OOM because of an extremely
        //high last row number
        $file = new \SplFileObject(__DIR__.'/fixtures/data_extreme_last_row.xlsx');

        $max = 5;
        $reader = new ExcelReader($file, null, null, null, $max);
        $this->assertEquals($max, $reader->count());
    }
}
