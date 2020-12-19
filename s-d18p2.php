<?php

if (! defined('JACK_ADD_MULT_PRECEDENCE_ADD_HIGHER'))
{
	echo <<< EOF
	Note: This script uses a specially crafted PHP version which treats + as
	a higher precedence order than *.

	Please use the `d18p2.Dockerfile` image to run this script!

	EOF;

	exit(1);
}

$sums = rtrim(file_get_contents('d18.txt'));

$sums = explode("\n", $sums);

function weirdAddition(string $sum) : int
{
	$answer = 0;
	eval('$answer += ' . $sum . ';');

	return $answer;
}

$sums = array_map('weirdAddition', $sums);

echo array_sum($sums), "\n";
