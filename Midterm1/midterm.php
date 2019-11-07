<?php

echo<<<_END
	<html>
	<head>
		<title>[CS174]HW3 Ji An Lee</title>
	</head>
	<body>
		<form method='post' action='midterm.php' enctype='multipart/form-data'>
			<p><label>[CS174][Fall 2019] midterm Ji An Lee</label></p>
			Select a file (.txt) to upload.<br/> 
			It must exactly be a 400-byte file, containing only integers (0~9) with no new line or any spaces in between.
			<p><input type='file' name='userfile'></p>
			<p><input type='submit' value='Upload File'></p>
		</form>	<br/>
		<form action="midterm.php" method="post">
   		<input type="submit" name="testfunction" value="CLICK TO RUN TEST FUNCTIONS" />
		</form>
_END;

define('FILESIZE',400);
define('ARRSIZE',20);

try{ 
    if(isset($_FILES['userfile'])){
       
        echo "<br/>#################FYI: User file upload validation test cases#################<br/>";
        
        echo "Case1) User had not selected a file to upload <br/>";
        echo "Expected Behavior : No file was uploaded <br/>";

        echo "Case2) Uploaded file content is LESS than 400 characters<br/>";
        echo "Expected Behavior : [ERROR] Sorry, you need to input exactly 400 integers. Check your input again<br/>";

        echo "Case3) Uploaded file content is MORE than 400 characters<br/>";
        echo "Expected Behavior : [ERROR] Sorry, you need to input exactly 400 integers. Check your input again<br/>";
        
        echo "Case4) Uploaded file content is not a .txt file <br/>";
        echo "Expected Behavior : [ERROR] Wrong file type. Upload .txt file only. <br/>";
        
        echo "Case5) If the file contains characters which are not integers(0~9) or decimals(.) <br/>";
        echo "Expected Behavior : [ERROR] The file you have entered has 400 characters but contains non-integer numbers.<br/>";
        
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
                break;
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('The uploaded file exceeds the upload_max_filesize directive in php.ini');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form (400 bytes)');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new Exception('Missing a temporary folder');                
                break;
            case UPLOAD_ERR_CANT_WRITE:
                throw new Exception('Failed to write file to disk.');
                break;
            default:
                throw new Exception('Unknown errors. Try again with a different file.');
                break;
        }
        
#Error Case 1: If the file contains more/less than 400 characters, abort.
	$userfilesize =	htmlentities($_FILES['userfile']['size']); 
        if($userfilesize!= FILESIZE){
            throw new Exception('[ERROR] Sorry, You need to input exactly 400 integers. Check your input again');
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
            throw new Exception('[ERROR] The file you have entered has 400 characters but contains non-integer numbers.');
        }

#Error Case 4: If the file contains decimals(.), abort.
        if(strpos($file,'.')){
            throw new Exception('[ERROR] There is a decimal point in your input. Check again. ');
        }
    
        $file_real_name= htmlentities($_FILES['userfile']['name']);
        echo"You have successfully uploaded : ( $file_real_name ) <br/> Computation will now begin! \n\n<br/><br/>";

        //set array from entire file string
        $arrayall=setarr($file);
        //compute the array for maximum product of 4 numbers
        if($arrayall){
            $global_max=max_product($arrayall);
        }
    }
}
catch(Exception $e) {
    echo $e->getMessage();
}

$servertype = htmlentities($_SERVER['REQUEST_METHOD']);
if($servertype == "POST" and isset($_POST['testfunction'])){
        testfunction();
}

function setarr($string){
    if(strlen($string)!=FILESIZE)
    {
	    echo"Error in parsing file string. string must be a size of 400 integers.</br></br>";
	    return NULL;
    }
    $count=0;
    for($i=0;$i<ARRSIZE;$i++){
        $arr[$i]=substr($string,$count,ARRSIZE);
        $count=$count+ARRSIZE;
        $arr[$i]=str_split($arr[$i]);
    }
    return $arr;
}

function max_product($arr){
        $gmax = 0;
        $cmax = 0;
	$g0=$g1=$g2=$g3=0;
	$direction="";
        for ($i=0;$i<ARRSIZE;$i++){
            for ($j=0;$j<ARRSIZE;$j++){
                if($arr[$i][$j] == 0){
			continue;
                }
                //1. Check ➔
                if(($j+3)<ARRSIZE){
                    $cmax = $arr[$i][$j]*$arr[$i][$j+1]*$arr[$i][$j+2]*$arr[$i][$j+3];
                    if($cmax>$gmax)
                    {
                        $gmax=$cmax;
                        $g0=$arr[$i][$j];
                        $g1=$arr[$i][$j+1];
                        $g2=$arr[$i][$j+2];
                        $g3=$arr[$i][$j+3];
			$direction= "➔";
		    }
                }
                //2. Check ↓
                if(($i+3)<ARRSIZE){
                    $cmax = $arr[$i][$j]*$arr[$i+1][$j]*$arr[$i+2][$j]*$arr[$i+3][$j];
                    if($cmax>$gmax)
                    {
                        $gmax=$cmax;
                        $g0=$arr[$i][$j];
                        $g1=$arr[$i+1][$j];
                        $g2=$arr[$i+2][$j];
                        $g3=$arr[$i+3][$j];
		        $direction="↓";	
		    }
                }
                //3. Check ↘
                if((($i+3)<ARRSIZE) && (($j+3)<ARRSIZE)){
                    $cmax = $arr[$i][$j]*$arr[$i+1][$j+1]*$arr[$i+2][$j+2]*$arr[$i+3][$j+3];
                    if($cmax>$gmax)
                    {
                        $gmax=$cmax;
                        $g0=$arr[$i][$j];
                        $g1=$arr[$i+1][$j+1];
                        $g2=$arr[$i+2][$j+2];
                        $g3=$arr[$i+3][$j+3];
		    	$direction="↘";
		    }            
                }
                //4. Check ↙
                if((($i+3)<ARRSIZE) && (($j-3)>=0)){
                    $cmax = $arr[$i][$j]*$arr[$i+1][$j-1]*$arr[$i+2][$j-2]*$arr[$i+3][$j-3];
                    if($cmax>$gmax)
                    {
                        $gmax=$cmax;
                        $g0=$arr[$i][$j];
                        $g1=$arr[$i+1][$j-1];
                        $g2=$arr[$i+2][$j-2];
                        $g3=$arr[$i+3][$j-3];
		    	$direction="↙";
		    }      
                }     
            }

        }
  
    echo"\n </br> Maximum product is: $gmax </br> The four numbers are : ($g0, $g1, $g2, $g3) in direction : $direction \n </br></br> ";   
    return $gmax;
}
function testfunction(){
    
    echo "<br/>###Testing max_product() ### <br/><br/><br/>";
    
    //ERROR CASE1: max_product(not 400 ints) 
    echo "#Error Case1) max_product(397numbers) --> Expected : Error, Input string length is not valid. provide 400 numbers <br/>";
    $error397_0="1234511111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    $arrayerr=setarr($error397_0);
    if($arrayerr){
	    max_product($arrayerr);
    }
   
   
    echo "#Case1) max_product ➔ --> Expected: multiply(2,3,4,5) = 120";
    
    $test400_0="1234511111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    $array0=setarr($test400_0);
    if($array0){
	    max_product($array0);
    }

   
    echo "#Case2) max_product ↓ --> Expected: multiply(9,9,9,9) = 6561";
    $test400_1="9111111111111111111191111111111111111111911111111111111111119111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    $array1=setarr($test400_1);
    if($array1){
	    max_product($array1);
    }

   
    echo "#Case3) max_product ↘ --> Expected: multiply(9,9,9,9) = 6561";
    $test400_2="9111111111111111111119111111111111111111119111111111111111111119111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    $array2=setarr($test400_2);
    if($array2){
	    max_product($array2);
    }


    echo "#Case4) max_product ↙ --> Expected: multiply(9,9,9,9) = 6561";
    $test400_3="1119111111111111111111911111111111111111191111111111111111119111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    $array3=setarr($test400_3);
    if($array3){
	    max_product($array3);
    }

}
echo "</body></html>";
?>
