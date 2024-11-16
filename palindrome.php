<?php

function isPalindrome($number){
    $originalNb = $number;
    $reversedNb =0;

    while ($number>0){
        $digit = $number % 10;
        $reversedNb = $reversedNb * 10 + $digit ;
        $number = (int) ($number / 10);
    }

    return $reversedNb === $originalNb;
}

if ($_SERVER['REQUEST_METHOD']==='POST'){
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['number'])){
        $numberToCheck = $inputData['number'];

        if (is_numeric($numberToCheck)){
            $result = isPalindrome((int) $numberToCheck);
            echo json_encode([
                'number' => $numberToCheck,
                'isPalindrome' => $result ? true : false
            ]);
        } else {
            echo json_encode(['error' => 'Invalid input. Please provide a numeric value.']);
        }
    } else{
        echo json_encode(['error' => 'No number provided.']);
    }
} 

