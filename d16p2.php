<?php

$tickets = rtrim(file_get_contents('d16.txt'));
// $tickets = "class: 0-1 or 4-19
// row: 0-5 or 8-19
// seat: 0-13 or 16-19

// your ticket:
// 11,12,13

// nearby tickets:
// 3,9,18
// 15,1,5
// 5,14,9";
[$rules, $myTicket, $nearbyTickets] = explode("\n\n", $tickets);

$rules = explode("\n", $rules);
$myTicket = explode("\n", $myTicket);
$nearbyTickets = explode("\n", $nearbyTickets);

array_shift($myTicket);
array_shift($nearbyTickets);

$myTicket = explode(',', $myTicket[0]);

foreach ($nearbyTickets as &$nearbyTicket)
{
	$nearbyTicket = explode(',', $nearbyTicket);
}

unset($nearbyTicket);

$rulesArray = [];
foreach ($rules as $rule)
{
	preg_match('%(.+): (\d+)-(\d+) or (\d+)-(\d+)%', $rule, $matches);

	$rulesArray[] = [
		'rule' => $matches[1],
		'min1' => $matches[2],
		'max1' => $matches[3],
		'min2' => $matches[4],
		'max2' => $matches[5],
	];
}

/**
 * @var array $nearbyTickets
 */
foreach ($nearbyTickets as $nearbyTicketKey => $nearbyTicket)
{
	foreach ($nearbyTicket as $number)
	{
		$valid = false;
		foreach ($rulesArray as ['min1' => $min1, 'max1' => $max1, 'min2' => $min2, 'max2' => $max2])
		{
			if (($number >= $min1 && $number <= $max1) || ($number >= $min2 && $number <= $max2))
			{
				$valid = true;
				break;
			}
		}

		if (! $valid)
		{
			unset($nearbyTickets[$nearbyTicketKey]);
		}
	}
}

function findRuleName(array $rule, array $values) : ?string
{
	['rule' => $ruleName, 'min1' => $min1, 'max1' => $max1, 'min2' => $min2, 'max2' => $max2] = $rule;

	foreach ($values as $value)
	{
		if (! (($value >= $min1 && $value <= $max1) || ($value >= $min2 && $value <= $max2)))
		{
			return null;
		}
	}

	return $ruleName;
}

// Work out which rules could be which columns
$ruleMap = [];
foreach ($rulesArray as $rule)
{
	for ($i = 0; $i < count($rulesArray); $i++)
	{
		$values = array_column($nearbyTickets, $i);
		$ruleName = findRuleName($rule, $values);

		if ($ruleName !== null)
		{
			$ruleMap[$rule['rule']][$i] = $i;
		}
	}
}

function compareCounts(array $a, array $b) : bool
{
	return count($a) <=> count($b);
}

array_multisort(array_map('count', $ruleMap), SORT_ASC, $ruleMap);

$finalRuleMap = [];
foreach($ruleMap as $ruleKey => $_)
{
	// Gotta indirectly reference the array as it's modified
	$shift = array_shift($ruleMap[$ruleKey]);
	unset($ruleMap[$ruleKey]);

	$finalRuleMap[$ruleKey] = $shift;

	// Clear the current column from all other rules.
	foreach ($ruleMap as $subRuleKey => $subRule)
	{
		unset($ruleMap[$subRuleKey][$shift]);
	}
}

$product = 1;
foreach ($finalRuleMap as $finalRuleKey => $finalRule)
{
	if (strpos($finalRuleKey, 'departure') !== 0)
	{
		continue;
	}

	// echo "Key: ", $finalRuleKey, "Val: ", $myTicket[$finalRule], "\n";
	$product *= $myTicket[$finalRule];
}

echo $product, "\n";
