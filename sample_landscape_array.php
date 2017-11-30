<?php



$data = array(
	'phase1'=>createProducts(),
	'phase2'=>createProducts(),
);

$json = json_encode($data);
echo '<pre>';
print_r($data);

function createProducts($count="") {

	if ($count == "") {
		$count = rand(1,50);
	}

	$data = array();
	for ($i=0;$i<$count;$i++) {
		$data[$i] = array(
			substr(md5(uniqid()),0,6),
			generateRandomString(rand(3,8)),
			generateRandomString(rand(12,25), true)
		);
	}
	return $data;
}

function generateRandomString($length = 10, $withspace = false) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

	if ($withspace) {
		$randomString = str_split($randomString, (int)($length/2));
		$randomString = implode(" ",$randomString);
	}
	
    return $randomString;
}
