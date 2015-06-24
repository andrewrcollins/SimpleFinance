<?php

/**
 * Simple Finance Functions for PHP.
 *
 * @author Andrew Collins <andrew@dripsandcastle.com>
 * @copyright Andrew Collins <andrew@dripsandcastle.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 *
 * @link https://github.com/andrewrcollins/SimpleFinance
 */
use PennyNotes\SimpleFinance;

/**
 * SimpleFinanceTest.
 *
 * Future Value Interest Factor (FVIF).
 * Regular Payment at Regular Interval (PMT).
 *
 * @see https://en.wikipedia.org/wiki/Time_value_of_money
 * @see https://secure.php.net/manual/en/book.bc.php
 * @see https://phpunit.de/
 **/
class SimpleFinanceTest extends PHPUnit_Framework_TestCase
{
    /**
     * FVIF.
     **/
    public function testFVIFInterestRateNotFloat()
    {
        $this->setExpectedException('\InvalidArgumentException', '$interestRate is not float');

        \PennyNotes\SimpleFinance::fvif('a string', 1);
    }

    /**
     * FVIF.
     **/
    public function testFVIFPeriodsNotInt()
    {
        $this->setExpectedException('\InvalidArgumentException', '$periods is not int');

        \PennyNotes\SimpleFinance::fvif(1.0, 'a string');
    }

    /**
     * FVIF.
     **/
    public function testFVIFPeriodsEqualsZero()
    {
        $fvif = \PennyNotes\SimpleFinance::fvif(1.0, 0);
        $this->assertEquals(1, $fvif);
    }

    /**
     * FVIF.
     **/
    public function testFVIFInterestFactorZero()
    {
        $fvif = \PennyNotes\SimpleFinance::fvif(-1.0, 1);
        $this->assertEquals(0.0, $fvif);
    }

    /**
     * FVIF.
     **/
    public function testFVIFPeriodsOne()
    {
        $interestRate = 0.01;
        $fvif = \PennyNotes\SimpleFinance::fvif($interestRate, 1);
        $this->assertEquals($interestRate + 1, $fvif);

        $interestRate = 0.05;
        $fvif = \PennyNotes\SimpleFinance::fvif($interestRate, 1);
        $this->assertEquals($interestRate + 1, $fvif);

        $interestRate = 0.1;
        $fvif = \PennyNotes\SimpleFinance::fvif($interestRate, 1);
        $this->assertEquals($interestRate + 1, $fvif);

        $interestRate = 0.25;
        $fvif = \PennyNotes\SimpleFinance::fvif($interestRate, 1);
        $this->assertEquals($interestRate + 1, $fvif);

        $interestRate = 0.5;
        $fvif = \PennyNotes\SimpleFinance::fvif($interestRate, 1);
        $this->assertEquals($interestRate + 1, $fvif);
    }

    /**
     * PMT.
     **/
    public function testPMTInterestRateNotFloat()
    {
        $this->setExpectedException('\InvalidArgumentException', '$interestRate is not float');

        \PennyNotes\SimpleFinance::pmt('a string', 1, 0, 0);
    }

    /**
     * PMT.
     **/
    public function testPMTPeriodsNotInt()
    {
        $this->setExpectedException('\InvalidArgumentException', '$periods is not int');

        \PennyNotes\SimpleFinance::pmt(1.0, 'a string', 0, 0);
    }
}
