<?php
header("Content-Type: application/json");


function countVowels($str) {
    $vowels = ['a', 'e', 'i', 'o', 'u'];
    $count = 0;

    $str = strtolower($str);
    
    for ($i = 0; $i < strlen($str); $i++) {
        if (in_array($str[$i], $vowels)) {
            $count++;
        }
    }
    return $count;
}


function hasTwoVowels($str) {
    return countVowels($str) === 2;
}


class Node {
    public $value;
    public $next;

    public function __construct($value) {
        $this->value = $value;
        $this->next = null;
    }
}


class LinkedList {
    public $head;

    public function __construct() {
        $this->head = null;
    }

    
    public function addNode($value) {
        $newNode = new Node($value);

        if ($this->head === null) {
            $this->head = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $newNode;
        }
    }


    public function getTwoVowelNodes() {
        $current = $this->head;
        $result = [];

        while ($current !== null) {
            if (hasTwoVowels($current->value)) {
                $result[] = $current->value;
            }
            $current = $current->next;
        }
        return $result;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['nodes']) && is_array($inputData['nodes'])) {
        $list = new LinkedList();

        foreach ($inputData['nodes'] as $nodeValue) {
            $list->addNode($nodeValue);
        }

        $twoVowelNodes = $list->getTwoVowelNodes();

        echo json_encode([
            'nodesWithTwoVowels' => $twoVowelNodes
        ]);
    } else {
        echo json_encode(['error' => 'Invalid input.']);
    }
} 

