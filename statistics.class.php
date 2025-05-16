<?php

declare(strict_types=1);

class Statistics {
    private int $round = 2;
    public array $scores;
    public array $frequency;
    public int $pn;
    public int $sn;
    public ?array $fx = null;
    public ?array $XminA = null;
    public ?array $XminAsqr = null;
    public ?array $rf = null;
    public ?array $rfp = null;
    public ?array $cf = null;
    public ?float $mean = null;
    public ?float $median = null;
    public ?float $mode = null;
    public ?float $range = null;
    public ?float $iqr = null;
    public ?float $pv = null;
    public ?float $sv = null;
    public ?float $psd = null;
    public ?float $ssd = null;
    public ?array $q = null;
    public ?float $max = null;
    public ?float $min = null;

    public function __construct(array $scores = [1], int $round = 2) {
        $this->round = $round;
        $this->scores = $this->cleanArray($scores);
        $this->frequency = $this->calculateFrequency();
        $this->pn = count($this->scores);
        $this->sn = count($this->scores) - 1;
    }

    /**
     * Clean an array of non-numeric values
     *
     * @param array $x Set of numbers
     * @return array Cleaned scores
     */
    private function cleanArray(array $x): array {
        $cleanScores = [];
        foreach ($x as $val) {
            if ($val != 0) {
                $cleanScores[] = (string)$val;
            }
        }
        sort($cleanScores);
        return $cleanScores;
    }

    /**
     * Return Scores
     *
     * @return array Scores
     */
    public function getScores(): array {
        return $this->scores;
    }

    /**
     * Calculate Frequency
     *
     * @return array Frequency
     */
    public function calculateFrequency(): array {
        return array_count_values($this->scores);
    }

    /**
     * Return Frequency
     *
     * @return array Frequency
     */
    public function getFrequency(): array {
        return $this->frequency;
    }

    /**
     * Return Population Size
     *
     * @return int Population size
     */
    public function getPN(): int {
        return $this->pn;
    }

    /**
     * Return Sample Size
     *
     * @return int Sample size
     */
    public function getSN(): int {
        return $this->sn;
    }

    /**
     * Return Quartiles 1, 2 and 3
     *
     * @return array Quartiles
     */
    public function getQ(): ?array {
        return $this->q;
    }

    /**
     * Calculate Quartiles 1, 2 and 3
     *
     * @return array Quartiles
     */
    public function findQ(): array {
        $this->q = [];
        $this->q[1] = $this->percentile($this->scores, 25);
        $this->q[2] = $this->percentile($this->scores, 50);
        $this->q[3] = $this->percentile($this->scores, 75);
        return $this->q;
    }

    /**
     * Calculate FX (F * X) (Frequency * Scores)
     *
     * @return array FX values
     */
    public function calculateFX(): array {
        $frequency = $this->frequency;
        $fx = [];

        foreach ($this->scores as $key => $value) {
            $newValue = $value * $frequency[$value];
            $fx[$key] = round((float)$newValue, $this->round);
        }
        
        $this->fx = $fx;
        return $this->fx;
    }

    /**
     * Return FX
     *
     * @return array FX values
     */
    public function getFX(): ?array {
        return $this->fx;
    }

    /**
     * Calculate the Mean
     *
     * @return float Mean
     */
    public function findMean(): float {
        $total = 0;
        foreach ($this->scores as $score) {
            $total += (float)$score;
        }
        $this->mean = round(($total / $this->pn), $this->round);
        return $this->mean;
    }

    /**
     * Return the Mean
     *
     * @return float Mean
     */
    public function getMean(): ?float {
        return $this->mean;
    }

    /**
     * Calculate the Median
     *
     * @return float Median
     */
    public function findMedian(): float {
        if ($this->q === null) {
            $this->q = $this->findQ();
        }
        $this->median = $this->q[2];
        return $this->median;
    }

    /**
     * Return the Median
     *
     * @return float Median
     */
    public function getMedian(): ?float {
        return $this->median;
    }

    /**
     * Calculate the Mode
     *
     * @return float Mode
     */
    public function findMode(): float {
        $counted = array_count_values($this->scores);
        arsort($counted);
        $this->mode = (float)key($counted);
        return $this->mode;
    }

    /**
     * Return the Mode
     *
     * @return float Mode
     */
    public function getMode(): ?float {
        return $this->mode;
    }

    /**
     * Calculate the Range
     *
     * @return float Range
     */
    public function findRange(): float {
        if ($this->max === null) {
            $this->max = $this->findMax();
        }
        if ($this->min === null) {
            $this->min = $this->findMin();
        }
        $this->range = $this->max - $this->min;
        return $this->range;
    }

    /**
     * Return the Range
     *
     * @return float Range
     */
    public function getRange(): ?float {
        return $this->range;
    }

    /**
     * Calculate the Highest Value
     *
     * @return float Maximum value
     */
    public function findMax(): float {
        $this->max = (float)max($this->scores);
        return $this->max;
    }

    /**
     * Calculate the Lowest Value
     *
     * @return float Minimum value
     */
    public function findMin(): float {
        $this->min = (float)min($this->scores);
        return $this->min;
    }

    /**
     * Return the Highest Value
     *
     * @return float Maximum value
     */
    public function getMax(): ?float {
        return $this->max;
    }

    /**
     * Return the Lowest Value
     *
     * @return float Minimum value
     */
    public function getMin(): ?float {
        return $this->min;
    }

    /**
     * Calculate (X - Mean) OR (X - Mean) squared
     *
     * @param bool $sqr Calculate squared values
     * @return array Results
     */
    public function calculateXminAvg(bool $sqr = false): array {
        if ($this->mean === null) {
            $this->mean = $this->findMean();
        }
        
        $mean = $this->mean;
        $XminA = [];
        
        foreach ($this->scores as $key => $val) {
            $value = ($sqr) 
                ? round(pow(((float)$val - $mean), 2), $this->round) 
                : round(((float)$val - $mean), $this->round);
            
            $XminA[$key] = $value;
        }
        
        if ($sqr) {
            $this->XminAsqr = $XminA;
            return $this->XminAsqr;
        } else {
            $this->XminA = $XminA;
            return $this->XminA;
        }
    }

    /**
     * Return (X - Mean)
     *
     * @return array XminA values
     */
    public function getXminAvg(): ?array {
        return $this->XminA;
    }

    /**
     * Return (X - Mean) squared
     *
     * @return array XminAsqr values
     */
    public function getXminAvgsqr(): ?array {
        return $this->XminAsqr;
    }

    /**
     * Calculate the Interquartile Range
     *
     * @return float Interquartile range
     */
    public function findIQR(): float {
        if ($this->q === null) {
            $this->q = $this->findQ();
        }
        $this->iqr = $this->q[3] - $this->q[1];
        return $this->iqr;
    }

    /**
     * Return the Interquartile Range
     *
     * @return float Interquartile range
     */
    public function getIQR(): ?float {
        return $this->iqr;
    }

    /**
     * Calculate the score at a certain percentile within an array
     *
     * @param array $x Set of numbers
     * @param float|int $percentile Percentile to find
     * @return float Percentile value
     */
    private function percentile(array $x, float|int $percentile): float {
        if (0 < $percentile && $percentile < 1) {
            $p = $percentile;
        } elseif (1 < $percentile && $percentile <= 100) {
            $p = $percentile * 0.01;
        } else {
            return 0.0;
        }
        
        $count = count($x);
        $allindex = ($count - 1) * $p;
        $intvalindex = intval($allindex);
        $floatval = $allindex - $intvalindex;
        
        sort($x);
        
        if (!is_float($floatval)) {
            $result = (float)$x[$intvalindex];
        } else {
            if ($count > $intvalindex + 1) {
                $result = $floatval * ((float)$x[$intvalindex + 1] - (float)$x[$intvalindex]) + (float)$x[$intvalindex];
            } else {
                $result = (float)$x[$intvalindex];
            }
        }
        
        return $result;
    }

    /**
     * Calculate the Population or Sample Variance
     *
     * @param string $ps 'p' for population or 's' for sample
     * @return float Variance
     */
    public function findV(string $ps = 'p'): float {
        if ($this->XminAsqr === null) {
            $this->XminAsqr = $this->calculateXminAvg(true);
        }
        
        $n = ($ps === 'p') ? $this->pn : $this->sn;
        $sumXminAsqr = array_sum($this->XminAsqr);
        
        $result = round(($sumXminAsqr) / $n, $this->round);
        
        if ($ps === 'p') {
            $this->pv = $result;
        } else {
            $this->sv = $result;
        }
        
        return $result;
    }

    /**
     * Return the Population Variance
     *
     * @return float Population variance
     */
    public function getPV(): ?float {
        return $this->pv;
    }

    /**
     * Return the Sample Variance
     *
     * @return float Sample variance
     */
    public function getSV(): ?float {
        return $this->sv;
    }

    /**
     * Calculate the Sample Standard Deviation or Population Standard Deviation
     *
     * @param string $ps 'p' for population or 's' for sample
     * @return float Standard deviation
     */
    public function findSD(string $ps = 'p'): float {
        $v = 0;
        
        if ($ps === 'p') {
            if ($this->pv === null) {
                $this->pv = $this->findV('p');
            }
            $v = $this->pv;
            $this->psd = round(sqrt($v), $this->round);
            return $this->psd;
        } else {
            if ($this->sv === null) {
                $this->sv = $this->findV('s');
            }
            $v = $this->sv;
            $this->ssd = round(sqrt($v), $this->round);
            return $this->ssd;
        }
    }

    /**
     * Return Population Standard Deviation
     *
     * @return float Population standard deviation
     */
    public function getPSD(): ?float {
        return $this->psd;
    }

    /**
     * Return Sample Standard Deviation
     *
     * @return float Sample standard deviation
     */
    public function getSSD(): ?float {
        return $this->ssd;
    }

    /**
     * Calculate Relative Frequency
     *
     * @return array Relative frequency
     */
    public function calculateRF(): array {
        $f = $this->frequency;
        $fsum = array_sum($f);
        $rf = [];
        
        foreach ($f as $freq) {
            $rf[] = round(($freq / $fsum), $this->round);
        }
        
        $this->rf = $rf;
        return $this->rf;
    }

    /**
     * Return Relative Frequency
     *
     * @return array Relative frequency
     */
    public function getRF(): ?array {
        return $this->rf;
    }

    /**
     * Calculate Relative Frequency Percentages
     *
     * @return array Relative frequency percentages
     */
    public function calculateRFP(): array {
        if ($this->rf === null) {
            $this->rf = $this->calculateRF();
        }
        
        $rfp = [];
        
        foreach ($this->rf as $f) {
            $rfp[] = round(($f * 100), $this->round);
        }
        
        $this->rfp = $rfp;
        return $this->rfp;
    }

    /**
     * Return Relative Frequency Percentages
     *
     * @return array Relative frequency percentages
     */
    public function getRFP(): ?array {
        return $this->rfp;
    }

    /**
     * Calculate Cumulative Frequency
     *
     * @return array Cumulative frequency
     */
    public function calculateCF(): array {
        $cf = [];
        $total = 0;
        
        foreach ($this->frequency as $f) {
            $total += $f;
            $cf[] = $total;
        }
        
        $this->cf = $cf;
        return $this->cf;
    }

    /**
     * Return Cumulative Frequency
     *
     * @return array Cumulative frequency
     */
    public function getCF(): ?array {
        return $this->cf;
    }
}