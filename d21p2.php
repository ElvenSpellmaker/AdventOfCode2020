<?php

$ingredients = rtrim(file_get_contents('d21.txt'));
// $ingredients = 'mxmxvkd kfcds sqjhc nhms (contains dairy, fish)
// trh fvjkl sbzzf mxmxvkd (contains dairy)
// sqjhc fvjkl (contains soy)
// sqjhc mxmxvkd sbzzf (contains fish)';
$ingredients = explode("\n", $ingredients);

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
		}
		else
		{
			$allergenSet[$ingredientOrAllergen][] = $seenIngredients;
		}
	}
}

$potentialAllergens = [];
foreach ($allergenSet as $allergen => $ingredients)
{
	// Workaround for a single element array, just use the first element twice.
	$potentialIngredients = array_intersect_key($allergenSet[$allergen][0], ...$allergenSet[$allergen]);

	$potentialAllergens[$allergen] = $potentialIngredients;
}

$knownAllergens = [];
while (count($potentialAllergens))
{
	foreach ($potentialAllergens as $allergen => $potentialIngredients)
	{
		if (count($potentialIngredients) === 1)
		{
			$ingredient = key($potentialIngredients);
			unset($potentialAllergens[$allergen]);
			$knownAllergens[$allergen] = $ingredient;

			foreach ($potentialAllergens as $removalKey => $_)
			{
				unset($potentialAllergens[$removalKey][$ingredient]);
			}
		}
	}
}

ksort($knownAllergens);

echo join(',', $knownAllergens), "\n";
