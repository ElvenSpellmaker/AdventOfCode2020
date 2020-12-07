<?php

$bagLines = rtrim(file_get_contents('d7.txt'));
$bagLines = explode("\n", $bagLines);

$myBag = 'shiny gold';
$bagsTree = [];

foreach ($bagLines as $bagLine)
{
	preg_match('%(.+) bags contain (?:no other bags.|(\d+) (.+?) bags?(?:, (\d+ .+ bags?).|.$))%', $bagLine, $matches);
	switch (count($matches))
	{
		case 2:
			$bagsTree[$matches[1]] = [];
		break;
		case 4:
			$bagsTree[$matches[1]] = [['number' => $matches[2], 'bag' => $matches[3]]];
		break;
		case 5:
			preg_match_all('%,?(\d+) (.+?) (?:bags?)%', $matches[4], $lastBags);

			// Add first bag to tree.
			$bagsTree[$matches[1]][] = ['number' => $matches[2], 'bag' => $matches[3]];

			// Add the bags at the end, e.g. ", 2 pink bags, 4 drabby chartreuse bags."
			foreach ($lastBags[2] as $lastBagKey => $lastBag)
			{
				$bagsTree[$matches[1]][] = ['number' => $lastBags[1][$lastBagKey], 'bag' => $lastBag];
			}
		break;
	}
}

function sumBags(array $bagsTree, string $currentBag, int $sum) : int
{
	$currentSum = $sum;
	foreach ($bagsTree[$currentBag] as $nextBag)
	{
		$childSum = $nextBag['number'] + $nextBag['number'] * sumBags($bagsTree, $nextBag['bag'], $currentSum);
		// echo $nextBag['bag'], ": ", $childSum, "\n";
		$sum += $childSum;
	}

	return $sum;
}

echo sumBags($bagsTree, $myBag, 0), "\n";
