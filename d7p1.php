<?php

$bagLines = rtrim(file_get_contents('d7.txt'));
$bagLines = explode("\n", $bagLines);

$bagToFind = 'shiny gold';
$bagsTree = [];

foreach ($bagLines as $bagLine)
{
	preg_match('%(.+) bags contain (?:no other bags.|\d+ (.+?) bags?(?:, (\d+ .+ bags?).|.$))%', $bagLine, $matches);
	switch (count($matches))
	{
		case 2:
			$bagsTree[$matches[1]] = [];
		break;
		case 3:
			$bagsTree[$matches[1]] = [$matches[2]];
		break;
		case 4:
			preg_match_all('%,?\d+ (.+?) (?:bags?)%', $matches[3], $lastMatches);

			$bagsTree[$matches[1]] = array_merge([$matches[2]], $lastMatches[1]);
		break;
	}
}

function traverseBags(array $bagsTree, string $currentBag, string $findBag) : bool
{
	$seenFindBag = $currentBag === $findBag;
	if (count($bagsTree) === 0)
	{
		return $seenFindBag;
	}

	foreach ($bagsTree[$currentBag] as $nextBag)
	{
		$seenFindBag |= traverseBags($bagsTree, $nextBag, $findBag);
	}

	return $seenFindBag;
}

$sum = 0;
foreach ($bagsTree as $bagTreeCurrent => $_)
{
	if ($bagTreeCurrent === $bagToFind)
	{
		continue;
	}

	$sum += traverseBags($bagsTree, $bagTreeCurrent, $bagToFind);
}

echo $sum, "\n";
