<?php

$instructions = rtrim(file_get_contents('d8.txt'));

// $instructions = "nop +0
// acc +1
// jmp +4
// acc +3
// jmp -3
// acc -99
// acc +1
// jmp -4
// acc +6";

$instructions = explode("\n", $instructions);

$seenInstructions = [];

$accumulator = 0;
$instruction = 0;
while (true)
{
	$currentInstruction = $instructions[$instruction];
	if (array_key_exists($instruction, $seenInstructions))
	{
		break;
	}

	$seenInstructions[$instruction] = true;


	[$currentInstruction, $amount] = explode(" ", $currentInstruction);

	// echo $currentInstruction, ": ", $amount, "\n";
	// sleep(1);

	switch ($currentInstruction)
	{
		case 'acc':
			$accumulator += $amount;
		break;
		case 'jmp':
			$instruction += $amount;
			continue 2;
		case 'nop';
			// Do nothing
		break;
	}

	$instruction++;
}

echo $accumulator, "\n";
