<?php

use PHPUnit\Framework\TestCase;

class StatisticsTest extends TestCase
{
    public function testMeanCalculation()
    {
        $stats = new Statistics([1, 2, 3, 4, 5]);
        $this->assertEquals(3.0, $stats->findMean());
    }

    public function testMedianCalculation()
    {
        $stats = new Statistics([1, 3, 3, 6, 7, 8, 9]);
        $this->assertEquals(6.0, $stats->findMedian());
        
        $statsEven = new Statistics([1, 2, 3, 4]);
        $this->assertEquals(2.5, $statsEven->findMedian());
    }
    
    public function testModeCalculation()
    {
        $stats = new Statistics([1, 2, 2, 3, 4]);
        $this->assertEquals(2.0, $stats->findMode());
    }

    public function testRangeCalculation()
    {
        $stats = new Statistics([1, 5, 10]);
        $this->assertEquals(9.0, $stats->findRange());
    }

    public function testMinMaxCalculation()
    {
        $stats = new Statistics([3, 1, 4, 1, 5, 9]);
        $this->assertEquals(1.0, $stats->findMin());
        $this->assertEquals(9.0, $stats->findMax());
    }

    public function testQuartilesAndIQR()
    {
        $stats = new Statistics([6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49]);
        $q = $stats->findQ();
        // The class uses a continuous interpolation method: rank = (N-1) * p
        $this->assertEquals(25.5, $q[1]); // Q1
        $this->assertEquals(40.0, $q[2]); // Q2 (Median)
        $this->assertEquals(42.5, $q[3]); // Q3
        $this->assertEquals(17.0, $stats->findIQR()); // Q3 - Q1
    }

    public function testVarianceAndStandardDeviation()
    {
        $stats = new Statistics([2, 4, 4, 4, 5, 5, 7, 9]);
        // Population Variance
        $this->assertEquals(4.0, $stats->findV('p'));
        // Population Standard Deviation
        $this->assertEquals(2.0, $stats->findSD('p'));
        
        // Sample Variance
        $this->assertEquals(4.57, $stats->findV('s')); // depends on rounding, default is 2
        // Sample Standard Deviation
        $this->assertEquals(2.14, $stats->findSD('s')); // sqrt(4.57) roughly 2.14
    }

    public function testIncludesZeroes()
    {
        $stats = new Statistics([0, 10]);
        // If zeroes are dropped, mean would be 10. With zero, it is 5.
        $this->assertEquals(5.0, $stats->findMean());
    }

    public function testEmptyArrayThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        new Statistics([]);
    }

    public function testDivisionByZeroOnVariance()
    {
        $stats = new Statistics([5]); // Sample size (n-1) = 0
        $this->expectException(DivisionByZeroError::class);
        $stats->findV('s');
    }
}
