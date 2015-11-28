<?php
	require_once("app/model/db/DbService.php");
	require_once("app/model/institute/InstituteHandler.php");
	require_once("app/model/institute/FilterHandler.php");
	require_once("phpTemplates/InstituteSummaryCardTemplate.php");
	
	$dbService=new DbService();
	$instituteHandler=new InstituteHandler($dbService::getDbConnection());
	$filterHandler=new FilterHandler($dbService::getDbConnection(), $dbService);

	$getRequest=$_GET;

	

	/*
		We are building a SQL QUERY STRING dynamically based on the GET Paremeters. 
		Available Filters By: name, state, level, hospital, medical
		Note: The GET request may contain any combination of the above filters. 
	*/

	/*
		Our Initial query string assums that there are no filters [WHERE 1=1];
	*/

	$BASE_QUERY="SELECT unitid, instnm FROM base_table WHERE 1=1 ";
	
	// Initializing the filter strings to empty (avoid null);
	$name=$state=$level=$hospital=$medical="";

	/*
		Now we dynamically modify the query string based on the filter parameters;
	*/
	if(!empty($getRequest['name'])){
		$name=strtolower("%".$getRequest['name']."%");
		$BASE_QUERY.=" AND LCASE(instnm) like :name";
	}

	if(!empty($getRequest['state'])){
		$state=$getRequest['state'];
		$BASE_QUERY.=" AND stabbr=:state";
	}

	if(!empty($getRequest['instituteLevel'])){
		$level=(int)$getRequest['instituteLevel'];
		$BASE_QUERY.=" AND iclevel=:level";
	}

	if(!empty($getRequest['hospital'])){
		$hospital=$getRequest['hospital'];
		$BASE_QUERY.=" AND hospital=:hospital";
	}

	if(!empty($getRequest['medical'])){
		$medical=$getRequest['medical'];
		$BASE_QUERY.=" AND medical=:medical";
	}

	
	$listOfNamesAndIdsFiltered=$filterHandler->getFilteredIdsAndNames(
										$BASE_QUERY, 
										$name, 
										$state, 
										$level, 
										$hospital, 
										$medical
									);

	foreach($listOfNamesAndIdsFiltered as $institute){
		$instituteInfo=$instituteHandler->getInstituteSummaryById($institute->unitid);

	    //Displaying a summary of each Institute.
		$instituteInfo=$instituteHandler->getInstituteSummaryById($institute->unitid);
		InstituteSummaryCardTemplate::printTemplate($institute->instnm, $instituteInfo);	
	}

?>
