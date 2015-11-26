<?php
	require_once("app/model/db/DbService.php");
	require_once("app/model/institute/InstituteHandler.php");
	require_once("app/model/institute/FilterHandler.php");
	require_once("phpTemplates/InstituteSummaryCardTemplate.php");
	
	if(!isset($_GET['longitude']) || !isset($_GET['latitude']) || !isset($_GET['state'])){
		include("ListOfInstitutes.php");
		die();
	}

	$longitude=$_GET['longitude'];
	$latitude=$_GET['latitude'];
	$state=strtolower($_GET['state']);


	$dbService=new DbService();
	$instituteHandler=new InstituteHandler($dbService::getDbConnection());
	$filterHandler=new FilterHandler($dbService::getDbConnection(), $dbService);

	//Filtering for nearby colleges
	$nearbyInstitutes=$filterHandler->getNearbyInstituteNamesAndDistance($latitude, $longitude,$state);

	foreach($nearbyInstitutes as $institute){
		$institute=json_decode(json_encode($institute));
		$instituteInfo=$instituteHandler->getInstituteSummaryById($institute->unitid);

	    //Displaying a summary of each Institute.
		$instituteInfo=$instituteHandler->getInstituteSummaryById($institute->unitid);
		InstituteSummaryCardTemplate::printTemplate($institute->instnm, $instituteInfo);	
	}
?>