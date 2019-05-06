#!/usr/bin/php
<?php
// include the common PHP code file
require("a2.php");
// PROGRAM BODY BEGINS
$usage = "Usage: $argv[0] name name";
$db = dbConnect(DB_CONNECTION);
// Check arguments
if (count($argv) < 3) exit("$usage\n");
//get ids of 2 actors
$q1 = "select id from actor where lower(name) = lower(%s)";
$r1 = dbQuery($db, mkSQL($q1, $argv[1]));
$t = dbNext($r1);
$actor1_id = $t[0];
$q1 = "select id from actor where lower(name) = lower(%s)";
$r1 = dbQuery($db, mkSQL($q1, $argv[2]));
$t = dbNext($r1);
$actor2_id = $t[0];
//echo $actor1_id." ".$actor2_id."\n";
//set a empty to determine wheather output is is empty or not
$empty = 1; //1:empty 0:not
//1st degree:
$time = time();
echo "$time\n";
$q1 = "create or replace view shortest_1st_degree as select a1.actor_id as actor1, a1.movie_id as movie1_2, a2.actor_id as actor2 from acting a1 left join acting a2 on a1.movie_id = a2.movie_id where a1.actor_id = %d and a2.actor_id <> %d";
$r1 = dbQuery($db, mkSQL($q1, $actor1_id, $actor1_id));
//get if res contians the solution
$q1 = "select * from shortest_1st_degree where actor2 = %d";
$r1 = dbQuery($db, mkSQL($q1, $actor2_id));
//transfer ids to names and titles
for ($i = 1; $t = dbNext($r1); $i++) {
$q2 = "select name from actor where id = %d";
    $r2 = dbQuery($db, mkSQL($q2, $t[0]));
    $temp = dbNext($r2);
    $actor1 = $temp[0];
    $results = "$actor1 was in";
    //1
    $q2 = "select title, year from movie where id = %d";
    $r2 = dbQuery($db, mkSQL($q2, $t[1]));
    $temp = dbNext($r2);
    $movie12 = $temp[0];
    $results .= " $movie12";
    $year12 = $temp[1];
    if (!empty($year12))
        $results .= " ($year12)";
    $q2 = "select name from actor where id = %d";
    $r2 = dbQuery($db, mkSQL($q2, $t[2]));
    $temp = dbNext($r2);
    $actor2 = $temp[0];
    $results .= " with $actor2";
    $strs[] = "$results\n";
    $empty = 0;
}
//sort and print
if (!$empty) { 
    sort($strs);
    $i=1;
    foreach($strs as $string){
        echo "$i. $string";
        $i++;
    }
}else{
    //2nd degree
$time = time();
echo "$time\n";
    $q1 = "create or replace view shortest_2nd_degree as select shortest_1st_degree.*, a1.movie_id as movie2_3, a2.actor_id as actor3 from shortest_1st_degree left join acting a1 on actor2 = a1.actor_id left join acting a2 on a1.movie_id = a2.movie_id where movie1_2 != a1.movie_id and actor2 != a2.actor_id";
    $r1 = dbQuery($db, mkSQL($q1));
    //get if res contians the solution
    $q1 = "select * from shortest_2nd_degree where actor3 = %d";
    $r1 = dbQuery($db, mkSQL($q1, $actor2_id));
    //transfer ids to names and titles
    for ($i = 1; $t = dbNext($r1); $i++) {
        $q2 = "select name from actor where id = %d";
        $r2 = dbQuery($db, mkSQL($q2, $t[0]));
        $temp = dbNext($r2);
        $actor1 = $temp[0];
        $results = "$actor1 was in";
        //1
        $q2 = "select title, year from movie where id = %d";
        $r2 = dbQuery($db, mkSQL($q2, $t[1]));
        $temp = dbNext($r2);
        $movie12 = $temp[0];
        $results .= " $movie12";
        $year12 = $temp[1];
        if (!empty($year12))
            $results .= " ($year12)";
        $q2 = "select name from actor where id = %d";
        $r2 = dbQuery($db, mkSQL($q2, $t[2]));
        $temp = dbNext($r2);
        $actor2 = $temp[0];
        $results .= " with $actor2";
        //2
        $q2 = "select title, year from movie where id = %d";
        $r2 = dbQuery($db, mkSQL($q2, $t[3]));
        $temp = dbNext($r2);
        $movie23 = $temp[0];
        $results .= "; $actor2 was in $movie23";
        $year23 = $temp[1];
        if (!empty($year23))
            $results .= " ($year23)";
        $q2 = "select name from actor where id = %d";
        $r2 = dbQuery($db, mkSQL($q2, $t[4]));
        $temp = dbNext($r2);
        $actor3 = $temp[0];
        $results .= " with $actor3";
        $strs[] = "$results\n";
        $empty = 0;
    }
    //sort and print
    if (!$empty) { 
        sort($strs);
        $i=1;
        foreach($strs as $string){
            echo "$i. $string";
            $i++;
        }
    }else{
        //3rd degree
$time = time();
echo "$time\n";
        $q1 = "create or replace view shortest_3rd_degree as select shortest_2nd_degree.*, a1.movie_id as movie3_4, a2.actor_id as actor4 from shortest_2nd_degree left join acting a1 on actor3 = a1.actor_id left join acting a2 on a1.movie_id = a2.movie_id where movie2_3 != a1.movie_id and actor3 != a2.actor_id";
        $r1 = dbQuery($db, mkSQL($q1));
        //get if res contians the solution
        $q1 = "select * from shortest_3rd_degree where actor4 = %d";
        $r1 = dbQuery($db, mkSQL($q1, $actor2_id));
        //transfer ids to names and titles
        for ($i = 1; $t = dbNext($r1); $i++) {
            $q2 = "select name from actor where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[0]));
            $temp = dbNext($r2);
            $actor1 = $temp[0];
            $results = "$actor1 was in";
            //1
            $q2 = "select title, year from movie where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[1]));
            $temp = dbNext($r2);
            $movie12 = $temp[0];
            $results .= " $movie12";
            $year12 = $temp[1];
            if (!empty($year12))
                $results .= " ($year12)";
            $q2 = "select name from actor where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[2]));
            $temp = dbNext($r2);
            $actor2 = $temp[0];
            $results .= " with $actor2";
            //2
            $q2 = "select title, year from movie where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[3]));
            $temp = dbNext($r2);
            $movie23 = $temp[0];
            $results .= "; $actor2 was in $movie23";
            $year23 = $temp[1];
            if (!empty($year23))
                $results .= " ($year23)";
            $q2 = "select name from actor where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[4]));
            $temp = dbNext($r2);
            $actor3 = $temp[0];
            $results .= " with $actor3";
            //3
            $q2 = "select title, year from movie where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[5]));
            $temp = dbNext($r2);
            $movie34 = $temp[0];
            $results .= "; $actor3 was in $movie34";
            $year34 = $temp[1];
            if (!empty($year34))
                $results .= " ($year34)";
            $q2 = "select name from actor where id = %d";
            $r2 = dbQuery($db, mkSQL($q2, $t[6]));
            $temp = dbNext($r2);
            $actor4 = $temp[0];
            $results .= " with $actor4";
            $strs[] = "$results\n";
            $empty = 0;
        }
        //sort and print
        if (!$empty) { 
            sort($strs);
            $i=1;
            foreach($strs as $string){
                echo "$i. $string";
                $i++;
            }
        }else{
            //4th degree
$time = time();
echo "$time\n";
            $q1 = "create or replace view shortest_4th_degree as select shortest_3rd_degree.*, a1.movie_id as movie4_5, a2.actor_id as actor5 from shortest_3rd_degree left join acting a1 on actor4 = a1.actor_id left join acting a2 on a1.movie_id = a2.movie_id where movie3_4 != a1.movie_id and actor4 != a2.actor_id";
            $r1 = dbQuery($db, mkSQL($q1));
            //get if res contians the solution 
            $q1 = "select * from shortest_4th_degree where actor5 = %d";
            $r1 = dbQuery($db, mkSQL($q1, $actor2_id));
            //transfer ids to names and titles
            for ($i = 1; $t = dbNext($r1); $i++) {
                $q2 = "select name from actor where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[0]));
                $temp = dbNext($r2);
                $actor1 = $temp[0];
                $results = "$actor1 was in";
                //1
                $q2 = "select title, year from movie where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[1]));
                $temp = dbNext($r2);
                $movie12 = $temp[0];
                $results .= " $movie12";
                $year12 = $temp[1];
                if (!empty($year12))
                    $results .= " ($year12)";
                $q2 = "select name from actor where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[2]));
                $temp = dbNext($r2);
                $actor2 = $temp[0];
                $results .= " with $actor2";
                //2
                $q2 = "select title, year from movie where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[3]));
                $temp = dbNext($r2);
                $movie23 = $temp[0];
                $results .= "; $actor2 was in $movie23";
                $year23 = $temp[1];
                if (!empty($year23))
                    $results .= " ($year23)";
                $q2 = "select name from actor where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[4]));
                $temp = dbNext($r2);
                $actor3 = $temp[0];
                $results .= " with $actor3";
                //3
                $q2 = "select title, year from movie where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[5]));
                $temp = dbNext($r2);
                $movie34 = $temp[0];
                $results .= "; $actor3 was in $movie34";
                $year34 = $temp[1];
                if (!empty($year34))
                    $results .= " ($year34)";
                $q2 = "select name from actor where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[6]));
                $temp = dbNext($r2);
                $actor4 = $temp[0];
                $results .= " with $actor4";
                //4
                $q2 = "select title, year from movie where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[7]));
                $temp = dbNext($r2);
                $movie45 = $temp[0];
                $results .= "; $actor4 was in $movie45";
                $year45 = $temp[1];
                if (!empty($year45))
                    $results .= " ($year45)";
                $q2 = "select name from actor where id = %d";
                $r2 = dbQuery($db, mkSQL($q2, $t[8]));
                $temp = dbNext($r2);
                $actor5 = $temp[0];
                $results .= " with $actor5";
                $strs[] = "$results\n";
                $empty = 0;
            }
            
            if (!$empty) { 
                sort($strs);
                $i=1;
                foreach($strs as $string){
                    echo "$i. $string";
                    $i++;
                }
            }else{
                //5th degree
$time = time();
echo "$time\n";
                $q1 = "create or replace view shortest_5th_degree as select shortest_4th_degree.*, a1.movie_id as movie5_6, a2.actor_id as actor6 from shortest_4th_degree left join acting a1 on actor5 = a1.actor_id left join acting a2 on a1.movie_id = a2.movie_id where movie4_5 != a1.movie_id and actor5 != a2.actor_id";
                $r1 = dbQuery($db, mkSQL($q1));
                //get if res contians the solution
$time = time();
echo "-2 $time\n";
                $q1 = "select * from shortest_5th_degree where actor6 = %d";
                $r1 = dbQuery($db, mkSQL($q1, $actor2_id));
$time = time();
echo "-1 $time\n";
                //transfer ids to names and titles
                for ($i = 1; $t = dbNext($r1); $i++) {
                    $q2 = "select name from actor where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[0]));
                    $temp = dbNext($r2);
                    $actor1 = $temp[0];
                    $results = "$actor1 was in";
                    //1
                    $q2 = "select title, year from movie where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[1]));
                    $temp = dbNext($r2);
                    $movie12 = $temp[0];
                    $results .= " $movie12";
                    $year12 = $temp[1];
                    if (!empty($year12))
                        $results .= " ($year12)";
                    $q2 = "select name from actor where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[2]));
                    $temp = dbNext($r2);
                    $actor2 = $temp[0];
                    $results .= " with $actor2";
                    //2
                    $q2 = "select title, year from movie where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[3]));
                    $temp = dbNext($r2);
                    $movie23 = $temp[0];
                    $results .= "; $actor2 was in $movie23";
                    $year23 = $temp[1];
                    if (!empty($year23))
                        $results .= " ($year23)";
                    $q2 = "select name from actor where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[4]));
                    $temp = dbNext($r2);
                    $actor3 = $temp[0];
                    $results .= " with $actor3";
                    //3
                    $q2 = "select title, year from movie where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[5]));
                    $temp = dbNext($r2);
                    $movie34 = $temp[0];
                    $results .= "; $actor3 was in $movie34";
                    $year34 = $temp[1];
                    if (!empty($year34))
                        $results .= " ($year34)";
                    $q2 = "select name from actor where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[6]));
                    $temp = dbNext($r2);
                    $actor4 = $temp[0];
                    $results .= " with $actor4";
                    //4
                    $q2 = "select title, year from movie where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[7]));
                    $temp = dbNext($r2);
                    $movie45 = $temp[0];
                    $results .= "; $actor4 was in $movie45";
                    $year45 = $temp[1];
                    if (!empty($year45))
                        $results .= " ($year45)";
                    $q2 = "select name from actor where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[8]));
                    $temp = dbNext($r2);
                    $actor5 = $temp[0];
                    $results .= " with $actor5";
                    //5
                    $q2 = "select title, year from movie where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[9]));
                    $temp = dbNext($r2);
                    $movie56 = $temp[0];
                    $results .= "; $actor5 was in $movie56";
                    $year56 = $temp[1];
                    if (!empty($year56))
                        $results .= " ($year56)";
                    $q2 = "select name from actor where id = %d";
                    $r2 = dbQuery($db, mkSQL($q2, $t[10]));
                    $temp = dbNext($r2);
                    $actor6 = $temp[0];
                    $results .= " with $actor6";
                    $strs[] = "$results\n";
                    $empty = 0;
                }
$time = time();
echo "0 $time\n";
                if (!$empty) { 
                    sort($strs);
                    $i=1;
                    foreach($strs as $string){
                        echo "$i. $string";
                        $i++;
                    }
                }else{
                    //6th degree
$time = time();
echo "1 $time\n";
                    $q1 = "create or replace view shortest_6th_degree as select shortest_5th_degree.*, a1.movie_id as movie6_7, a2.actor_id as actor7 from shortest_5th_degree left join acting a1 on actor6 = a1.actor_id left join acting a2 on a1.movie_id = a2.movie_id where movie5_6 != a1.movie_id and actor6 != a2.actor_id";
                    $r1 = dbQuery($db, mkSQL($q1));
$time = time();
echo "2 $time\n";
                    //get if res contians the solution
                    $q1 = "select * from shortest_6th_degree where actor7 = %d";
                    $r1 = dbQuery($db, mkSQL($q1, $actor2_id));
$time = time();
echo "3 $time\n";
                    //transfer ids to names and titles
                    for ($i = 1; $t = dbNext($r1); $i++) {
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[0]));
                        $temp = dbNext($r2);
                        $actor1 = $temp[0];
                        $results = "$actor1 was in";
                        //1
                        $q2 = "select title, year from movie where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[1]));
                        $temp = dbNext($r2);
                        $movie12 = $temp[0];
                        $results .= " $movie12";
                        $year12 = $temp[1];
                        if (!empty($year12))
                            $results .= " ($year12)";
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[2]));
                        $temp = dbNext($r2);
                        $actor2 = $temp[0];
                        $results .= " with $actor2";
                        //2
                        $q2 = "select title, year from movie where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[3]));
                        $temp = dbNext($r2);
                        $movie23 = $temp[0];
                        $results .= "; $actor2 was in $movie23";
                        $year23 = $temp[1];
                        if (!empty($year23))
                            $results .= " ($year23)";
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[4]));
                        $temp = dbNext($r2);
                        $actor3 = $temp[0];
                        $results .= " with $actor3";
                        //3
                        $q2 = "select title, year from movie where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[5]));
                        $temp = dbNext($r2);
                        $movie34 = $temp[0];
                        $results .= "; $actor3 was in $movie34";
                        $year34 = $temp[1];
                        if (!empty($year34))
                            $results .= " ($year34)";
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[6]));
                        $temp = dbNext($r2);
                        $actor4 = $temp[0];
                        $results .= " with $actor4";
                        //4
                        $q2 = "select title, year from movie where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[7]));
                        $temp = dbNext($r2);
                        $movie45 = $temp[0];
                        $results .= "; $actor4 was in $movie45";
                        $year45 = $temp[1];
                        if (!empty($year45))
                            $results .= " ($year45)";
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[8]));
                        $temp = dbNext($r2);
                        $actor5 = $temp[0];
                        $results .= " with $actor5";
                        //5
                        $q2 = "select title, year from movie where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[9]));
                        $temp = dbNext($r2);
                        $movie56 = $temp[0];
                        $results .= "; $actor5 was in $movie56";
                        $year56 = $temp[1];
                        if (!empty($year56))
                            $results .= " ($year56)";
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[10]));
                        $temp = dbNext($r2);
                        $actor6 = $temp[0];
                        $results .= " with $actor6";
            
                        //5
                        $q2 = "select title, year from movie where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[11]));
                        $temp = dbNext($r2);
                        $movie67 = $temp[0];
                        $results .= "; $actor6 was in $movie67";
                        $year67 = $temp[1];
                        if (!empty($year67))
                            $results .= " ($year67)";
                        $q2 = "select name from actor where id = %d";
                        $r2 = dbQuery($db, mkSQL($q2, $t[12]));
                        $temp = dbNext($r2);
                        $actor7 = $temp[0];
                        $results .= " with $actor7";
                        $strs[] = "$results\n";
                        $empty = 0;
                    }
                    sort($strs);
                    $i=1;
                    foreach($strs as $string){
                        echo "$i. $string";
                        $i++;
                    }
$time = time();
echo "4 $time\n";
                }
            }  
        }
    }
}
?>