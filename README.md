# Simple PHP Statistics Class

A lightweight, easy-to-use PHP class for calculating common statistical measures. Originally created in 2010 and updated in 2025.

## Features

This statistics class provides numerous statistical calculations for numerical data sets:

- **Measures of Central Tendency**: Mean, Median, Mode
- **Measures of Dispersion**: Range, Variance, Standard Deviation, Interquartile Range
- **Frequency Analysis**: Frequency, Relative Frequency, Cumulative Frequency
- **Quartile Calculations**: Q1, Q2, Q3
- **Min/Max Values**
- **Population and Sample Statistics**

## Requirements

- PHP 8.0 or higher
- Strict typing enabled

## Installation

Simply include the `statistics.class.php` file in your project:

```php
require_once 'statistics.class.php';
```

## Basic Usage

```php
// Create a new Statistics object with an array of numbers
$scores = [7, 3.4, 4, 6.9, 4, 2.2, 7.8];
$stats = new Statistics($scores);

// Calculate and get the mean
$mean = $stats->findMean();
echo "Mean: $mean";

// Calculate and get the standard deviation (population)
$stdDev = $stats->findSD('p');
echo "Standard Deviation: $stdDev";
```

## Available Methods

### Constructor

```php
// Create a new instance with default rounding to 2 decimal places
$stats = new Statistics($scores, 2);
```

### Basic Statistics

```php
// Find and get basic statistics
$mean = $stats->findMean();
$median = $stats->findMedian();
$mode = $stats->findMode();
$range = $stats->findRange();
$min = $stats->findMin();
$max = $stats->findMax();
```

### Quartiles and IQR

```php
// Find quartiles (Q1, Q2, Q3)
$quartiles = $stats->findQ();
echo "Q1: " . $quartiles[1];
echo "Q2: " . $quartiles[2];
echo "Q3: " . $quartiles[3];

// Find interquartile range
$iqr = $stats->findIQR();
```

### Variance and Standard Deviation

```php
// Calculate population variance and standard deviation
$populationVariance = $stats->findV('p');
$populationStdDev = $stats->findSD('p');

// Calculate sample variance and standard deviation
$sampleVariance = $stats->findV('s');
$sampleStdDev = $stats->findSD('s');
```

### Frequency Analysis

```php
// Calculate and get frequency information
$frequency = $stats->getFrequency();
$relativeFrequency = $stats->calculateRF();
$relativeFrequencyPercent = $stats->calculateRFP();
$cumulativeFrequency = $stats->calculateCF();
```

### Other Methods

```php
// Calculate and get deviation from mean
$deviations = $stats->calculateXminAvg(false);  // (X - Mean)
$deviationsSquared = $stats->calculateXminAvg(true);  // (X - Mean)²

// Get population and sample sizes
$populationSize = $stats->getPN();
$sampleSize = $stats->getSN();
```

## Complete Example

```php
<?php
require_once 'statistics.class.php';

// Initialise with data
$scores = [7, 3.4, 4, 6.9, 4, 2.2, 7.8];
$stats = new Statistics($scores);

// Calculate all statistics
$q = $stats->findQ();
$max = $stats->findMax();
$min = $stats->findMin();
$mean = $stats->findMean();
$median = $stats->findMedian();
$mode = $stats->findMode();
$range = $stats->findRange();
$iqr = $stats->findIQR();
$pv = $stats->findV('p');
$sv = $stats->findV('s');
$psd = $stats->findSD('p');
$ssd = $stats->findSD('s');

// Output results
echo "Basic Statistics:\n";
echo "Mean: $mean\n";
echo "Median: $median\n";
echo "Mode: $mode\n";
echo "Range: $range\n";
echo "Min: $min, Max: $max\n";
echo "Quartiles: Q1={$q[1]}, Q2={$q[2]}, Q3={$q[3]}\n";
echo "IQR: $iqr\n";
echo "Population Variance: $pv\n";
echo "Sample Variance: $sv\n";
echo "Population Standard Deviation: $psd\n";
echo "Sample Standard Deviation: $ssd\n";
?>
```

## License

This project is licensed under the MIT License - see the LICENSE file for details.