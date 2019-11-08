<?php

    require_once 'login.php';
    $err_flag=0;
    
    if (!isset($_SERVER['PHP_AUTH_USER'])){
        header('WWW-Authenticate: Basic realm="Admin log-in"');
	header('HTTP/1.0 401 Unauthorized');
	echo "You chose not to authenticate as Admin.<br/>";
    }
    else{
        //Connect to database
        $cn = connect_db($hn,$un,$pw,$db);
        if(!$cn){
            error_db();
        }
        else{
            //Admin Authentication
            $un_temp = sanitizeSQL($cn,$_SERVER['PHP_AUTH_USER']);
            $pw_temp = sanitizeSQL($cn,$_SERVER['PHP_AUTH_PW']);
            
            $querydb = "SELECT * FROM Admin WHERE Username='$un_temp'";
            $result = $cn->query($querydb);
            if(!result){
                error_db();
            }
            else{
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $result->close();
                $salt1=$row['Salt'];
                $token = hash('ripemd128', "$salt1$pw_temp");
                if($token != $row['Password']){
                    //Admin authentication fail
                    echo "You have failed to authenticate as Admin.<br/>";
                    $cn->close();
                    exit;
                }
                else{
                    //Admin authentication success
    echo<<<_END
        <html>
        <head>
            <title>[CS174]Midterm2 Ji An Lee</title>
        </head>
        <body>
            <form method="post" action="" enctype="multipart/form-data">
                <p><label>[CS174][Fall 2019] HW6 Ji An Lee</label></p>
                -- [Admin] --<br></p>
                --  Select file (.txt) to upload.<br></p>
                File Upload : <input type="file" name="file"></p>
                Malware Name : <input type="text" name="filename"><br></p>
                <p><input type="submit" name="Admin" value="Upload Malware"></p>
_END;
                    //Malware uploaded from Admin
                    if((isset($_FILES['file']))&&(!empty($_POST['filename']))){
                        //Check file type : .txt only!
                        $tmp_name = sanitizeStr($_FILES['file']['tmp_name']);
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $fmime = finfo_file($finfo,$tmp_name);
                        if($fmime!='text/plain'){
                            finfo_close($finfo);
                            echo '[ERROR] Wrong file type. Upload .txt file only.<br/>';
                            exit;
                        }
                        finfo_close($finfo);
                        //Read Malware signature (20bytes) from file
                        $fp=fopen($tmp_name, "r");
                        $first20bytes=fread($fp,"20");
                        fclose($fp);
                        
                        //Sanitize input and add (name, signature) to DB
                        $signature = sanitizeSQL($cn, $first20bytes);
                        $sig_name = sanitizeSQL($cn, $_POST['filename']);
                        $check=add_db($cn, $sig_name, $signature);
                        }
                    }
                }
            $cn->close();                 
            }      
    }
    
    function connect_db($hn, $un, $pw, $db){
        $conn = new mysqli($hn,$un, $pw, $db);
        if (mysqli_connect_error()) {
        error_db();
            return null;
        }
        return $conn;
    }

    function add_db($conn, $name, $sig){
        $insertdb = "INSERT INTO Malware (Name, Signature) VALUES "."('$name', '$sig')";
        $result = $conn->query($insertdb);
        if(!$result){
            echo "[Err] Couldn't add Malware to DB. (might already exist).<br/>";
            $result->close();
        }
        echo "Successfully added $name to DB!<br/>";
        $result->close();
    }

    function sanitizeStr($string){
        if(get_magic_quotes_gpc()){
            $string = stripslashes($string);
        }
        $string = strip_tags($string);
        $string = htmlentities($string);
        return $string;
    }
    function sanitizeSQL($connection,$str){
        $str = $connection->real_escape_string($str);
        $str = sanitizeStr($str);
        return $str;
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
