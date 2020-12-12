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

const TURN_DIRECTIONS = [
	DIRECTION_NORTH . TURN_LEFT => DIRECTION_WEST,
	DIRECTION_NORTH . TURN_RIGHT => DIRECTION_EAST,
	DIRECTION_EAST . TURN_LEFT => DIRECTION_NORTH,
	DIRECTION_EAST . TURN_RIGHT => DIRECTION_SOUTH,
	DIRECTION_SOUTH . TURN_LEFT => DIRECTION_EAST,
	DIRECTION_SOUTH . TURN_RIGHT => DIRECTION_WEST,
	DIRECTION_WEST . TURN_LEFT => DIRECTION_SOUTH,
	DIRECTION_WEST . TURN_RIGHT => DIRECTION_NORTH,
];

$facing = DIRECTION_EAST;
$x = 0;
$y = 0;

const MOVEMENT_AXIS = [
	DIRECTION_NORTH => [0, -1],
	DIRECTION_EAST => [1, 0],
	DIRECTION_SOUTH => [0, 1],
	DIRECTION_WEST => [-1, 0],
];

function turnDegrees(string $facing, string $turnDirection, int $degrees) : string
{
	$turns = $degrees / 90;
	while ($turns--)
	{
		$facing = TURN_DIRECTIONS[$facing . $turnDirection];
	}

	return $facing;
}

foreach ($instructions as $instruction)
{
	preg_match('%(.)(.+)%', $instruction, $matches);
	[, $direction, $amount] = $matches;

	switch ($direction)
	{
		case TURN_LEFT:
		case TURN_RIGHT:
			$facing = turnDegrees($facing, $direction, $amount);
		break;
		case DIRECTION_FORWARD:
			$direction = $facing;
		case DIRECTION_NORTH:
		case DIRECTION_EAST:
		case DIRECTION_SOUTH:
		case DIRECTION_WEST:
			$x += MOVEMENT_AXIS[$direction][0] * $amount;
			$y += MOVEMENT_AXIS[$direction][1] * $amount;
		break;
	}
}

echo abs($x) + abs($y), "\n";
