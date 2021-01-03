<?php

ini_set('memory_limit', '250M');

$input = '253149867';
// $input = '389125467';

$cups = str_split($input);

$cups = array_merge($cups, range(10, 1000000));

$numberofCups = count($cups);

class Node
{
	public $value;
	public $next;

	public function __construct(string $value)
	{
		$this->value = $value;
	}
}

class SpecialLinkedList
{
	/**
	 * @var Node
	 */
	public $startNode;

	/**
	 * @var Node
	 */
	public $endNode;

	/**
	 * @var Node[]
	 */
	public $nodeList;

	public function __construct(array $data)
	{
		foreach ($data as $datum)
		{
			$this->push(new Node($datum));
		}
	}

	public function push(Node $node)
	{
		if ($this->startNode === null)
		{
			$this->startNode = $node;
		}

		if ($this->endNode === null)
		{
			$this->endNode = $node;
		}

		$this->endNode->next = $node;
		$this->endNode = $node;
		$this->endNode->next = null;

		$this->nodeList[$node->value] = $node;
	}

	public function insertAtValue(string $value, Node $node)
	{
		$insertNode = $this->nodeList[$value];

		$nextNode = $insertNode->next;

		$insertNode->next = $node;
		$node->next = $nextNode;
	}

	public function shift() : Node
	{
		$startNode = $this->startNode;

		$this->startNode = $startNode->next;
		$this->endNode->next = $startNode;

		return $startNode;
	}
}

$ll = new SpecialLinkedList($cups);

$seenOrders = [];

$cycles = 10000000;
while ($cycles--)
{
	$currentCup = $ll->shift();

	try{
	$takeCup1 = $ll->shift();
	}catch(TypeError $e) { unset($ll->nodeList); var_dump($ll); exit;}
	$takeCup2 = $ll->shift();
	$takeCup3 = $ll->shift();

	$findCup = $currentCup->value;

	do
	{
		$findCup--;

		$findCup = (string)$findCup;

		if ($findCup <= 0)
		{
			$findCup = $numberofCups;
		}
	}
	while ($findCup === $takeCup1->value || $findCup === $takeCup2->value || $findCup === $takeCup3->value);

	$ll->push($currentCup);

	$ll->insertAtValue($findCup, $takeCup3);
	$ll->insertAtValue($findCup, $takeCup2);
	$ll->insertAtValue($findCup, $takeCup1);

	// $sn = $ll->startNode;
	// do
	// {
	// 	echo $sn->value, ",";
	// 	$sn = $sn->next;
	// }
	// while ($sn !== null);

	// echo "\n";
}

$node1 = $ll->nodeList['1'];
$first = $node1->next ?? $ll->startNode;
$second = $first->next ?? $ll->startNode;

// echo  $first->value, ', ', $second->value, "\n";

echo $first->value * $second->value, "\n";
