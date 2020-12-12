<?php

$seats = rtrim(file_get_contents('d11.txt'));
// $seats = "L.LL.LL.LL
// LLLLLLL.LL
// L.L.L..L..
// LLLL.LL.LL
// L.LL.LL.LL
// L.LLLLL.LL
// ..L.L.....
// LLLLLLLLLL
// L.LLLLLL.L
// L.LLLLL.LL";
$seats = explode("\n", $seats);

$seats = array_map('str_split', $seats);

$newSeats = $seats;

$rowSize = count($seats[0]);

class Reference
{
	private $x;
	private $y;

	public function __construct($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
	}

	public function getSeatFromReference(array $seats) : string
	{
		if ($this->x === false && $this->y === false)
		{
			return 'L';
		}

		return $seats[$this->y][$this->x];
	}
}

function findSeenSeat(array $seats, $x, $y, $dx, $dy) : Reference
{
	$return = null;
	do
	{
		$nextSeat = $seats[$y += $dy][$x += $dx] ?? false;

		if ($nextSeat === false)
		{
			return new Reference(false, false);
		}

		if ($nextSeat === '.')
		{
			continue;
		}

		$return = new Reference($x, $y);
	}
	while ($return === null);

	return $return;
}

// Work out seen seats first
$seeSeats = [];
foreach ($seats as $rowKey => $row)
{
	foreach ($row as $columnKey => $column)
	{
		// Top Left
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, -1, -1);
		// Top
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, 0, -1);
		// Top Right
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, 1, -1);
		// Left
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, -1, 0);
		// Right
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, 1, 0);
		// Bottom Left
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, -1, 1);
		// Bottom
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, 0, 1);
		// Bottom Right
		$seeSeats[$rowKey][$columnKey][] = findSeenSeat($seats, $columnKey, $rowKey, 1, 1);

		// if ($rowKey === 9 && $columnKey === 8)
		// {
		// 	var_dump($seeSeats[$rowKey][$columnKey]);exit;
		// }
	}
}

$rounds = 1;
do
{
	$seats = $newSeats;
	foreach ($seats as $rowKey => $row)
	{
		for ($columnKey = 0; $columnKey < $rowSize; $columnKey++)
		{
			if ($seats[$rowKey][$columnKey] === '.')
			{
				continue;
			}

			$totalSurroundingSeats = 0;

			// Top Left
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][0]->getSeatFromReference($seats) === '#';
			// Top
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][1]->getSeatFromReference($seats) === '#';
			// Top Right
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][2]->getSeatFromReference($seats) === '#';
			// Left
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][3]->getSeatFromReference($seats) === '#';
			// Right
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][4]->getSeatFromReference($seats) === '#';
			// Bottom Left
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][5]->getSeatFromReference($seats) === '#';
			// Bottom
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][6]->getSeatFromReference($seats) === '#';
			// Bottom Right
			$totalSurroundingSeats += $seeSeats[$rowKey][$columnKey][7]->getSeatFromReference($seats) === '#';

			// if ($rounds === 2 && $rowKey === 9 && $columnKey === 8)
			// {
			// 	// var_dump($seats[9][6]);exit;
			// 	var_dump($seeSeats[$rowKey][$columnKey][7]->getSeatFromReference($seats));exit;
			// 	// var_dump($seeSeats[$rowKey][$columnKey]);exit;
			// }

			if ($totalSurroundingSeats === 0)
			{
				$newSeats[$rowKey][$columnKey] = '#';
			}

			if ($totalSurroundingSeats >= 5)
			{
				$newSeats[$rowKey][$columnKey] = 'L';
			}
		}
	}

	// if ($rounds === 3)
	// {
	// 	foreach ($newSeats as $row)
	// 	{
	// 		echo join('', $row), "\n";
	// 	}
	// 	exit;
	// }

	$rounds++;
}
while ($newSeats !== $seats);

$sum = 0;
$countSeats = function($arrayValue) use (&$sum)
{
	return $sum += ($arrayValue === '#');
};

array_walk_recursive($newSeats, $countSeats);

echo $sum, "\n";
