<?php
	require_once("app/model/db/DbService.php");
	require_once("app/model/institute/InstituteHandler.php");
	require_once("app/model/institute/FilterHandler.php");

	$dbService=new DbService();
	$instituteHandler=new InstituteHandler($dbService::getDbConnection());
	$filterHandler=new FilterHandler($dbService::getDbConnection(), $dbService);

	echo json_encode($filterHandler->getListOfStates());
?>