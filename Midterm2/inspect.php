<?php
    require_once 'login.php';
    $err_flag=0;
    define('MINSIZE',20);

echo<<<_END
	<html>
	<head>
		<title>[CS174]HW3 Ji An Lee</title>
	</head>
	<body>
		<form method='post' enctype='multipart/form-data'>
			<p><label>[CS174][Fall 2019] HW3 Ji An Lee</label></p>
			Select a file (.txt) to upload.<br/> 
			<p><input type='file' name='userfile'></p>
			<p><input type='submit' value='Inspect File'></p>
		</form>
_END;
    if(isset($_FILES['userfile'])){
        //Check file type (.txt only)
            $tmp_name = sanitizeStr($_FILES['userfile']['tmp_name']);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fmime = finfo_file($finfo,$tmp_name);
            if($fmime!='text/plain'){
                finfo_close($finfo);
                echo '[ERROR] Wrong file type. Upload .txt file only.<br/>';
                exit;
        }
        finfo_close($finfo);
        //Check file size (>20)
        $filesize = sanitizeStr($_FILES['userfile']['size']);
        if ($filesize < MINSIZE){
            echo 'Filesize is too small to be infected with malware.';
            exit;
        }
        //Get file contents
        $tmp_content = file_get_contents($tmp_name,FALSE,NULL,0,$filesize);
        $file_content = sanitizeStr($tmp_content);
        if(!$file_content){
            echo '[ERROR][file_get_contents] Could not get file content. File does not exist / No permission<br/>';
            exit;
        }
        $flag=0;
        $cn = connect_db($hn, $un, $pw, $db);
        if($cn){
            $fetchdb = "SELECT * FROM Malware";
            $result = $cn->query($fetchdb);
            if(!result){
                error_db();
                exit;
            }
            //Get all signatures from DB
            $rows = $result->num_rows;
            for ($i=0; $i<$rows; $i++){
                $result->data_seek($i);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                //Set up target
                $target= $row['Signature'];
                $mal_name = $row['Name'];
                
                //For each signature in DB, search entire user's input file for a match
                for($str_end =(MINSIZE-1); $str_end<=$filesize; $str_end++){
                    $tmp = substr($file_content,($str_end-MINSIZE-1),MINSIZE);
                    
                    //If there's a match in the input file, alert.
                    if($tmp == $target){
                        echo "Uploaded file is infected with $mal_name.<br/>";
                        echo "Signature : ($target) at index($str_end) of input file.<br/>";
                        $flag=1;
                        break 2;
                    }
                }
            }
            $result->close();
            $cn->close(); 
        }
        if($flag==0){
            echo "Uploaded file is not infected with malware in the DB.<br/>";
        }
    }
    
    function connect_db($hn, $un, $pw, $db){
        $con = new mysqli($hn,$un, $pw, $db);
        if (mysqli_connect_error()) {
        error_db();
            return null;
        }
        return $con;
    }
    function sanitizeStr($string){
        if(get_magic_quotes_gpc()){
            $string = stripslashes($string);
        }
        $string = strip_tags($string);
        $string = htmlentities($string);
        return $string;
    }

    function error_db(){
        global $err_flag;
        if($err_flag==0){
            echo "uh-oh, Something went wrong!<br/>";
            $err_flag=1;
        }
    }    
    
echo "</body></html>";
?>

