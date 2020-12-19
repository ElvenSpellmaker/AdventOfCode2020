<?php

if (! defined('JACK_ADD_MULT_PRECEDENCE_SAME'))
{
	echo <<< EOF
	Note: This script uses a specially crafted PHP version which treats + and *
	as the same precendence.

	Please use the `d18p1.Dockerfile` image to run this script!

	EOF;

	exit(1);
}

$sums = rtrim(file_get_contents('d18.txt'));
// $sums = '1 + (2 * 3) + (4 * (5 + 6))';
// $sums = '2 * 3 + (4 * 5)';
// $sums = '5 + (8 * 3 + 9 + 3 * 4 * 3)';
// $sums = '5 * 9 * (7 * 3 * 3 + 9 * 3 + (8 + 6 * 4))';
// $sums = '((2 + 4 * 9) * (6 + 9 * 8 + 6) + 6) + 2 + 4 * 2';
// $sums = '8 * (2 * 3 * 3 * 5 * 3 + 5) * 8 * (7 + (7 * 7 + 3 + 8) * 7 * 2) * ((3 + 3) * 7 + 9 + 4 + (2 + 7 + 2 + 6 * 7 * 9) * (9 * 3 * 5 + 9 + 7)) + 2';

// $sums = '(6 * 5 + 4 * 3 * 7 * (8 * 9)) * 4 + 4 + 8 * (3 + 7 * 5) + 2';
// $sums = '5 + 9 * 7 + 5 + 7 + (6 * (4 * 9 + 5 * 7 + 7 + 7) * (7 * 7 + 9 * 5) * 6 + 4 * 9)';

$sums = explode("\n", $sums);

function weirdAddition(string $sum) : int
{
	$answer = 0;
	eval('$answer += ' . $sum . ';');

	return $answer;
}

$sums = array_map('weirdAddition', $sums);

echo array_sum($sums), "\n";
