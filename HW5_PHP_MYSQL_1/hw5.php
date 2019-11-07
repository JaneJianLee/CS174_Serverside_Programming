<?php

require_once 'login.php';
define('FILESIZE',250);
define('NAMESIZE',25);
$set = 0;
$err_flag=0;

echo<<<_END
	<html>
	<head>
		<title>[CS174]HW5 Ji An Lee</title>
	</head>
	<body>
        <form method="post" action="hw5.php" enctype="multipart/form-data">
            <p><label>[CS174][Fall 2019] HW5 Ji An Lee</label></p>
            Type a name (~25 char) and select a file (.txt) to upload.<br/> 
            The file should only contain a single email address (max length ~250 characters). <br/></p>
            Name : <input type="text" name="name"><br></p>
            File : <input type="file" name="userfile"></p>
            <p><input type="submit" value="Add to Database"></p>
        </form>	<br/>
_END;

try{
    if(isset($_FILES['userfile']) && (!empty($_POST['name']))){
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
                throw new Exception('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form (250 bytes)');
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

        #Error Case 1: If the file contains more/less than 250 characters, abort.
        $userfilesize =	htmlentities($_FILES['userfile']['size']); 
        if($userfilesize > FILESIZE){
            throw new Exception('[ERROR] Sorry, Your input file cannot exeed 250 bytes. Check your input again');
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
        $string = file_get_contents($file_tmp_name,FALSE,NULL,0,FILESIZE);
        if(!$string){
            throw new Exception('[ERROR][file_get_contents] Could not get file content. File does not exist / No permission');
        }
	$name = htmlentities($_POST['name']);
	if(strlen($name)>NAMESIZE){
	    throw new Exception('[ERROR] Name length should be less than 25 characters<p>');
	}
	else{
		add_db($name,$string,$hn, $un, $pw, $db);
	}
    }
}
catch(Exception $e) {
    echo $e->getMessage();
}
//Print database only once.
if($set==0){
	fetch_db($hn, $un, $pw, $db);
}

//Connect to db
function connect_db($ihn, $iun, $ipw, $idb){
    
    $conn = new mysqli($ihn,$iun, $ipw, $idb);
    if (mysqli_connect_error()) {
	error_db();
        return null;
    }
    return $conn;
}

//Add record to db
function add_db($name, $content, $hn0, $un0, $pw0, $db0){

    $connection = connect_db($hn0, $un0, $pw0, $db0);
    if($connection){
        $name_add = $connection->real_escape_string($name);
        $string_add = $connection->real_escape_string($content);
        $insertdb = "INSERT INTO contacts (Name, Content) VALUES "."('$name_add', '$string_add')";
        $result = $connection->query($insertdb);
        fetch_db($hn0, $un0, $pw0, $db0);
        if(!$result){
            error_db();
        }
        else{
            $result->close();
        }
        $connection->close();
        global $set;
        $set = 1;
    }
}

//Fetch table from db
function fetch_db($hn1, $un1, $pw1, $db1){
    $connection = connect_db($hn1, $un1, $pw1, $db1);
    if($connection){
        echo '<br> <br>Showing database of contacts (name, email) <br><br>'; 
        $fetchdb = "SELECT * FROM contacts";
        $result = $connection->query($fetchdb);
	if(!result){
		error_db();
        }
        else{
            $rows = $result->num_rows;
            for ($i=0; $i<$rows; $i++){
                $result->data_seek($i);
                $row = $result->fetch_array(MYSQLI_ASSOC);

                echo 'ID: '.$row['ID'].'<br>';
                echo 'Name: '.$row['Name'].'<br>';
                echo 'E-Mail: '.$row['Content'].'<br>';
                echo 'Timestamp: '.$row['Timestamp'].'<br><br>';            
            }
            $result->close();
        }
        $connection->close();
    }
}


function error_db(){
    global $err_flag;
    if($err_flag==0){
    	echo '<br><img src="uhoh.jpg" alt="error uhoh" style="width:400px;height:500px;"/><br>';
        $err_flag=1;
    }
}
echo "</body></html>";
?>

