<?php

function IsPrime($check){

$flag=0;
    for($i=2;$i<$check;$i++){
        if($check%$i==0){
            return $flag;
        }
    }
$flag=1;
return $flag;

}

function Solution($num){
   $result = [];
    if($num <=0){
        echo "Prime numbers up to N = $num, [Error] Cannot input 0 or negative numbers.\n";
        return $result;
    }

    echo "Prime numbers up to N = $num is :";
    
    for($i=2;$i<=$num;$i++)
    {
        if(IsPrime($i)==1){
            echo "$i ";
            array_push($result,$i);
        }
    }
    echo "\n";
    
    return $result;
}

function tester_function($test_arr, $test_num){
    echo"\n\nTesting Solution($test_num) \n\n";
    $compare = Solution($test_num);
    
    echo"Given test array is :";
    for($i=0; $i<count($test_arr); $i++){
        echo"$test_arr[$i] ";
    }
    echo"\n";
    echo"IsPrime function output is :";
    for($i=0; $i<count($compare); $i++){
        echo"$compare[$i] ";
    }
    echo"\n";
     echo"Is output from prime_solution($test_num) equal to given subset?\n";
    if($compare == $test_arr){
        echo"[Result] >> Yes, Test Pass \n";
    }
    else{
        echo "[Result] >> No, Test Fail \n";
    }

}
//...HW1-1...
//Function which prints prime numbers up to give N --> (Solution(N))
Solution(-1);
Solution(2);
Solution(10);
Solution(100);

//...HW1-2...
//Test cases for Solution(N)
$test1 = []; #When given N<=0
$test2 = array(2,3,5,7,11,13,17,19,23,29); #N=30, test result should return "true"
$test3 = array(1,2,3,5,7); #N=10, test result should return "false" because of test1[0]

tester_function($test1,0);
tester_function($test2,30);
tester_function($test3,10);
?>

