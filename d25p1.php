<?php

$keys = '12092626
4707356';
// $keys = '5764801
// 17807724';
[$cardKey, $doorKey] = explode("\n", $keys);

function transform(int $subject) : Generator
{
	$transformSize = 1;
	while (true)
	{
		$transformSize = ($transformSize * $subject) % 20201227;
		yield $transformSize;
	}
}

$cardKey = (int)$cardKey;
$doorKey = (int)$doorKey;

$cardLoopSize = 0;
$gen = transform(7);
do
{
	$key = $gen->current();
	$gen->next();
	$cardLoopSize++;
}
while ($cardKey !== $key);

$doorLoopSize = 0;
$gen = transform(7);
do
{
	$key = $gen->current();
	$gen->next();
	$doorLoopSize++;
}
while ($doorKey !== $key);

$gen = transform($doorKey);
while ($cardLoopSize--)
{
	$encryptionKey = $gen->current();
	$gen->next();
}

echo $encryptionKey, "\n";
