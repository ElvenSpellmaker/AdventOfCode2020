<?php

$passports = rtrim(file_get_contents('d4.txt'));

$passports = explode("\n\n", $passports);

$validPassports = 0;
foreach ($passports as $passport)
{
	preg_match_all("%([a-z]{3}):%", $passport, $matches);

	$fields = $matches[1];

	$validPassports += (count($fields) === 8 || (count($fields) === 7 && array_search('cid', $fields) === false));
}

echo $validPassports, "\n";
