<?php

$passports = rtrim(file_get_contents('d4.txt'));

$passports = explode("\n\n", $passports);

$validPassports = 0;
foreach ($passports as $passport)
{
	preg_match_all("%([a-z]{3}):([^\s]+)%", $passport, $matches);

	$fields = $matches[1];
	$values = $matches[2];

	if (count($fields) === 8 || (count($fields) === 7 && array_search('cid', $fields) === false))
	{
		$validPassport = true;
		foreach ($fields as $fieldKey => $field)
		{
			switch ($field)
			{
				case 'byr':
					$validPassport = $values[$fieldKey] >= 1920 && $values[$fieldKey] <= 2002;
				break;
				case 'iyr':
					$validPassport = $values[$fieldKey] >= 2010 && $values[$fieldKey] <= 2020;
				break;
				case 'eyr':
					$validPassport = $values[$fieldKey] >= 2020  && $values[$fieldKey] <= 2030;
				break;
				case 'hgt':
					$heightMatch = preg_match('%^([0-9]{2,3})(cm|in)$%', $values[$fieldKey], $fieldMatches);

					if($heightMatch === 0)
					{
						$validPassport = false;
						break 2;
					}

					$amount = $fieldMatches[1];
					$unit = $fieldMatches[2];
					$validPassport = ($unit === 'cm' && ($amount >= 150 && $amount <= 193))
						|| ($unit === 'in' && ($amount >= 59 && $amount <= 76));
				break;
				case 'hcl':
					$validPassport = preg_match('%^#[a-z0-9]{6}$%', $values[$fieldKey]) !== 0;
				break;
				case 'ecl':
					$validPassport = preg_match('%^amb|blu|brn|gry|grn|hzl|oth$%', $values[$fieldKey]) !== 0;
				break;
				case 'pid':
					$validPassport = preg_match('%^[0-9]{9}$%', $values[$fieldKey]) !== 0;
				break;
				case 'cid':
					// Intentionally empty, no validation.
				break;
			}

			if ($validPassport === false)
			{
				break;
			}
		}

		$validPassports += $validPassport;
	}
}

echo $validPassports, "\n";
