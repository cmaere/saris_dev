<?php


$cha = mysql_connect($hostname_cha, strrev($username_cha), strrev($password_cha)); 

if (!$cha){
	 printf(mysql_error()."Failed to connect to db");
	 exit;
	}
@mysql_select_db ($database_cha, $cha); 


global $szRootURL,$szRootPath,$szSiteTitle,$szWebmasterEmail,$arrStructure,$arrVariations,$intDefaultVariation;
global $szDBName,$szDBUsername,$szDBPassword,$szDiscussionAdmin,$szDiscussionPassword;
if (!$cha){
	 printf("welcome");
	 exit;
	}

	$arrVariations = array (
		1 => array( 'name' => 'English', 'shortname' => 'Eng'),
		2 => array( 'name' => 'Chichewa', 'shortname' => 'MW'),
	);
	
$arrVariationPreference = array (
		1 => 1,
		2 => 2
	);
	
	if (!isset($_SESSION['arrVariationPreference'])){
		// store it in the session variable
		$_SESSION['arrVariationPreference']=$arrVariationPreference;
	}
	
	// define the default variation
	$intDefaultVariation = 1;

	#Get Organisation Name and address
	$qorg = "SELECT * FROM organisation";
	$dborg = mysql_query($qorg);
	$row_org = mysql_fetch_assoc($dborg);
	$org = $row_org['Name'];
	$post = $row_org['Address'];
	$phone = $row_org['tel'];
	$fax = $row_org['fax'];
	$email = $row_org['email'];
	$website = $row_org['website'];
	$city = $row_org['city'];
	mysql_free_result($dborg);
?>
