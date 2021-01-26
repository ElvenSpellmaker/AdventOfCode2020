<?php

$map = rtrim(file_get_contents('d20.txt'));
$map = explode("\n\n", $map);

$mapArray = [];
foreach ($map as $minimap)
{
	[$tileId, $minimap] = explode("\n", $minimap, 2);

	preg_match('%Tile (\d+):%', $tileId, $matches);
	$tileId = $matches[1];

	$minimap = explode("\n", $minimap);
	$minimap = array_map('str_split', $minimap);

	$topRow = join('', $minimap[0]);
	$rightColumn = join('', array_column($minimap, count($minimap) - 1));
	$bottomRow = join('', $minimap[count($minimap) - 1]);
	$leftColumn = join('', array_column($minimap, 0));

	$mapArray[$tileId] = [
		'minimap' => $minimap,
		'edges' => [
			'top' => $topRow,
			'right' => $rightColumn,
			'bottom' => $bottomRow,
			'left' => $leftColumn,
		],
	];
}

$product = 1;
foreach ($mapArray as $tileId => $currentMinimap)
{
	$mapArrayCopy = $mapArray;
	unset($mapArrayCopy[$tileId]);

	$neighbours = 0;

	foreach ($currentMinimap['edges'] as $edge)
	{
		foreach ($mapArrayCopy as $minimapToCheck)
		{
			foreach ($minimapToCheck['edges'] as $checkEdge)
			{
				if ($edge === $checkEdge || strrev($edge) === $checkEdge)
				{
					$neighbours++;
					continue 3;
				}
			}
		}
	}

	// echo "Tile: $tileId - Neighbours: $neighbours\n";

	if ($neighbours === 2)
	{
		$product *= $tileId;
	}
}

echo $product, "\n";
