<?php

$file = rtrim(file_get_contents('d19.txt'));
// $file = '0: 4 1 5
// 1: 2 3 | 3 2
// 2: 4 4 | 5 5
// 3: 4 5 | 5 4
// 4: "a"
// 5: "b"

// ababbb
// bababa
// abbbab
// aaabbb
// aaaabbb';
[$rules, $input] = explode("\n\n", $file);

$rules = explode("\n", $rules);
$input = explode("\n", $input);

$ruleList = [];
foreach ($rules as $rule)
{
	[$key, $value] = explode(': ', $rule);
	if (strpos($value, '"') === 0)
	{
		$value = substr($value, 1, strlen($value) - 2);
	}
	$ruleList[$key] = $value;
}

function buildRegex(string $rule, array $ruleList) : string
{
	$newRule = '(?:';
	$rule = explode(' ', $rule);
	foreach ($rule as $part)
	{
		switch ($part)
		{
			case 'a':
			case 'b':
				return $part;
			break;
			case '|':
				$newRule .= '|';
			break;
			default:
				$newRule .= buildRegex($ruleList[$part], $ruleList);
			break;
		}
	}

	$newRule .= ')';

	return $newRule;
}

$mainRule = $ruleList[0];
$regex = '%^';
$regex .= buildRegex($mainRule, $ruleList);
$regex .= '$%';

$validMessages = array_filter($input, function(string $inputLine) use ($regex) {
	return preg_match($regex, $inputLine) !== 0;
});

echo count($validMessages), "\n";
