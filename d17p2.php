<?php

$grid = rtrim(file_get_contents('d17.txt'));
// $grid = ".#.
// ..#
// ###";
$grid = explode("\n", $grid);

$metaGrid = [];
foreach ($grid as $rowKey => $row)
{
	$metaGrid[0][0][$rowKey] = str_split($row);
}

function gridDrawer(array $metaGrid, $minX, $maxX, $minY, $maxY, $minZ, $maxZ, $minW, $maxW) : string
{
	$string = '';
	for ($w = $minW; $w <= $maxW; $w++)
	{
		for ($z = $minZ; $z <= $maxZ; $z++)
		{
			echo 'z: ', $z, ', w: ', $w, "\n";
			for ($y = $minY; $y <= $maxY; $y++)
			{
				for ($x = $minX; $x <= $maxX; $x++)
				{
					echo $metaGrid[$w][$z][$y][$x] ?? '.';
				}

				echo "\n";
			}


			echo "\n";
		}
	}

	return $string;
}

function getNeighbours(array $metaGrid, int $x, int $y, int $z, int $w) : array
{
	$neighbours = [];

	$neighbours = array_merge($neighbours, getNeighboursForCube($metaGrid, $x, $y, $z, $w - 1, false));

	$neighbours = array_merge($neighbours, getNeighboursForCube($metaGrid, $x, $y, $z, $w, true));

	$neighbours = array_merge($neighbours, getNeighboursForCube($metaGrid, $x, $y, $z, $w + 1, false));

	$neighbours = array_filter($neighbours, function(string $item) : bool {
		return $item !== '.';
	});

	return $neighbours;
}

function getNeighboursForCube(array $metaGrid, int $x, int $y, int $z, int $w, bool $origW) : array
{
	$neighbours = [];

	// Left Plane
	$neighbours[] = $metaGrid[$w][$z - 1][$y - 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z - 1][$y - 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z - 1][$y - 1][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$w][$z - 1][$y][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z - 1][$y][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z - 1][$y][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$w][$z - 1][$y + 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z - 1][$y + 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z - 1][$y + 1][$x + 1] ?? '.';

	// Our Plane
	$neighbours[] = $metaGrid[$w][$z][$y - 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z][$y - 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z][$y - 1][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$w][$z][$y][$x - 1] ?? '.';
	if ($origW === false)
	{
		$neighbours[] = $metaGrid[$w][$z][$y][$x] ?? '.';
	}
	$neighbours[] = $metaGrid[$w][$z][$y][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$w][$z][$y + 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z][$y + 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z][$y + 1][$x + 1] ?? '.';

	// Right Plane
	$neighbours[] = $metaGrid[$w][$z + 1][$y - 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z + 1][$y - 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z + 1][$y - 1][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$w][$z + 1][$y][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z + 1][$y][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z + 1][$y][$x + 1] ?? '.';

	$neighbours[] = $metaGrid[$w][$z + 1][$y + 1][$x - 1] ?? '.';
	$neighbours[] = $metaGrid[$w][$z + 1][$y + 1][$x] ?? '.';
	$neighbours[] = $metaGrid[$w][$z + 1][$y + 1][$x + 1] ?? '.';

	return $neighbours;
}

$minW = -1;
$maxW = 1;
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

	for ($w = $minW; $w <= $maxW; $w++)
	{
		$cube = $metaGrid[$w] ?? [];

		for ($z = $minZ; $z <= $maxZ; $z++)
		{
			$planeGrid = $cube[$z] ?? [];

			for ($y = $minY; $y <= $maxY; $y++)
			{
				$row = $planeGrid[$y] ?? [];

				for ($x = $minX; $x <= $maxX; $x++)
				{
					$current = $metaGrid[$w][$z][$y][$x] ?? '.';
					$neighbours = count(getNeighbours($metaGrid, $x, $y, $z, $w));

					if ($current === '#' && ! ($neighbours === 2 || $neighbours === 3))
					{
						$newGrid[$w][$z][$y][$x] = '.';
					}

					if ($current === '.' && $neighbours === 3)
					{
						$newGrid[$w][$z][$y][$x] = '#';

						// Min Search Space
						if ($w === $minW)
						{
							$minW--;
						}

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
						if ($w === $maxW)
						{
							$maxW++;
						}

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
	}

	$metaGrid = $newGrid;
}

$sum = 0;

array_walk_recursive($metaGrid, function(string $item) use (&$sum) {
	$sum += $item === '#';
});

echo $sum, "\n";

// echo gridDrawer($metaGrid, -1, 3, -1, 3, -1, 1, -1, 1);
