<?php

ini_set('memory_limit', '550M');

$tiles = rtrim(file_get_contents('d24.txt'));
// $tiles = 'sesenwnenenewseeswwswswwnenewsewsw
// neeenesenwnwwswnenewnwwsewnenwseswesw
// seswneswswsenwwnwse
// nwnwneseeswswnenewneswwnewseswneseene
// swweswneswnenwsewnwneneseenw
// eesenwseswswnenwswnwnwsewwnwsene
// sewnenenenesenwsewnenwwwse
// wenwwweseeeweswwwnwwe
// wsweesenenewnwwnwsenewsenwwsesesenwne
// neeswseenwwswnwswswnw
// nenwswwsewswnenenewsenwsenwnesesenew
// enewnwewneswsewnwswenweswnenwsenwsw
// sweneswneswneneenwnewenewwneswswnese
// swwesenesewenwneswnwwneseswwne
// enesenwswwswneneswsenwnewswseenwsese
// wnwnesenesenenwwnenwsewesewsesesew
// nenewswnwewswnenesenwnesewesw
// eneswnwswnwsenenwnwnwwseeswneewsenese
// neswnwewnwnwseenwseesewsenwsweewe
// wseweeenwnesenwwwswnew';
$tiles = explode("\n", $tiles);

$tileMap = [];

foreach ($tiles as $tileRoute)
{
	$tileRoute = str_split($tileRoute);

	$tile = '';
	$h = 0;
	$e = 0;
	$x = 0;
	foreach ($tileRoute as $tileStep)
	{
		$tile .= $tileStep;
		if ($tile === 'n' || $tile === 's')
		{
			continue;
		}

		switch ($tile)
		{
			case 'e':
				$h++;
				$e++;
			break;
			case 'w':
				$h--;
				$e--;
			break;
			case 'ne':
				$e++;
				$x++;
			break;
			case 'nw':
				$h--;
				$x++;
			break;
			case 'se':
				$h++;
				$x--;
			break;
			case 'sw':
				$e--;
				$x--;
			break;
		}

		$tile = '';
	}

	$tileToFlip = $tileMap[$h][$e][$x] ?? 0;

	$tileMap[$h][$e][$x] = ! $tileToFlip;
}

function getNeighbours(int $h, int $e, int $x, array $tileMap) : int
{
	$nwN = $tileMap[$h - 1][$e][$x + 1] ?? 0;
	$neN = $tileMap[$h][$e + 1][$x + 1] ?? 0;
	$eN = $tileMap[$h + 1][$e + 1][$x] ?? 0;
	$seN = $tileMap[$h + 1][$e][$x - 1] ?? 0;
	$swN = $tileMap[$h][$e - 1][$x - 1] ?? 0;
	$wN = $tileMap[$h - 1][$e - 1][$x] ?? 0;

	return $nwN + $neN + $eN + $seN + $swN + $wN;
}

function workOutTileColour(int $h, int $e, int $x, array $tileMap, array &$newTileMap) : void
{
	$tile = $tileMap[$h][$e][$x] ?? 0;
	$tile = (int)$tile;

	$neighbourCount = getNeighbours($h, $e, $x, $tileMap);

	if ($tile === 1 && ($neighbourCount === 0 || $neighbourCount > 2))
	{
		$tile = 0;
	}
	elseif ($tile === 0 && $neighbourCount === 2)
	{
		$tile = 1;
	}

	if ($tile)
	{
		$newTileMap[$h][$e][$x] = $tile;
	}
	else
	{
		unset($newTileMap[$h][$e][$x]);
	}
}

$cycles = 100;
while ($cycles--)
{
	// echo $cycles, "\n";
	$newTileMap = $tileMap;

	foreach ($tileMap as $h => $hRow)
	{
		foreach($hRow as $e => $eColumn)
		{
			foreach ($eColumn as $x => $blackTile)
			{
				workOutTileColour($h, $e, $x, $tileMap, $newTileMap);

				// Work out our neighbours
				// nw
				workOutTileColour($h - 1, $e, $x + 1, $tileMap, $newTileMap);
				// ne
				workOutTileColour($h, $e + 1, $x + 1, $tileMap, $newTileMap);
				// e
				workOutTileColour($h + 1, $e + 1, $x, $tileMap, $newTileMap);
				// se
				workOutTileColour($h + 1, $e, $x - 1, $tileMap, $newTileMap);
				// sw
				workOutTileColour($h, $e - 1, $x - 1, $tileMap, $newTileMap);
				// w
				workOutTileColour($h - 1, $e - 1, $x, $tileMap, $newTileMap);
			}
		}
	}

	$tileMap = $newTileMap;
}

$blackTiles = 0;

array_walk_recursive($tileMap, function($tile) use (&$blackTiles) {
	$blackTiles += $tile;
});

echo $blackTiles, "\n";
