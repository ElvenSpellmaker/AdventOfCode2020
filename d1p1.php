<?php

$expenseReport = rtrim(file_get_contents('d1.txt'));

$expenseReport = explode("\n", $expenseReport);

foreach ($expenseReport as $expenseIndex => $expense)
{
	$searchNumber = 2020 - $expense;

	unset($expenseReport[$expenseIndex]);

	$secondExpense = array_search($searchNumber, $expenseReport);

	if ($secondExpense !== false)
	{
		break;
	}
}

echo $expense * $expenseReport[$secondExpense], "\n";
