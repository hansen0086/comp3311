#!/usr/bin/php
<?php
    $num1=0;
    $num2 = 1;
    $string = "hello";
    $array = [$num1];
    $array[] = $num2;
    $array[] = $string;
    $i =0;
    while($i< count($array)){
        echo "$array[$i]\n";
        $i++;
    }
?>