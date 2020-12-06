<?php

$questions = rtrim(file_get_contents('d6.txt'));

$questions = explode("\n\n", $questions);

$sum = 0;

foreach ($questions as $question)
{
	$questionLength = strlen($question);
	$yesQuestions = [];
	for ($i = 0; $i < $questionLength; $i++)
	{
		$char = $question[$i];

		if ($char === "\n")
		{
			continue;
		}

		$yesQuestions[$char] = true;
	}

	$sum += count($yesQuestions);
}

echo $sum, "\n";
