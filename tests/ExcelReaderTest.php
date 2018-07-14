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
    public function testCountWithHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);
        $this->assertEquals(3, $reader->count());
    }

    /**
     *
     */
    public function testCountWithoutHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file);
        $this->assertEquals(3, $reader->count());
    }

    /**
     *
     */
    public function testCustomColumnHeadersWithHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);

        $this->assertEquals(
            array(
                'id',
                'number',
                'description',
            ),
            $reader->getColumnHeaders()
        );

        $reader->setColumnHeaders(
            array(
                'id2',
                'number2',
                'description2',
            )
        );

        $this->assertEquals(
            array(
                'id2',
                'number2',
                'description2',
            ),
            $reader->getColumnHeaders()
        );

        // TODO: Check if row 0 should return the header row if headers are enabled.
        // Row 0 returns the header row as data and indexes.
        $row = $reader->getRow(0);
        $this->assertEquals(
            array(
                'id2'          => 'id',
                'number2'      => 'number',
                'description2' => 'description',
            ),
            $row
        );

        $row = $reader->getRow(3);
        $this->assertEquals(
            array(
                'id2'          => 7.0,
                'number2'      => 7890.0,
                'description2' => 'Some more info',
            ),
            $row
        );

        $row = $reader->getRow(1);
        $this->assertEquals(
            array(
                'id2'          => 50.0,
                'number2'      => 123.0,
                'description2' => 'Description',
            ),
            $row
        );

        $row = $reader->getRow(2);
        $this->assertEquals(
            array(
                'id2'          => 6.0,
                'number2'      => 456.0,
                'description2' => 'Another description',
            ),
            $row
        );
    }

    /**
     *
     */
    public function testCustomColumnHeadersWithoutHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file);

        $reader->setColumnHeaders(
            array(
                'id',
                'number',
                'description',
            )
        );

        $row = $reader->getRow(2);
        $this->assertEquals(
            array(
                'id'          => 7.0,
                'number'      => 7890.0,
                'description' => 'Some more info',
            ),
            $row
        );

        $row = $reader->getRow(0);
        $this->assertEquals(
            array(
                'id'          => 50.0,
                'number'      => 123.0,
                'description' => 'Description',
            ),
            $row
        );

        $row = $reader->getRow(1);
        $this->assertEquals(
            array(
                'id'          => 6.0,
                'number'      => 456.0,
                'description' => 'Another description',
            ),
            $row
        );
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
    public function testMaxRowNumb()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file, null, null, null, 1000);
        $this->assertEquals(3, $reader->count());

        // Without $maxRows, this faulty file causes OOM because of an extremely
        //high last row number
        $file = new \SplFileObject(__DIR__.'/fixtures/data_extreme_last_row.xlsx');

        $max    = 5;
        $reader = new ExcelReader($file, null, null, null, $max);
        $this->assertEquals($max, $reader->count());
    }

    /**
     *
     */
    public function testMultiSheet()
    {
        $file         = new \SplFileObject(__DIR__.'/fixtures/data_multi_sheet.xls');
        $sheet1reader = new ExcelReader($file, null, 0);
        $this->assertEquals(3, $sheet1reader->count());

        $sheet2reader = new ExcelReader($file, null, 1);
        $this->assertEquals(2, $sheet2reader->count());
    }

    /**
     *
     */
    public function testSeekWithHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx');
        $reader = new ExcelReader($file, 0);

        // TODO: Check if row 0 should return the header row if headers are enabled.
        // Row 0 returns the header row as data and indexes.
        $row = $reader->getRow(0);
        $this->assertEquals(
            array(
                'id'          => 'id',
                'number'      => 'number',
                'description' => 'description',
            ),
            $row
        );
        $this->assertEquals(0, $reader->key());

        $row = $reader->getRow(3);
        $this->assertEquals(
            array(
                'id'          => 7.0,
                'number'      => 7890.0,
                'description' => 'Some more info',
            ),
            $row
        );
        $this->assertEquals(3, $reader->key());

        $row = $reader->getRow(1);
        $this->assertEquals(
            array(
                'id'          => 50.0,
                'number'      => 123.0,
                'description' => 'Description',
            ),
            $row
        );
        $this->assertEquals(1, $reader->key());

        $row = $reader->getRow(2);
        $this->assertEquals(
            array(
                'id'          => 6.0,
                'number'      => 456.0,
                'description' => 'Another description',
            ),
            $row
        );
        $this->assertEquals(2, $reader->key());
    }

    /**
     *
     */
    public function testSeekWithoutHeaders()
    {
        $file   = new \SplFileObject(__DIR__.'/fixtures/data_no_column_headers.xls');
        $reader = new ExcelReader($file);

        $row = $reader->getRow(2);
        $this->assertEquals(
            array(
                7.0,
                7890.0,
                'Some more info',
            ),
            $row
        );

        $row = $reader->getRow(0);
        $this->assertEquals(
            array(
                50.0,
                123.0,
                'Description',
            ),
            $row
        );

        $row = $reader->getRow(1);
        $this->assertEquals(
            array(
                6.0,
                456.0,
                'Another description',
            ),
            $row
        );
    }
}
