<?php

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

$blackTiles = 0;
array_walk_recursive($tileMap, function($tile) use (&$blackTiles) {
	$blackTiles += $tile;
});

echo $blackTiles, "\n";
