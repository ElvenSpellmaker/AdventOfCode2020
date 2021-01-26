<?php

$joltageAdapters = rtrim(file_get_contents('d10.txt'));
$joltageAdapters = explode("\n", $joltageAdapters);

$sortedAdapters = new SplMinHeap;

foreach ($joltageAdapters as $adapter)
{
	$sortedAdapters->insert($adapter);
}

$currentJoltage = 0;
$seenDiffOne = 0;
$seenDiffThree = 0;
foreach ($sortedAdapters as $adapter)
{
	$difference = $adapter - $currentJoltage;

	switch ($difference)
	{
		case '1':
			$seenDiffOne++;
		break;
		case '3':
			$seenDiffThree++;
		break;
	}

	$currentJoltage += $difference;
}

$seenDiffThree++;

// echo $seenDiffOne, ":", $seenDiffThree, "\n";

echo $seenDiffOne * $seenDiffThree, "\n";
