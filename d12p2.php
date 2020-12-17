<?php

$instructions = rtrim(file_get_contents('d12.txt'));
// $instructions = "F10
// N3
// F7
// R90
// F11";
$instructions = explode("\n", $instructions);

const DIRECTION_NORTH = 'N';
const DIRECTION_EAST = 'E';
const DIRECTION_SOUTH = 'S';
const DIRECTION_WEST = 'W';

const TURN_LEFT = 'L';
const TURN_RIGHT = 'R';

const DIRECTION_FORWARD = 'F';

$x = 0;
$y = 0;
$wX = 10;
$wY = -1;

const MOVEMENT_AXIS = [
	DIRECTION_NORTH => [0, -1],
	DIRECTION_EAST => [1, 0],
	DIRECTION_SOUTH => [0, 1],
	DIRECTION_WEST => [-1, 0],
];

function turnWaypointDegrees(int $x, int $y, string $turnDirection, int $degrees) : array
{
	// var_dump($x, $y);exit;
	$turns = $degrees / 90;
	while ($turns--)
	{
		switch ($turnDirection)
		{
			case TURN_LEFT:
				[$y, $x] = [-$x, $y];
			break;
			case TURN_RIGHT:
				[$y, $x] = [$x, -$y];
			break;
		}
	}

	return [$x, $y];
}

// $r = 1;
foreach ($instructions as $instruction)
{
	preg_match('%(.)(.+)%', $instruction, $matches);
	[, $direction, $amount] = $matches;

	switch ($direction)
	{
		case TURN_LEFT:
		case TURN_RIGHT:
			[$wX, $wY] = turnWaypointDegrees($wX, $wY, $direction, $amount);
		break;
		case DIRECTION_FORWARD:
			$x += $wX * $amount;
			$y += $wY * $amount;
		break;
		case DIRECTION_NORTH:
		case DIRECTION_EAST:
		case DIRECTION_SOUTH:
		case DIRECTION_WEST:
			$wX += MOVEMENT_AXIS[$direction][0] * $amount;
			$wY += MOVEMENT_AXIS[$direction][1] * $amount;
		break;
	}

	// if ($r === 5)
	// {
	// 	var_dump($x, $y, $wX, $wY);exit;
	// }

	// $r++;
}

echo abs($x) + abs($y), "\n";
