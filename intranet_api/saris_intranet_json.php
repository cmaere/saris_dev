<?php
/* 
This is code is written by charlie maere
This is a JSON script queries from mysql database and passes data to a JSON function
Code Authors
============
Charlie Maere


Country: Malawi
Date: 29th May 2014
Email: cmaere@kcn.unima.mw
*/

include("dbconnect.php");
$sql = "SELECT p.post_title,p.post_date,p.post_content,p.guid,u.user_nicename FROM wp_posts p,wp_users u WHERE p.post_author = u.ID AND p.post_status ='publish' ORDER BY p.post_date DESC
LIMIT 0 , 4";
$result = mysql_query($sql);
while($row=mysql_fetch_assoc($result))
{
	$output[]=$row;
}
print(json_encode($output));




?>