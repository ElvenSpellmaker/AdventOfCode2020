<?php

$questions = rtrim(file_get_contents('d6.txt'));

$questions = explode("\n\n", $questions);

$sum = 0;

foreach ($questions as $question)
{
	$questionLength = strlen($question);
	$yesQuestions = [];
	$count = 0;
	for ($i = 0, $j = 0; $i < $questionLength; $i++)
	{
		$char = $question[$i];

		if ($char === "\n")
		{
			$j++;

			continue;
		}

		$yesQuestions[$j][$char] = true;
	}

	$groupSize = count($yesQuestions);
	$questionRange = range('a', 'z');
	foreach ($questionRange as $questionLetter)
	{
		$peopleAnsweredYes = array_column($yesQuestions, $questionLetter);

		if (count($peopleAnsweredYes) === $groupSize)
		{
			$sum += 1;
		}
	}
}

echo $sum, "\n";
