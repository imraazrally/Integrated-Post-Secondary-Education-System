<?php
	require_once("app/model/db/DbService.php");
	require_once("app/model/institute/InstituteHandler.php");
	require_once("app/model/institute/FilterHandler.php");
	require_once("phpTemplates/StateRankByInstituteTemplate.php");

	$dbService=new DbService();
	$instituteHandler=new InstituteHandler($dbService::getDbConnection());
	$filterHandler=new FilterHandler($dbService::getDbConnection(), $dbService);


	//Retrieving the count publicly open institutes for each state
	$stateInstituteCount=$instituteHandler->getStatePublicInstituteCount($filterHandler->getListOfStates());

	//Insertion Sorting in Descending Order
	for($i=1; $i<count($stateInstituteCount); $i++){
		$j=$i;
		while($j>0 && $stateInstituteCount[$j]->count > $stateInstituteCount[$j-1]->count){
			$temp=$stateInstituteCount[$j-1];
			$stateInstituteCount[$j-1]=$stateInstituteCount[$j];
			$stateInstituteCount[$j]=$temp;
			$j--;
		}
	}

	//Printing Table
	StateRankByInstituteTemplate::printTemplate($stateInstituteCount);

?>