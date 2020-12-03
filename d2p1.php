<?php

$passwords = rtrim(file_get_contents('d2.txt'));

$passwords = explode("\n", $passwords);

foreach ($passwords as $passwordKey => $passwordLine)
{
	preg_match('%(\d+)-(\d+) ([a-z]): (.*)%', $passwordLine, $matches);

	$minSeen = $matches[1];
	$maxSeen = $matches[2];
	$seeCharacter = $matches[3];
	$password = $matches[4];

	$seen = 0;
	$passwordLength = strlen($password);
	for ($i = 0; $i < $passwordLength; $i++)
	{
		if ($password[$i] === $seeCharacter)
		{
			$seen++;
		}

		if ($seen > $maxSeen)
		{
			break;
		}
	}

	if ($seen < $minSeen || $seen > $maxSeen)
	{
		unset($passwords[$passwordKey]);
	}
}

echo count($passwords), "\n";
