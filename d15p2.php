<?php

ini_set('memory_limit', '1800M');

$numbers = rtrim(file_get_contents('d15.txt'));
// $numbers = "0,3,6";
$numbers = explode(',', $numbers);

$search = 30000000 - count($numbers);

$seenNumbers = [];
$turn = 1;
foreach ($numbers as $number)
{
	if ($turn !== 1)
	{
		$numberSeen = ($seenNumbers[$lastSeen]['times'] ?? 0) + 1;
		$seenNumbers[$lastSeen] = ['times' => $numberSeen, 'lastTurnSeen' => $turn - 1];
	}

	$lastSeen = $number;
	$turn++;
}

while ($search--)
{
	switch ($seenNumbers[$lastSeen]['times'] ?? 0)
	{
		case 0:
			$number = 0;
		break;
		default:
			// echo $seenNumbers[$lastSeen]['lastTurnSeen'], ", $turn: ";
			$number = $turn - $seenNumbers[$lastSeen]['lastTurnSeen'] - 1;
		break;
	}

	// var_dump($seenNumbers);

	$numberSeen = ($seenNumbers[$lastSeen]['times'] ?? 0) + 1;
	$seenNumbers[$lastSeen] = ['times' => $numberSeen, 'lastTurnSeen' => $turn - 1];

	// echo "$turn: $lastSeen: ", $number, "\n";
	// sleep(1);

	// echo "turn: ", $turn - 1, "last:", $lastSeen, "\n";

	$lastSeen = $number;
	$turn++;
}

echo $lastSeen, "\n";
