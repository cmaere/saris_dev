<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];

//control the display table
@$new=2;

mysql_select_db($database_cha, $cha);
$query_campus = "SELECT CampusID, Campus FROM campus";
$campus = mysql_query($query_campus, $cha) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);	

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert_faculty"])) && ($_POST["MM_insert_faculty"] == "frmInst")) 
{
	  $insertSQL = sprintf("INSERT INTO faculty (CampusID, FacultyName, Location, Address, Tel, Email) VALUES (%s, %s, %s, %s, %s, %s)",
	                       $row_campus['CampusID'],
			       GetSQLValueString($_POST['txtName'], "text"),
	                       GetSQLValueString($_POST['txtPhyAdd'], "text"),
	                       GetSQLValueString($_POST['txtAdd'], "text"),
	                       GetSQLValueString($_POST['txtTel'], "text"),
	                       GetSQLValueString($_POST['txtEmail'], "text"));

	  mysql_select_db($database_cha, $cha);
	  $Result1 = mysql_query($insertSQL, $cha) or die(mysql_error());
          echo '<meta http-equiv = "refresh" content ="0; 
url = ./?page=Faculty&section=Policy&pageNum_inst=0">';
}

if ((isset($_POST["MM_update_faculty"])) && ($_POST["MM_update_faculty"] == "frmInstEdit")) 
{
	  $updateSQL = sprintf("UPDATE faculty SET CampusID=%s, FacultyName=%s, Location=%s, Address=%s, Tel=%s, Email=%s WHERE FacultyID=%s",
	                       GetSQLValueString($_POST['cmbInst'], "text"),
			       GetSQLValueString($_POST['txtName'], "text"),
	                       GetSQLValueString($_POST['txtPhyAdd'], "text"),
	                       GetSQLValueString($_POST['txtAdd'], "text"),
	                       GetSQLValueString($_POST['txtTel'], "text"),
	                       GetSQLValueString($_POST['txtEmail'], "text"),
	                       GetSQLValueString($_POST['id'], "int"));

	  mysql_select_db($database_cha, $cha);
	  $Result1 = mysql_query($updateSQL, $cha) or die(mysql_error());

	  $updateGoTo = "./";
	  if (isset($_SERVER['QUERY_STRING']))
	   {
	   	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	    	$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
          echo '<meta http-equiv = "refresh" content ="0; 
url = '.$updateGoTo.'">';
}

// page numbering of the list
$pageNum_inst = 0;
$maxRows_inst = 10;
if (isset($_GET['pageNum_inst'])) 
{
	$pageNum_inst = htmlspecialchars($_GET['pageNum_inst']);
}
$startRow_inst = $pageNum_inst * $maxRows_inst;

mysql_select_db($database_cha, $cha);
$query_inst = "SELECT * FROM faculty ORDER BY FacultyName ASC";
$query_limit_inst = sprintf("%s LIMIT %d, %d", $query_inst, $startRow_inst, $maxRows_inst);
$inst = mysql_query($query_limit_inst, $cha) or die(mysql_error());
$row_inst = mysql_fetch_assoc($inst);
	
if (isset($_GET['totalRows_inst'])) 
{
	  $totalRows_inst = $_GET['totalRows_inst'];
}
else 
{
	  $all_inst = mysql_query($query_inst);
	  $totalRows_inst = mysql_num_rows($all_inst);
}
$totalPages_inst = ceil($totalRows_inst/$maxRows_inst);


// table heading
$column_titles = array('Faculty','Location','Address','Tel','Email');	
	

// display to common template	
	
$template->loadTemplateFile("common_header.tpl");
$template->setCurrentBlock("heading");
$template->setVariable("PAGENAME", "Faculty Information");	
$template->setVariable("LINK", "./?page=Faculty&section=Policy&new=1");
$template->setVariable("BUTTONNAME", "Add New Faculty");		
$template->parseCurrentBlock();	
$template->show();
 
@$new= htmlspecialchars($_GET['new']);
if (array_key_exists('new', $_GET) && @$new==1)
{
	require_once("include/admission/admissionFaculty_new.php");
}
else if (isset($_GET['edit']))
{
	require_once("include/admission/admissionFaculty_edit.php");
}
else
{
	
	
		$template->loadTemplateFile("common_table_list.tpl");
		$template->setCurrentBlock("columns");
		$i=0;
		foreach ($column_titles as $column) 
		{
		
		
			if($i==0)
			{	$template->setVariable("OPTIONS", "data-toggle='class' class='th-sortable'");
				$template->setVariable("COLUMNTITLE", $column );
				$template->setVariable("SPINNER", spinner());
			}
			else
			{
					$template->setVariable("COLUMNTITLE", $column );
			}
			$template->parseCurrentBlock();	
			$i++;	
		}
		$i=0;
		do { 
			$id = $row_inst['FacultyID']; 
			$name = $row_inst['FacultyName'];
			$location = $row_inst['Location'];
			$address = $row_inst['Address'];
			$tel = $row_inst['Tel'];
			$email = $row_inst['Email'];
			$template->setCurrentBlock("id");
			$template->setVariable("ID", $id);
			$template->parseCurrentBlock();	
			$template->setCurrentBlock("innercolumns");
			$template->setVariable("ROWDATA", $name);
			$template->parseCurrentBlock();	
			$template->setVariable("ROWDATA", $location);
			$template->parseCurrentBlock();	
			$template->setVariable("ROWDATA", $address);
			$template->parseCurrentBlock();	
			$template->setVariable("ROWDATA", $tel);
			$template->parseCurrentBlock();	
			$template->setVariable("ROWDATA", $email);
			$template->parseCurrentBlock();	
			$template->setCurrentBlock("edit");
			$template->setVariable("ID", "./?page=$page&section=$section&edit=$id");
			$template->parseCurrentBlock();	
			$template->parse("row");
		
		
		
			$i++;
		
		
		} while ($row_inst = mysql_fetch_assoc($inst));
	
		$template->setCurrentBlock("delete");
		$template->setVariable("FORM", $editFormAction );
		$template->parseCurrentBlock();	
		// page numbers
		$template->setCurrentBlock("pagenumstat");
	 	if(($pageNum_inst - 1) < 0)
	 	{
		 	if($totalRows_inst <= $maxRows_inst)
		 	{
		 		$template->setVariable("PAGENUMSTATUS", "showing 1 - $totalRows_inst of $totalRows_inst items" );
	 	 	}
		 	else
		 	{
				$startpaging = 1;
				$endpaging = $startpaging + ($maxRows_inst - 1);
		 		$template->setVariable("PAGENUMSTATUS",  "showing 1 - $endpaging  of $totalRows_inst items");
		 	}
	 	}
		else
		{
			$startpaging = ($pageNum_inst * $maxRows_inst) + 1;
			$endpaging = $startpaging + ($maxRows_inst - 1);
			$template->setVariable("PAGENUMSTATUS",  "showing $startpaging - $endpaging of $totalRows_inst items");
		}
		$template->parseCurrentBlock();	
	
		pagenumber($template,$pageNum_inst,$page,$section,$totalPages_inst,$queryString_inst);
		$template->show();
}
@mysql_free_result($inst);
@mysql_free_result($instEdit);
@mysql_free_result($faculty);
@mysql_free_result($campus);
	
?>






