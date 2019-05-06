#!/usr/bin/php
<?php
require("a2.php");
//$usage = "Usage: $argv[0] ActorName";
$db = dbConnect(DB_CONNECTION);



$i=0;

$q1 = "select distinct student from enrolments order by student";
$q2 = "select count(*) from enrolments where student = %s";
$r1 = dbQuery($db, mkSQL($q1));
while ($t1 = dbNext($r1)) {
    $i++;
    $s = $t1["student"];
    $r2 = dbQuery($db, mkSQL($q2, $s));
    while ($t2 = dbNext($r2)) {
        $i++;
        $m = $t2[0];};
    printf("%-10s %2d\n",$s,$m);
}

echo "$i\n";
?>