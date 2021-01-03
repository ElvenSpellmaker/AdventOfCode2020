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
// $cards = 'Player 1:
// 43
// 19

// Player 2:
// 2
// 29
// 14';
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

function doGame(array $game) : array
{
	$gameArray = [];

	do
	{
		// Rule 1: Check to see if we've seen this combination before, if so
		// player 0 insantly wins.
		$roundString = '';
		foreach ($game as $player)
		{
			$roundString .= join(',', iterator_to_array($player));
			$roundString .= ':';

			if (array_key_exists($roundString, $gameArray))
			{
				return [0, $game];
			}

			$gameArray[$roundString] = true;
		}

		$playerCards = [];
		$canIRecurse = true;
		foreach ($game as $playerNumber => $player)
		{
			$playerCards[$playerNumber] = $player->shift();
			$canIRecurse &= count($player) >= $playerCards[$playerNumber];
		}

		if ($canIRecurse)
		{
			$recurseGame = [];
			foreach ($game as $playerKey => $player)
			{
				for ($i = 0; $i < $playerCards[$playerKey]; $i++)
				{
					if (! array_key_exists($playerKey, $recurseGame))
					{
						$recurseGame[$playerKey] = new SplDoublyLinkedList;
					}

					$recurseGame[$playerKey]->push($game[$playerKey][$i]);
				}
			}
			[$winningPlayer] = doGame($recurseGame);

			// Assumes two players
			$losingPlayer = ($winningPlayer + 1) % 2;

			$game[$winningPlayer]->push($playerCards[$winningPlayer]);
			$game[$winningPlayer]->push($playerCards[$losingPlayer]);
		}
		else
		{
			arsort($playerCards);

			$winningPlayer = key($playerCards);

			foreach ($playerCards as $card)
			{
				$game[$winningPlayer]->push($card);
			}
		}

		$game = array_filter($game, function(SplDoublyLinkedList $list) : bool { return count($list) !== 0; });
	}
	while (count($game) !== 1);

	return [$winningPlayer, $game];
}

[$winner, $game] = doGame($game);

$winner = array_shift($game);
$winner->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
$i = 1;
$total = 0;
foreach ($winner as $card)
{
	$total += ($card * $i++);
}

echo $total, "\n";
