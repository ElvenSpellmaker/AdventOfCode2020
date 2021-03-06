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
foreach ($remainingArray as $nextValue)
{
	foreach ($preambleArray as $tryKey => $tryValue)
	{
		$searchArray = $preambleArray;
		unset($searchArray[$tryKey]);
		$searchValue = $nextValue - $tryValue;

		// echo "search array: ", join(', ', $searchArray);
		// echo  "search: ", $searchValue, "\n";

		$found = array_search($searchValue, $preambleArray);

		if ($found !== false)
		{
			break;
		}
	}

	unset($preambleArray[$preamblePosition++]);
	$preambleArray[] = $nextValue;

	// echo "\n";

	if ($found === false)
	{
		// echo 'Found: ', $nextValue, "\n";
		break;
	}
}

echo $nextValue, "\n";
