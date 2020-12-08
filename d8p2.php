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

class Result
{
	public $valid = false;
	public $accumulator = 0;
}

function runProgramme(array $instructions) : Result
{
	$result = new Result;
	$seenInstructions = [];

	$accumulator = 0;
	$instruction = 0;

	while ($instruction < count($instructions))
	{
		$currentInstruction = $instructions[$instruction];
		if (array_key_exists($instruction, $seenInstructions))
		{
			// We don't care about the accumulator as it's invalid but might as well set it!
			$result->accumulator = $accumulator;
			return $result;
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

	$result->valid = true;
	$result->accumulator = $accumulator;

	return $result;
}

foreach ($instructions as $instructionKey => $instruction)
{
	if (preg_match('%^nop|jmp%', $instruction, $matches) === 0)
	{
		continue;
	}

	$cloneInstructions = $instructions;
	$cloneInstructions[$instructionKey] = str_replace(['nop', 'jmp'], ['jmp', 'nop'], $cloneInstructions[$instructionKey]);

	$result = runProgramme($cloneInstructions);

	if ($result->valid === true)
	{
		break;
	}
}

echo $result->accumulator, "\n";
