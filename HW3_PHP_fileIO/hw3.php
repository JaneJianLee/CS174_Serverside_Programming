<?php
echo<<<_END
	<html>
	<head>
		<title>[CS174]HW3 Ji An Lee</title>
	</head>
	<body>
		<form method='post' action='hw3.php' enctype='multipart/form-data'>
			<p><label>[CS174][Fall 2019] HW3 Ji An Lee</label></p>
			Select a file (.txt) to upload.<br/> 
			It must exactly be a 1000-byte file, containing only integers (0~9), no new line feed or spaces.
			<p><input type='file' name='userfile' size='1000'></p>
			<p><input type='submit' value='Upload File'></p>
		</form>	<br/>
		<form action="hw3.php" method="post">
   		<input type="submit" name="testfunction" value="CLICK TO RUN TEST FUNCTIONS" />
		</form>
_END;

define('FILESIZE', 1000);
try{ 
    if(isset($_FILES['userfile'])){
       
        echo "<br/>#################FYI: User file upload validation test cases#################<br/>";
        
        echo "Case1) User had not selected a file to upload <br/>";
        echo "Expected Behavior : No file was uploaded <br/>";

        echo "Case2) Uploaded file content is LESS than 1000 characters<br/>";
        echo "Expected Behavior : [ERROR] Sorry, you need to input exactly 1000 integers. Check your input again<br/>";

        echo "Case3) Uploaded file content is MORE than 1000 characters<br/>";
        echo "Expected Behavior : [ERROR] Sorry, you need to input exactly 1000 integers. Check your input again<br/>";
        
        echo "Case4) Uploaded file content is not a .txt file <br/>";
        echo "Expected Behavior : [ERROR] Wrong file type. Upload .txt file only. <br/>";
        
        echo "Case5) If the file contains characters which are not integers(0~9) or decimals(.) <br/>";
        echo "Expected Behavior : [ERROR] The file you have entered has 1000 characters but contains non-integer numbers.<br/>";
        
        echo "Case6) If the file contains decimals(.)<br/>";
        echo "Expected Behavior : [ERROR] There is a decimal point in your input. Check again.<br/>";
        
        echo "Case7) Uploaded Valid file type, file contents <br/>";
	echo "Expected Behavior : You have successfully uploaded file. Computation will now begin!<br/>";
	echo "###########################################################	<br/><br/><br/>";
	
	$files_error = htmlentities($_FILES['userfile']['error']);
        switch ($files_error){
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file was uploaded..');
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('The uploaded file exceeds the upload_max_filesize directive in php.ini');
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form (1000 bytes)');
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new Exception('Missing a temporary folder');            
            case UPLOAD_ERR_CANT_WRITE:
                throw new Exception('Failed to write file to disk.');
            default:
                throw new Exception('Unknown errors. Try again with a different file.');
        }
        
#Error Case 1: If the file contains more/less than 1000 characters, abort.
	$userfilesize =	htmlentities($_FILES['userfile']['size']); 
        if($userfilesize!= FILESIZE){
            throw new Exception('[ERROR] Sorry, You need to input exactly 1000 integers. Check your input again');
        }

#Error Case 2: If the uploaded file is not a .txt file, abort.
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$file_tmp_name = htmlentities($_FILES['userfile']['tmp_name']);
        $fmime = finfo_file($finfo,$file_tmp_name);

        if($fmime!='text/plain'){
            finfo_close($finfo);
            throw new Exception('[ERROR] Wrong file type. Upload .txt file only.');
        }
        finfo_close($finfo);

#Error Case 3: Unable to read file
        $file = file_get_contents($file_tmp_name,FALSE,NULL,0,FILESIZE);
        if(!$file){
            throw new Exception('[ERROR][file_get_contents] Could not get file content. File does not exist / No permission');
        }

#Error Case 4: If the file contains characters which are not integers(0~9) or decimals(.), abort.
        if(!is_numeric($file)){
            throw new Exception('[ERROR] The file you have entered has 1000 characters but contains non-integer numbers.');
        }

#Error Case 4: If the file contains decimals(.), abort.
        if(strpos($file,'.')){
            throw new Exception('[ERROR] There is a decimal point in your input. Check again. \n');
        }
    
        $file_real_name= htmlentities($_FILES['userfile']['name']);
        echo"You have successfully uploaded : ( $file_real_name ) <br/> Computation will now begin! \n\n<br/><br/>";

        $max_pro = max_product($file);
        sum_factorial($max_pro);
    }
}
catch(Exception $e) {
    echo $e->getMessage();
}

$servertype = htmlentities($_SERVER['REQUEST_METHOD']);
if($servertype == "POST" and isset($_POST['testfunction'])){
        testfunction();
}

function max_product($string){
	$i=0;
	$arr= str_split($string);
	$global_max=0;
	$curr_max=0;
    $gn0=0;$gn1=0;$gn2=0;$gn3=0;$gn4=0;
    
    if(strlen($string)!=FILESIZE){
	    exit("[ERROR] Input string length is not valid. provide 1000 numbers");
	}
    
	while($i<FILESIZE){
		#num0
		if($arr[$i]==0){
			$i = $i +1;
			continue;
		}
		else $n0=$arr[$i];
		#num1
		if(($i+1)<FILESIZE){
			if($arr[$i+1]==0){
				$i = $i+2;
				continue;
			}
			$n1=$arr[$i+1];
		}
		else break;
		#num2
		if(($i+2)<FILESIZE){
			if($arr[$i+2]==0){
				$i = $i+3;
				continue;
			}
			$n2=$arr[$i+2];
		}
		else break;
		#num3
		if(($i+3)<FILESIZE){
			if($arr[$i+3]==0){
				$i = $i+4;
				continue;
			}
			$n3=$arr[$i+3];
		}
		else break;
		#num4
		if(($i+4)<FILESIZE){
			if($arr[$i+4]==0){
				$i = $i+5;
				continue;
			}
			$n4=$arr[$i+4];
		}
		else break;

		$curr_max=$n0*$n1*$n2*$n3*$n4;
		if($curr_max>$global_max)
		{
			$global_max=$curr_max;
			$gn0=$n0;
			$gn1=$n1;
			$gn2=$n2;
			$gn3=$n3;
			$gn4=$n4;
		}
		$i=$i+1;
	}

	echo "Which combination of 5 adjacent numbers produce the maximum product?<br/> ";
	echo ">> Maximum Combination = $gn0,$gn1,$gn2,$gn3,$gn4\n<br/>";
	echo ">> Maximum product: $global_max\n<br/><br/>";
	return $global_max;
}

function sum_factorial($num){
    if(!is_int($num)){
        exit("[ERROR] Cannot process factorial of non-integers <br/>");
    }
    if($num<0){
        exit("[ERROR] Cannot process factorial of negative numbers <br/>");
    }
    
	$result=0;
	$broken_num=str_split($num);
	echo"Calculate the sum of each digits' factorial. Number = $num<br/>";
	foreach ($broken_num as $value){
		$result = $result + factorial($value);
	}
	echo ">> Sum of Factorials of digits: $result \n<br/><br/>";
	return $result;
}

function factorial($num){
    if($num<0){
        exit("[ERROR] Cannot process factorial of negative numbers <br/>");
    }
	elseif($num==0 or $num==1){
        return 1;
	}
	else{
		return $num*factorial($num-1);
	}
}

function testfunction(){
    
    echo "<br/>###Testing general-purpose use case of factorial(), sum_factorial(), and max_product()### <br/><br/><br/>";

    echo "###Test1 factorial()###<br/>";
    //ERROR CASE1: factorial(negative numbers)
    //echo "#Case1) factorial(-4) --> Expected: Error cannot process negative numbers  <br/>";
    //$result=factorial(-5);

    //Valid-input Test Cases:
    echo "#Case1) factorial(0) --> Expected 1 <br/>";
    $result=factorial(0);
    echo">> $result <br/>";
    echo "#Case2) factorial(1) --> Expected 1  <br/>";
    $result=factorial(1);
    echo">> $result <br/>";
    echo "#Case3) factorial(3) --> Expected 6  <br/>";
    $result=factorial(3);
    echo">> $result <br/>";
    echo "#Case4) factorial(5) --> Expected 120  <br/>";
    $result=factorial(5);
    echo">> $result <br/>";


    echo "<br/><br/> ###Test2 sum_factorial()###<br/>";
    //ERROR CASE1: factorial(negative numbers)
    //echo "#Case1) factorial(-4) --> Expected: Error, Cannot process sum_factorial of non-integers  <br/>";
    //$result=factorial(-5);
    //ERROR CASE2: factorial(non-int values)
    //echo "#Case1) factorial("5") --> Expected: Error, Cannot process sum_factorial of negative numbers  <br/>";
    //$result=factorial("5");

    //Valid-input Test Cases:
    echo "#Case1) factorial(0) --> Expected 2 <br/>";
    $result=sum_factorial(10);
    echo "#Case2) factorial(3) --> Expected 9  <br/>";
    $result=sum_factorial(321);
    echo "#Case3) factorial(5) --> Expected 123  <br/>";
    $result=sum_factorial(502);

    echo "<br/>###Test3 max_product()###<br/>";
    //ERROR CASE1: max_product(not1000ints) 
    //echo "#Case1) max_product(997numbers) --> Expected : Error, Input string length is not valid. provide 1000 numbers <br/>";
    //$error997_0="1234511111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    //$result= max_product($error997_0);

    echo "#Case1) max_product(123451111..11) --> Expected: multiply(1,2,3,4,5) = 120  <br/>";
    $test1000_0="1234511111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    max_product($test1000_0);

    echo "#Case2) max_product(99999..11) --> Expected: multiply(9,9,9,9,9) = 59049  <br/>";
    $test1000_1="9999911111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    max_product($test1000_1);

    echo "#Case3) max_product(1111111..11) --> Expected: multiply(1,1,1,1,1) = 1 <br/>";
    $test1000_2="1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    max_product($test1000_2);

    echo "#Case4) max_product(..,8,9,9,9,9,..) --> Expected: multiply(8,9,9,9,9) = 52488 <br/>";
    $test1000_3='2181909081690770185052321049073392528999924958672356046979453769097983912367487855997402230561679880542387228889476850757737903607452063202252829852127046858625482692134198162429337001213577726116908514664635423331205215134470848367512925400704661962580640890664313117800554013231403860240550610524655649572535981360631143029583799192850575819154351245176888467406304265633870396173612682403840754456841644298060110287113917114252479830620174706903243809203049661422791000976453738604137464292930226583201111085002438702815407882499588263992563046189620898667510771765446559576354161712001831396179158158533578592450585371366756756642127368204077726989838950243201253814677820216507909032478230899050913490525809389618055860332910938263179965000817268232190913458126042096800350082094543536798259445697189931530086908424199765192095035655162721956973504323864332410511227743379323054099943786203444437559203423208005792744145904043741861237453880119726203444437559203423208005792797262034145904520176';
    max_product($test1000_3);
}
echo "</body></html>";
?>
