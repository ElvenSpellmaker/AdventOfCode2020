<?php

$code = rtrim(file_get_contents('d14.txt'));
// $code = "mask = XXXXXXXXXXXXXXXXXXXXXXXXXXXXX1XXXX0X
// mem[8] = 11
// mem[7] = 101
// mem[8] = 0";
$code = explode("\n", $code);

// Fudge code into valid PHP
foreach ($code as &$line)
{
	if (strpos($line, 'mem') === 0)
	{
		preg_match('%mem\[(\d+)\] = (.+)%', $line, $matches);

		[, $index, $amount] = $matches;

		$line = '$mem[' . $index . '] = ' . $amount . ' & $maskAnd | $maskOr;';
	}

	if (strpos($line, 'mask') === 0)
	{
		preg_match('%mask = (.+)%', $line, $matches);

		$mask = $matches[1];

		$maskLine = '$maskAnd = 0b' . str_replace('X', '1', $mask) . ';';
		$maskLine .= '$maskOr = 0b' . str_replace('X', '0', $mask) . ';';
		$line = $maskLine;
	}
}

// var_dump($code);exit;

$mem = [];

$code = join('', $code);

// Execute it!
eval($code);

echo array_sum($mem), "\n";
