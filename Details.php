<?php
	require_once("app/model/db/DbService.php");
	require_once("app/model/institute/InstituteHandler.php");

	$dbService=new DbService();
	$instituteHandler=new InstituteHandler($dbService::getDbConnection());
	$hostname=$dbService->getHostName();

	//If 'id' is not found we redirect to homepage
	if(!isset($_GET['id'])) header("Location:$hostname");
	$id=mysql_escape_string($_GET['id']);

	//Retrieving the Institute Information from the database. 
	$institute=$instituteHandler->getInstituteInfoByUnitId($id);
	$rows="";

	//Retrieving the raw column name for each attribute of an institution;
	$fieldNames=$instituteHandler->getListOfFieldNames();

	/*
		Iterate through each raw attribute and get the full attribute name using getVarTitleUsingVarName();
		Display the [Full Attribute Name][Value] in each row
	*/

	foreach($fieldNames as $field){
		$attribute=$instituteHandler->getVarTitleUsingVarName($field);
		$value=$institute[$field];
		$rows.= "<tr><td>$attribute</td><td>$value</td></tr>";
	}

	
	//Printing Table
	echo("<table class='w3-table w3-bordered w3-striped w3-large w3-sand'>$rows</table>");
?>

