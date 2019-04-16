#!/usr/bin/php
<?php
   $array[0]=0;
   $array[1]=1;
   $array[2]=2;
   $array[3]=3;

   unset($array[1]);
   foreach($array as $num){
       echo "$num\n";
   }
?>