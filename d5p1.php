<?php

$seats = rtrim(file_get_contents('d5.txt'));
$seats = explode("\n", $seats);

$seatIds = new SplMaxHeap;

// $seats = [
// 	'FBFBBFFRLR',
// 	'BFFFBBFRRR',
// 	'FFFBBBFRRR',
// 	'BBFFBBFRLL',
// ];

foreach ($seats as $seat)
{
	[$row, $column] = str_split($seat, 7);

	$low = 0;
	$high = 127;
	for ($i = 0; $i < strlen($row); $i++)
	{
		switch ($row[$i])
		{
			case 'F':
				$high = floor(($low + $high) / 2);
			break;
			case 'B':
				$low = ceil(($low + $high) / 2);
			break;
		}
	}

	if ((int)$low !== (int)$high)
	{
		throw new RuntimeException('Not sure what happened here for the row...');
	}

	$row = $low;

	$low = 0;
	$high = 7;
	for ($i = 0; $i < strlen($column); $i++)
	{
		switch ($column[$i])
		{
			case 'L':
				$high = floor(($low + $high) / 2);
			break;
			case 'R':
				$low = ceil(($low + $high) / 2);
			break;
		}
	}

	if ((int)$low !== (int)$high)
	{
		throw new RuntimeException('Not sure what happened here for the column...');
	}

	$column = $low;

	$seatIds->insert(($row * 8) + $column);
}

echo $seatIds->top(), "\n";
