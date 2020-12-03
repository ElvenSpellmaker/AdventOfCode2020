<?php

$expenseReport = rtrim(file_get_contents('d1.txt'));

$expenseReport = explode("\n", $expenseReport);

// $expenseReport = [20, 1000, 1000];

foreach ($expenseReport as $expenseIndex => $expense)
{
	$searchNumber = 2020 - $expense;

	unset($expenseReport[$expenseIndex]);
	$expenseReportCopy = $expenseReport;

	foreach ($expenseReportCopy as $expenseCopyIndex => $expenseCopy)
	{
		$finalSearchNumber = $searchNumber - $expenseCopy;

		unset($expenseReportCopy[$expenseCopyIndex]);

		$thirdExpense = array_search($finalSearchNumber, $expenseReportCopy);

		if ($thirdExpense !== false)
		{
			break 2;
		}
	}
}

if ($thirdExpense === false)
{
	throw new RuntimeException("Panic I failed!");
}

echo $expense * $expenseCopy * $expenseReport[$thirdExpense], "\n";
