<?php

$code = rtrim(file_get_contents('d9.txt'));
// $code = "35
// 20
// 15
// 25
// 47
// 40
// 62
// 55
// 65
// 95
// 102
// 117
// 150
// 182
// 127
// 219
// 299
// 277
// 309
// 576";
$code = explode("\n", $code);

$preamble = 25;

$preambleArray = array_slice($code, 0, $preamble);
$remainingArray = array_slice($code, $preamble, count($code));

$preamblePosition = 0;
foreach ($remainingArray as $nextKey => $nextValue)
{
	foreach ($preambleArray as $tryKey => $tryValue)
	{
		$searchArray = $preambleArray;
		unset($searchArray[$tryKey]);
		$searchValue = $nextValue - $tryValue;

		$found = array_search($searchValue, $preambleArray);

		if ($found !== false)
		{
			break;
		}
	}

	unset($preambleArray[$preamblePosition++]);
	$preambleArray[] = $nextValue;

	if ($found === false)
	{
		break;
	}
}

$searchValue = $nextValue;
$searchValues = array_slice($code, 0, $nextKey);

foreach ($searchValues as $currKey => $currValue)
{
	$sum = $currValue;

	$sumValues = [$currValue];

	for ($i = $currKey + 1; $i < count($searchValues), $sum < $searchValue; $i++)
	{
		$sum += $searchValues[$i];
		$sumValues[] = $searchValues[$i];

		if ($sum === (int)$searchValue)
		{
			break 2;
		}
	}

	unset($searchValues[$currKey]);
}

echo min($sumValues) + max($sumValues), "\n";
