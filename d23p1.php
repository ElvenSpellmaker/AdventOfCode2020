<?php

$input = '253149867';
// $input = '389125467';

$cups = str_split($input);

$numberofCups = count($cups);

$cycles = 100;
while ($cycles--)
{
	$currentCup = array_shift($cups);

	$takeCup1 = array_shift($cups);
	$takeCup2 = array_shift($cups);
	$takeCup3 = array_shift($cups);

	$findCup = $currentCup;

	do
	{
		$findCup--;

		if ($findCup <= 0)
		{
			$findCup = $numberofCups;
		}

		// if ($cycles === 0)
		// {
		// 	var_dump($findCup);
		// }

		$destination = array_search($findCup, $cups);
	}
	while ($destination === false);

	$cups[] = $currentCup;

	$takeCups = [$takeCup1, $takeCup2, $takeCup3];

	// if ($cycles === 0){var_dump($cups, $destination, $takeCups);}

	array_splice($cups, $destination + 1, 0, $takeCups);
}

$after1 = array_splice($cups, array_search('1', $cups), $numberofCups);

array_shift($after1);

echo join('', array_merge($after1, $cups)), "\n";
