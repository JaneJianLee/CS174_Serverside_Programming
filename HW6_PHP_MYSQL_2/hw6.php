<?php

require_once 'login.php';
$err_flag=0;

echo<<<_END
	<html>
	<head>
		<title>[CS174]HW6 Ji An Lee</title>
	</head>
	<body>
        <form method="post" action="" enctype="multipart/form-data">
            <p><label>[CS174][Fall 2019] HW6 Ji An Lee</label></p>
            -- Add new record to advisor-student mapping database <br></p>
            Advisor Name : <input type="text" name="adname"><br></p>
            Student Name : <input type="text" name="stname"><br></p>
            Student ID : <input type="text" name="stid"><br></p>
            Class Code : <input type="text" name="class"><br></p>
            <p><input type="submit" name="add" value="Add to Database"></p>
        </form>	<br/>
            <form method="post" action="" >
            -- Search for information about an advisor from database  <br></p>
            Advisor Name : <input type="text" name="searchadv"><br></p>
            <input type="submit" name="search" value="Search Database" />
		</form><br/>
_END;

//Add Section 
if((!empty($_POST['adname']))&& (!empty($_POST['stname']))&& (!empty($_POST['stid']))&& (!empty($_POST['class']))){

    $cn = connect_db($hn,$un,$pw,$db);
    if($cn){
            $smth = $cn -> prepare('INSERT INTO advstu VALUES (?,?,?,?)');
            $smth -> bind_param('ssis',$advisor_name, $student_name, $student_id, $class_code);
            
            $advisor_name = sanitizeSQL($cn,$_POST['adname']);
            $student_name = sanitizeSQL($cn,$_POST['stname']);
            $student_id = sanitizeSQL($cn,$_POST['stid']);
            $class_code = sanitizeSQL($cn,$_POST['class']);
            
            $smth->execute();
            if ($smth->affected_rows>0){
                printf("%d Row Inserted. \n", $smth->affected_rows);
            }
            else{
                printf("[Error] There is error in your input (length, type) \n");
            }
            $smth->close();
            $cn->close();
    }
    else{
        error_db();
    }
}

//Search Section
if(!empty($_POST['searchadv'])){
    $flag = 0;
    
    $cn = connect_db($hn,$un,$pw,$db);
    if($cn){
        echo ' Search advisor information.. <br><br>';
        
        /* prepare statement */
        $stmt = $cn -> prepare('SELECT StudentName, StudentID, ClassCode FROM advstu where AdvisorName = ?');
        $stmt -> bind_param('s',$advisor_name);
        $advisor_name = sanitizeSQL($cn,$_POST['searchadv']);
        $stmt->execute();
        
        /* bind variables to prepared statement */
        $stmt->bind_result($sname,$sid,$sclass);
        echo '** Advisor Name: \''.$advisor_name.'\' **<br><br>';
        
        /* fetch values */
        while ($stmt->fetch()) {
            echo 'Student Name: '.$sname.'<br>';
            echo 'Student ID: '.$sid.'<br>';
            echo 'Course Code: '.$sclass.'<br><br>';
            $flag=1;
        }
	if(!$flag){
            echo 'There is no information in the database for \''.$advisor_name.'\'.  <br>';
        }

        /* close statement */
        $stmt->close();
        $cn->close();
    }
    else{
        error_db();
    }  
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
    	echo '<br><img src="uhoh.jpg" alt="error uhoh" style="width:400px;height:500px;"/><br>';
        $err_flag=1;
    }
}
echo "</body></html>";
?>



