#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/statistics.class.php';

// Example of using the Statistics class
$scores = [7, 3.4, 4, 6.9, 4, 2.2, 7.8];
$stats = new Statistics($scores);

// Calculate all statistics
echo "Example usage of Statistics class with scores: " . implode(', ', $scores) . PHP_EOL . PHP_EOL;

// Find quartiles
$q = $stats->findQ();
echo "Quartiles: Q1={$q[1]}, Q2={$q[2]}, Q3={$q[3]}" . PHP_EOL;

// Find min/max
$max = $stats->findMax();
$min = $stats->findMin();
echo "Min: $min, Max: $max" . PHP_EOL;

// Find central tendency measures
$mean = $stats->findMean();
$median = $stats->findMedian();
$mode = $stats->findMode();
echo "Mean: $mean, Median: $median, Mode: $mode" . PHP_EOL;

// Find dispersion measures
$range = $stats->findRange();
$iqr = $stats->findIQR();
echo "Range: $range, IQR: $iqr" . PHP_EOL;

// Find variance measures
$pv = $stats->findV('p');
$sv = $stats->findV('s');
echo "Population Variance: $pv, Sample Variance: $sv" . PHP_EOL;

// Find standard deviation measures
$psd = $stats->findSD('p');
$ssd = $stats->findSD('s');
echo "Population Std Dev: $psd, Sample Std Dev: $ssd" . PHP_EOL;

// Calculate additional statistics
$stats->calculateXminAvg(false);
$stats->calculateXminAvg(true);
$stats->calculateRF();
$stats->calculateRFP();
$stats->calculateCF();

// Output all calculated statistics
echo PHP_EOL . "All Statistics:" . PHP_EOL;
echo "Population size: " . $stats->pn . PHP_EOL;
echo "Sample size: " . $stats->sn . PHP_EOL;
echo "Frequency: " . json_encode($stats->frequency) . PHP_EOL;
echo "Relative frequency: " . json_encode($stats->rf) . PHP_EOL;
echo "Relative frequency %: " . json_encode($stats->rfp) . PHP_EOL;
echo "Cumulative frequency: " . json_encode($stats->cf) . PHP_EOL;

?>