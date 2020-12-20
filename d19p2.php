<?php

$file = rtrim(file_get_contents('d19.txt'));
// $file = '42: 9 14 | 10 1
// 9: 14 27 | 1 26
// 10: 23 14 | 28 1
// 1: "a"
// 11: 42 31
// 5: 1 14 | 15 1
// 19: 14 1 | 14 14
// 12: 24 14 | 19 1
// 16: 15 1 | 14 14
// 31: 14 17 | 1 13
// 6: 14 14 | 1 14
// 2: 1 24 | 14 4
// 0: 8 11
// 13: 14 3 | 1 12
// 15: 1 | 14
// 17: 14 2 | 1 7
// 23: 25 1 | 22 14
// 28: 16 1
// 4: 1 1
// 20: 14 14 | 1 15
// 3: 5 14 | 16 1
// 27: 1 6 | 14 18
// 14: "b"
// 21: 14 1 | 1 14
// 25: 1 1 | 1 14
// 22: 14 14
// 8: 42
// 26: 14 22 | 1 20
// 18: 15 15
// 7: 14 5 | 1 21
// 24: 14 1

// abbbbbabbbaaaababbaabbbbabababbbabbbbbbabaaaa
// bbabbbbaabaabba
// babbbbaabbbbbabbbbbbaabaaabaaa
// aaabbbbbbaaaabaababaabababbabaaabbababababaaa
// bbbbbbbaaaabbbbaaabbabaaa
// bbbababbbbaaaaaaaabbababaaababaabab
// ababaaaaaabaaab
// ababaaaaabbbaba
// baabbaaaabbaaaababbaababb
// abbbbabbbbaaaababbbbbbaaaababb
// aaaaabbaabaaaaababaa
// aaaabbaaaabbaaa
// aaaabbaabbaaaaaaabbbabbbaaabbaabaaa
// babaaabbbaaabaababbaabababaaab
// aabbbbbaabbbaaaaaabbbbbababaaaaabbaaabba';
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

function buildRegex(string $rule, array $ruleList, string $rule42 = '', string $rule31 = '') : string
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
				$newRule .= buildRegex($ruleList[$part], $ruleList, $rule42, $rule31);
			break;
		}
	}

	$newRule .= ')';

	return $newRule;
}

$rule42 = buildRegex($ruleList[42], $ruleList);
$rule31 = buildRegex($ruleList[31], $ruleList);

$validMessages = array_filter($input, function(string $inputLine) use ($rule42, $rule31) {
	// $inputLine = 'aabbbbbaabbbaaaaaabbbbbababaaaaabbaaabba';
	// $inputLine = 'abbbbbabbbaaaababbaabbbbabababbbabbbbbbabaaaa';
	$num42Matches = 0;
	while(preg_match("%^$rule42%", $inputLine, $matches) !== 0)
	{
		// echo $num42Matches, ': ', $inputLine, "\n";
		$num42Matches++;
		// echo "42-$num42Matches: " . $matches[0], "\n";
		$inputLine = substr($inputLine, strlen($matches[0]));
	}

	if ($num42Matches < 2)
	{
		return false;
	}

	$num31Matches = 0;
	while(preg_match("%^$rule31%", $inputLine, $matches) !== 0)
	{
		// echo $num31Matches, ': ', $inputLine, "\n";
		$num31Matches++;
		// echo "31-$num31Matches: " . $matches[0], "\n";
		$inputLine = substr($inputLine, strlen($matches[0]));
	}

	if ($num31Matches === 0 || strlen($inputLine) !== 0)
	{
		return false;
	}

	return ($num42Matches - $num31Matches > 0);
});

// var_dump($validMessages);

echo count($validMessages), "\n";
