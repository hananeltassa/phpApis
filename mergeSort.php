<?php

function merge($left, $right) {
    $result = [];
    $i = 0;
    $j = 0;

    while ($i < count($left) && $j < count($right)) {
        if ($left[$i] <= $right[$j]) {
            $result[] = $left[$i];
            $i++;
        } else {
            $result[] = $right[$j];
            $j++;
        }
    }

    return array_merge($result, array_slice($left, $i), array_slice($right, $j));
}

function mergeSort($arr) {
    if (count($arr) <= 1) {
        return $arr;
    }

    $mid = floor(count($arr) / 2);
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);

    return merge(mergeSort($left), mergeSort($right));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents("php://input"), true);
    
    if (isset($inputData['array'])) {
        $arrayToSort = $inputData['array'];
        
        $sortedArray = mergeSort($arrayToSort);

        echo json_encode(['sortedArray' => $sortedArray]);
    } else {
        echo json_encode(['error' => 'Invalid input. Provide an array of numbers.']);
    }
} 
