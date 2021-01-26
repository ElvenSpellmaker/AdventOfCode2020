<?php

$shuttles = rtrim(file_get_contents('d13.txt'));
// $shuttles = "939
// 7,13,x,x,59,x,31,19";
[$delay, $shuttles] = explode("\n", $shuttles);

function filterX(string $shuttle) : bool
{
	return $shuttle !== 'x';
}

$shuttles = explode(',', $shuttles);
$shuttles = array_filter($shuttles, 'filterX');

$mapFunc = function(int $shuttle) use ($delay) : int {
	$shuttle = (ceil(($delay / $shuttle)) * $shuttle);
	return $shuttle % $delay;
};

$firstTimes = array_map($mapFunc, $shuttles);

$shuttles = array_combine($shuttles, $firstTimes);

asort($shuttles);

$firstShuttle = key($shuttles);

$waitTime = ($delay + $shuttles[$firstShuttle]) - $delay;

echo $firstShuttle * $waitTime, "\n";
