<?php

$grid = rtrim(file_get_contents('d17.txt'));
// $grid = ".#.
// ..#
// ###";
$grid = explode("\n", $grid);

$metaGrid = [];
foreach ($grid as $rowKey => $row)
{
	$metaGrid[0][$rowKey] = str_split($row);
}

function gridDrawer(array $metaGrid, $minX, $maxX, $minY, $maxY, $minZ, $maxZ) : string
{
	$string = '';
	for ($z = $minZ; $z <= $maxZ; $z++)
	{
		echo 'z: ', $z, "\n";
		for ($y = $minY; $y <= $maxY; $y++)
		{
			for ($x = $minX; $x <= $maxX; $x++)
			{
				echo $metaGrid[$z][$y][$x] ?? '.';
			}

			echo "\n";
		}


		echo "\n";
	}

	return $string;
}

function getNeighbours(array $metaGrid, int $x, int $y, int $z) : array
{
	$neighbours = [];

	// Left Plane
	$neighbours[] = $metaGrid[$z - 1][$y - 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z - 1][$y - 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$z - 1][$y - 1][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$z - 1][$y][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z - 1][$y][$x] ?? '.';
	$neighbours[] = $metaGrid[$z - 1][$y][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$z - 1][$y + 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z - 1][$y + 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$z - 1][$y + 1][$x + 1] ?? '.';

	// Our Plane
	$neighbours[] = $metaGrid[$z][$y - 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z][$y - 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$z][$y - 1][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$z][$y][$x - 1] ?? '.';
	// $neighbours[] = $metaGrid[$z][$y][$x] ?? '.';
	$neighbours[] = $metaGrid[$z][$y][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$z][$y + 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z][$y + 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$z][$y + 1][$x + 1] ?? '.';

	// Right Plane
	$neighbours[] = $metaGrid[$z + 1][$y - 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z + 1][$y - 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$z + 1][$y - 1][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$z + 1][$y][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z + 1][$y][$x] ?? '.';
	$neighbours[] = $metaGrid[$z + 1][$y][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$z + 1][$y + 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$z + 1][$y + 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$z + 1][$y + 1][$x + 1] ?? '.';

	$neighbours = array_filter($neighbours, function(string $item) : bool {
		return $item !== '.';
	});

	return $neighbours;
}

$minZ = -1;
$maxZ = 1;
$minY = -1;
$maxY = count($metaGrid[0]);
$minX = -1;
$maxX = count($metaGrid[0][0]);

$cycles = 6;
while ($cycles--)
{
	$newGrid = $metaGrid;

	for ($z = $minZ; $z <= $maxZ; $z++)
	{
		$planeGrid = $metaGrid[$z] ?? [];

		for ($y = $minY; $y <= $maxY; $y++)
		{
			$row = $planeGrid[$y] ?? [];

			for ($x = $minX; $x <= $maxX; $x++)
			{
				$current = $metaGrid[$z][$y][$x] ?? '.';
				$neighbours = count(getNeighbours($metaGrid, $x, $y, $z));

				if ($current === '#' && ! ($neighbours === 2 || $neighbours === 3))
				{
					$newGrid[$z][$y][$x] = '.';
				}

				if ($current === '.' && $neighbours === 3)
				{
					$newGrid[$z][$y][$x] = '#';

					// Min Search Space
					if ($z === $minZ)
					{
						$minZ--;
					}

					if ($y === $minY)
					{
						$minY--;
					}

					if ($x === $minX)
					{
						$minX--;
					}

					// Max Search Space
					if ($z === $maxZ)
					{
						$maxZ++;
					}

					if ($y === $maxY)
					{
						$maxY++;
					}

					if ($x === $maxX)
					{
						$maxX++;
					}
				}
			}
		}
	}

	$metaGrid = $newGrid;
}

$sum = 0;

array_walk_recursive($metaGrid, function(string $item) use (&$sum) {
	$sum += $item === '#';
});

echo $sum, "\n";

// echo gridDrawer($metaGrid, -1, 3, 0, 4, -2, 2);
