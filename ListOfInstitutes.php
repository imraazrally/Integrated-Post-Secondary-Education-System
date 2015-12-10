<?php
	require_once("app/model/db/DbService.php");
	require_once("app/model/institute/InstituteHandler.php");
	require_once("phpTemplates/InstituteSummaryCardTemplate.php");
	
	$dbService=new DbService();
	$instituteHandler=new InstituteHandler($dbService::getDbConnection());

	//Retrieveing a List of Institute Names and Id's from the database
	$listOfInstituteNames=$instituteHandler->getListOfInstituteNamesAndIds();
	//The URL that displays detailed information when clicked on a peticular institute
	$instituteDetailsPageUrl=$dbService->getHostName()."Details.php?";


	//Displaying the clickable links for each institute by passing the ID as a GET parameter
	foreach($listOfInstituteNames as $institute){
		$instituteInfo=$instituteHandler->getInstituteSummaryById($institute->unitid);
		//Displaying a summary of each Institute.
		$instituteInfo=$instituteHandler->getInstituteSummaryById($institute->unitid);
		InstituteSummaryCardTemplate::printTemplate($institute->instnm, $instituteInfo);	
	}
?>
