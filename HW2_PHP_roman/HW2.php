<?php
function roman2int($roman){
    $result = 0;
    $romans = array(
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    );
    //
    if(empty($roman)){
        echo"[ERROR] Empty Input. Check again.";
        return null;
    }
    else if(is_numeric($roman)){
        echo"[ERROR] Numbers(0~9) in Input. Check again.";
        return null;
    }   
    
    foreach ($romans as $item => $value) {
        while (strpos($roman, $item) === 0) {
            $result = $result+$value;
            $roman = substr($roman, strlen($item));
        }
    }
    if(empty($roman)==0){
        echo"[ERROR] Unrecognized Character in input string. Check again";
        return null;
    }
    unset($item,$value);
    
    foreach ($romans as $item => $value) {
        while (strpos($roman, $item) === 0) {
            echo"here!>$roman\n";
            $result = $result+$value;
            $roman = substr($roman, strlen($item));
        }
    }
    unset($item,$value);
    
    echo $result;
    return $result;
}

roman2int('');
echo"\n";
roman2int('HVI');
echo"\n";
roman2int('VI');
echo"\n";
roman2int('HVI1');
echo"\n";
roman2int('VI1');
echo"\n";
roman2int('1');
echo"\n";
roman2int('IV');
echo"\n";
roman2int('MCMXC');
echo"\n";
roman2int('IX');
echo"\n";
roman2int('MMMCMXC');
echo"\n";
roman2int('XXXIV');
echo"\n";
roman2int('MLXXII');
echo"\n";

?>