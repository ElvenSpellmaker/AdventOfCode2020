<?php

$passwords = rtrim(file_get_contents('d2.txt'));

$passwords = explode("\n", $passwords);

foreach ($passwords as $passwordKey => $passwordLine)
{
	preg_match('%(\d+)-(\d+) ([a-z]): (.*)%', $passwordLine, $matches);

	$firstPosition = $matches[1] - 1;
	$secondPosition = $matches[2] - 1;
	$seeCharacter = $matches[3];
	$password = $matches[4];

	$seeFirst = $password[$firstPosition] === $seeCharacter;
	$seeSecond = $password[$secondPosition] === $seeCharacter;

	if (! $seeFirst xor $seeSecond)
	{
		unset($passwords[$passwordKey]);
	}
}

echo count($passwords), "\n";
