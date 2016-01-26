
<?php
// this script verifies the user from the database and assign proper previledges to the user
//Code Authors
//Charlie Maere
//die("still here in here prof");
$date = date('Y m d');
//encrypting the password
$encrypted_pass = "{jlungo-hash}" . base64_encode(pack("H*", sha1($_SESSION['password']))); 
// end encrypting the password
$query_AYear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
$result_AYear=mysql_query($query_AYear);
while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
{
	$year= $line["AYear"];  
        $semester = $line["Semister_status"];
} 
mysql_free_result($result_AYear);	    
$sql="SELECT UserName, UPPER(RegNo) AS RegNo ,LEFT(UPPER(RegNo),9) as RegNo2,RIGHT(UPPER(RegNo),3) AS RegNo3,Position, Module, PrivilegeID, FullName, Faculty FROM security WHERE UserName='$user' AND password = '$encrypted_pass'";

$result = mysql_query($sql, $cha);
$loginFoundUser = mysql_num_rows($result);
    
 if ($loginFoundUser <> 0) 
 {
           
		  
	    $_SESSION['loginstatus'] = "loggedin";
           
            
           
            
    		$_SESSION['loginName']= mysql_result($result,0,'FullName');
		$position = mysql_result($result,0,'Position');
		$RegNo  = mysql_result($result,0,'RegNo');
           	$RegNo2 = mysql_result($result,0,'RegNo2');
                $RegNo3 = mysql_result($result,0,'RegNo3');
		$module = mysql_result($result,0,'Module');
		$userFaculty = mysql_result($result,0,'Faculty');
		$privilege  = mysql_result($result,0,'PrivilegeID');
		$mtumiaji = 3;
		mysql_free_result($result); 			
	 	$update_login = "UPDATE security SET LastLogin = now() WHERE UserName = '$user'";
	 	$result = mysql_query($update_login) or die("failed update LastLogin, chasaris");
        	
      
      
      	  require_once("include/controller.php"); 
	

} 
else
{
	
	
	echo "<script language='JavaScript'> alert('Username or Password incorrect!'); </script>";
  	echo '<meta http-equiv = "refresh" content ="0; url = ./?page=Logout">';
   	exit;
}
	

mysql_close($cha);
?>
