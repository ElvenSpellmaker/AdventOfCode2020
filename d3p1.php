<?php

$map = rtrim(file_get_contents('d3.txt'));

$map = explode("\n", $map);

$currX = 0;
$currY = 0;

$height = count($map);
$width = strlen($map[0]);

$treesSeen = 0;
while ($currY < $height)
{
	if ($map[$currY][$currX] === '#')
	{
		$treesSeen++;
	}

	$currX += 3;
	$currY += 1;

	if ($currX >= $width)
	{
		$currX -= $width;
	}
}

echo $treesSeen, "\n";
