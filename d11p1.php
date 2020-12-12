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

// $rounds = 1;
do
{
	$seats = $newSeats;
	foreach ($seats as $rowKey => $row)
	{
		for ($column = 0; $column < $rowSize; $column++)
		{
			if ($seats[$rowKey][$column] === '.')
			{
				continue;
			}

			$totalSurroundingSeats = 0;

			// Top Left
			$totalSurroundingSeats += ($seats[$rowKey - 1][$column - 1] ?? false) === '#';
			// Top
			$totalSurroundingSeats += ($seats[$rowKey - 1][$column] ?? false) === '#';
			// Top Right
			$totalSurroundingSeats += ($seats[$rowKey - 1][$column + 1] ?? false) === '#';
			// Left
			$totalSurroundingSeats += ($seats[$rowKey][$column - 1] ?? false) === '#';
			// Right
			$totalSurroundingSeats += ($seats[$rowKey][$column + 1] ?? false) === '#';
			// Bottom Left
			$totalSurroundingSeats += ($seats[$rowKey + 1][$column - 1]  ?? false) === '#';
			// Bottom
			$totalSurroundingSeats += ($seats[$rowKey + 1][$column]  ?? false) === '#';
			// Bottom Right
			$totalSurroundingSeats += ($seats[$rowKey + 1][$column + 1] ?? false) === '#';

			if ($totalSurroundingSeats === 0)
			{
				$newSeats[$rowKey][$column] = '#';
			}

			if ($totalSurroundingSeats >= 4)
			{
				$newSeats[$rowKey][$column] = 'L';
			}
		}
	}

	// if ($rounds === 2)
	// {
	// 	var_dump($newSeats);exit;
	// }

	// $rounds++;
}
while ($newSeats !== $seats);

$sum = 0;
$countSeats = function($arrayValue) use (&$sum)
{
	return $sum += ($arrayValue === '#');
};

array_walk_recursive($newSeats, $countSeats);

echo $sum, "\n";
