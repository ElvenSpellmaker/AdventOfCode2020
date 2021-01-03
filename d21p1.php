<?php

$ingredients = rtrim(file_get_contents('d21.txt'));
// $ingredients = 'mxmxvkd kfcds sqjhc nhms (contains dairy, fish)
// trh fvjkl sbzzf mxmxvkd (contains dairy)
// sqjhc fvjkl (contains soy)
// sqjhc mxmxvkd sbzzf (contains fish)';
$ingredients = explode("\n", $ingredients);

$ingredientsList = [];
$allergenSet = [];
foreach ($ingredients as $ingredientLine)
{
	$ingredientLine = explode(' ', $ingredientLine);

	$amIngredient = true;
	$seenIngredients = [];
	foreach ($ingredientLine as $ingredientOrAllergen)
	{
		$ingredientOrAllergen = rtrim($ingredientOrAllergen, ',)');

		if ($ingredientOrAllergen === '(contains')
		{
			$amIngredient = false;
			continue;
		}

		if ($amIngredient)
		{
			$seenIngredients[$ingredientOrAllergen] = true;
			@$ingredientsList[$ingredientOrAllergen]++;
		}
		else
		{
			$allergenSet[$ingredientOrAllergen][] = $seenIngredients;
		}
	}
}

foreach ($allergenSet as $allergen => $ingredients)
{
	$potentialIngredients = array_intersect_key($allergenSet[$allergen][0], ...$allergenSet[$allergen]);

	foreach ($potentialIngredients as $potentialIngredient => $_)
	{
		unset($ingredientsList[$potentialIngredient]);
	}
}
echo array_sum($ingredientsList), "\n";
