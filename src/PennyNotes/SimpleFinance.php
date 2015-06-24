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

namespace PennyNotes;

/**
 * SimpleFinance.
 *
 * Future Value Interest Factor (FVIF).
 * Regular Payment at Regular Interval (PMT).
 *
 * @see https://en.wikipedia.org/wiki/Time_value_of_money
 * @see https://secure.php.net/manual/en/book.bc.php
 **/
class SimpleFinance
{
    /**
     * Future Value Interest Factor (FVIF).
     *
     * @param float $interestRate interest rate
     * @param int   $periods      periods
     *
     * @throws \InvalidArgumentException if $interestRate is not float
     * @throws \InvalidArgumentException if $periods is not int
     *
     * @return float future value interest factor
     */
    public static function fvif($interestRate, $periods)
    {
        if (!is_float($interestRate)) {
            throw new \InvalidArgumentException('$interestRate is not float');
        }

        if (!is_int($periods)) {
            throw new \InvalidArgumentException('$periods is not int');
        }

        if ($periods === 0) {
            return 1;
        }

        $interestFactor = $interestRate + 1;

        if ($interestFactor == 0) {
            return 0;
        }

        if ($periods === 1) {
            return $interestFactor;
        }

        if ($periods < 0) {
            $periods = -$periods;

            $interestFactor = 1 / $interestFactor;
        }

        return pow($interestFactor, $periods);
    }

    /**
     * Regular Payment at Regular Interval (PMT).
     *
     * @param float $interestRate interest rate
     * @param int   $periods      periods
     * @param float $presentValue present value
     * @param float $futureValue  future value
     *
     * @throws \InvalidArgumentException if $interestRate is not float
     * @throws \InvalidArgumentException if $periods is not int
     *
     * @return float regular payment at regular interval
     */
    public static function pmt($interestRate, $periods, $presentValue, $futureValue)
    {
        if (!is_float($interestRate)) {
            throw new \InvalidArgumentException('$interestRate is not float');
        }

        if (!is_int($periods)) {
            throw new \InvalidArgumentException('$periods is not int');
        }

        if ($interestRate === 0) {
            $pmt = -1 * ($futureValue + $presentValue) / $periods;
        } else {
            $numerator = ($presentValue + $futureValue);

            $denominator = self::fvif($interestRate, $periods) - 1;

            $pmt = -1.0 * (($numerator / $denominator) + $presentValue) * $interestRate;
        }

        return $pmt;
    }

    /**
     * Calculate regular loan payment.
     *
     * @param float $annualInterest annual interest rate
     * @param int   $months         months
     *
     * @return float regular loan payment
     **/
    public static function payment($annualInterest, $months)
    {
        // convert annual interest rate to monthly interest rate
        $interestRate = $annualInterest / 12;

        // present value = -1 * [principal] = -25.00
        $presentValue = -25;

        // future value = 0
        $futureValue = 0;

        // calculate regular payment at regular interval (PMT).
        $pmt = self::pmt($interestRate, $months, $presentValue, $futureValue);

        // return result
        return $pmt;
    }

    /**
     * Calculate future value interest factor (FVIF).
     *
     * @param string $interestRate interest rate
     * @param int    $periods      periods
     *
     * @return string future value interest factor
     **/
    public static function bcfvif($interestRate, $periods)
    {
        if ($periods === 0) {
            return 1;
        }

        $interestFactor = bcadd($interestRate, 1);

        if (bccomp($interestFactor, 0) === 0) {
            return 0;
        }

        if ($periods === 1) {
            return $interestFactor;
        }

        if ($periods < 0) {
            $periods = -$periods;

            $interestFactor = bcdiv(1, $interestFactor);
        }

        return bcpow($interestFactor, $periods);
    }

    /**
     * Calculate regular payment at regular interval (PMT).
     *
     * @param string $interestRate interest rate
     * @param int    $periods      periods
     * @param string $presentValue present value
     * @param string $futureValue  future value
     *
     * @return string regular payment at regular interval
     */
    public static function bcpmt($interestRate, $periods, $presentValue, $futureValue)
    {
        $pmt = 0;

        if (bccomp($interestRate, 0) === 0) {
            $pmt = bcadd($futureValue, $presentValue);
            $pmt = bcdiv($pmt, $periods);
            $pmt = bcmul(-1, $pmt);
        } else {
            $numerator = bcadd($presentValue, $futureValue);

            $denominator = self::bcfvif($interestRate, $periods);
            $denominator = bcsub($denominator, 1);

            $pmt = bcdiv($numerator, $denominator);
            $pmt = bcadd($pmt, $presentValue);
            $pmt = bcmul($pmt, $interestRate);
            $pmt = bcmul(-1, $pmt);
        }

        return $pmt;
    }

    /**
     * Calculate regular loan payment.
     *
     * @param string $annualInterest annual interest rate
     * @param int    $months         months
     *
     * @return string regular loan payment
     **/
    public static function bcpayment($annualInterest, $months)
    {
        // convert annual interest rate to monthly interest rate
        $interestRate = bcdiv($annualInterest, 12);

        // present value = -1 * [principal] = -25.00
        $presentValue = -25;

        // future value = 0
        $futureValue = 0;

        // calculate regular payment at regular interval (PMT).
        $pmt = self::bcpmt($interestRate, $months, $presentValue, $futureValue);

        // return result
        return $pmt;
    }
}
