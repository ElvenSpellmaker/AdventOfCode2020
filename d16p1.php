<?php

$tickets = rtrim(file_get_contents('d16.txt'));
// $tickets = "class: 1-3 or 5-7
// row: 6-11 or 33-44
// seat: 13-40 or 45-50

// your ticket:
// 7,1,14

// nearby tickets:
// 7,3,47
// 40,4,50
// 55,2,20
// 38,6,12";
[$rules, $myTicket, $nearbyTickets] = explode("\n\n", $tickets);

$rules = explode("\n", $rules);
$myTicket = explode("\n", $myTicket);
$nearbyTickets = explode("\n", $nearbyTickets);

array_shift($myTicket);
array_shift($nearbyTickets);

$myTicket = explode(',', $myTicket[0]);

foreach ($nearbyTickets as &$nearbyTicket)
{
	$nearbyTicket = explode(',', $nearbyTicket);
}

unset($nearbyTicket);

$rulesArray = [];
foreach ($rules as $rule)
{
	preg_match('%(.+): (\d+)-(\d+) or (\d+)-(\d+)%', $rule, $matches);

	$rulesArray[] = [
		'min1' => $matches[2],
		'max1' => $matches[3],
		'min2' => $matches[4],
		'max2' => $matches[5],
	];
}

$sum = 0;
/**
 * @var array $nearbyTickets
 */
foreach ($nearbyTickets as $nearbyTicket)
{
	foreach ($nearbyTicket as $number)
	{
		$valid = false;
		foreach ($rulesArray as ['min1' => $min1, 'max1' => $max1, 'min2' => $min2, 'max2' => $max2])
		{
			if (($number >= $min1 && $number <= $max1) || ($number >= $min2 && $number <= $max2))
			{
				$valid = true;
				break;
			}
		}

		if (! $valid)
		{
			// echo $number, "\n";
			$sum += $number;
		}
	}
}

echo $sum, "\n";
