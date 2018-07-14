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

use Port\Excel\ExcelReaderFactory;

/**
 * {@inheritDoc}
 */
class ExcelReaderFactoryTest extends \PHPUnit_Framework_TestCase
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
    public function testGetReader()
    {
        $factory = new ExcelReaderFactory();
        $reader  = $factory->getReader(new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx'));
        $this->assertInstanceOf('\Port\Excel\ExcelReader', $reader);
        $this->assertCount(4, $reader);

        $factory = new ExcelReaderFactory(0);
        $reader  = $factory->getReader(new \SplFileObject(__DIR__.'/fixtures/data_column_headers.xlsx'));
        $this->assertCount(3, $reader);
    }
}
