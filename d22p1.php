<?php

$cards = rtrim(file_get_contents('d22.txt'));
// $cards = 'Player 1:
// 9
// 2
// 6
// 3
// 1

// Player 2:
// 5
// 8
// 4
// 7
// 10';
$cards = explode("\n\n", $cards);

/**
 * @var SplDoublyLinkedList[] $game
 */
$game = [];
$i = 0;
foreach ($cards as $playerCards)
{
	$playerCards = explode("\n", $playerCards);
	for ($j = 1; $j < count($playerCards); $j++)
	{
		if (! array_key_exists($i, $game))
		{
			$game[$i] = new SplDoublyLinkedList;
		}

		$game[$i]->push($playerCards[$j]);
	}

	$i++;
}

do
{
	$playerCards = [];
	foreach ($game as $playerNumber => $player)
	{
		$playerCards[$playerNumber] = $player->shift();
	}

	arsort($playerCards);

	$winningPlayer = key($playerCards);

	foreach ($playerCards as $card)
	{
		$game[$winningPlayer]->push($card);
	}

	$game = array_filter($game, function(SplDoublyLinkedList $list) : bool { return count($list) !== 0; });
}
while (count($game) !== 1);

$winner = array_shift($game);
$winner->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
$i = 1;
$total = 0;
foreach ($winner as $card)
{
	$total += ($card * $i++);
}

echo $total, "\n";
