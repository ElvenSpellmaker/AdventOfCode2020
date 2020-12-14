<?php

$code = rtrim(file_get_contents('d14.txt'));
// $code = "mask = 000000000000000000000000000000X1001X
// mem[42] = 100
// mask = 00000000000000000000000000000000X0XX
// mem[26] = 1";
$code = explode("\n", $code);

function maskToAddresses(string $mask) : array
{
	$maskLength = strlen($mask);
	$strings = [];

	$countX = substr_count($mask, 'X');

	$countXString = 2 ** $countX;

	for ($start = 0; $start < $countXString; $start++)
	{
		$replacements = sprintf("%0${countX}b", $start);
		$maskCopy = $mask;
		for ($i = 0, $j = 0; $i < $maskLength; $i++)
		{
			if ($mask[$i] !== 'X')
			{
				continue;
			}

			$maskCopy[$i] = $replacements[$j++];
		}

		$strings[] = $maskCopy;
	}

	return $strings;
}

function applyMask(string $value, string $mask) : string
{
	$maskXReplace = str_replace('X', '0', $mask);

	// Apply mask with 0s instead of X
	$value |= bindec($maskXReplace);
	// Pad the string back out
	$value = str_pad(decbin($value), 36, '0', STR_PAD_LEFT);
	// Now go through and replace the value with the mask X values
	for ($i = 0; $i < strlen($mask); $i++)
	{
		if ($mask[$i] === 'X')
		{
			$value[$i] = 'X';
		}
	}

	return $value;
}

$mask = '';
$mem = [];
foreach ($code as $line)
{
	if (strpos($line, 'mem') === 0)
	{
		preg_match('%mem\[(\d+)\] = (.+)%', $line, $matches);

		[, $index, $amount] = $matches;

		$maskedIndex = applyMask($index, $mask);
		$addresses = maskToAddresses($maskedIndex);
		foreach ($addresses as $address)
		{
			$mem[bindec($address)] = $amount;
		}
	}

	if (strpos($line, 'mask') === 0)
	{
		preg_match('%mask = (.+)%', $line, $matches);

		$mask = $matches[1];
	}
}

echo array_sum($mem), "\n";
