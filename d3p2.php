<?php

$map = rtrim(file_get_contents('d3.txt'));

$map = explode("\n", $map);

$slopeXValues = [
	1,
	3,
	5,
	7,
	1,
];

$slopeYValues = [
	1,
	1,
	1,
	1,
	2,
];

if (count($slopeXValues) !== count($slopeYValues))
{
	throw new RuntimeException('There must be an equal amount of slopes to use!');
}

$maps = array_fill(0, count($slopeXValues), $map);

function seenTrees(array $map, int $dx, int $dy) : int
{
	$height = count($map);
	$width = strlen($map[0]);

	$currX = 0;
	$currY = 0;

	$treesSeen = 0;
	while ($currY < $height)
	{
		if ($map[$currY][$currX] === '#')
		{
			$treesSeen++;
		}

		$currX += $dx;
		$currY += $dy;

		if ($currX >= $width)
		{
			$currX -= $width;
		}
	}

	return $treesSeen;
}

$treesSeenForSlopes = array_map('seenTrees', $maps, $slopeXValues, $slopeYValues);

echo array_product($treesSeenForSlopes), "\n";
