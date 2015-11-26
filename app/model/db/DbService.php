<?php
	require_once("DbLoginConsts.php");

	// Using Singleton Pattern
	// Whenever a PDO connection is required, we aqquire the connection by calling new DbSerive()->getDbConnection();
	// This reassures that we are not creating multiple open connections. 
	// Use closeDbConnection() to deallocate resources. 
	class DbService{
		private static $dbConnection=null;

		public static function getDbConnection(){
			//Retreiving Settings from Configs/mysql.xml file
			$dbLoginInfo=new DbLoginConsts();
			//We spawn a PDO Object only if a PDO connection doesn't already exist
			if (is_null(DbService::$dbConnection)){
				DbService::$dbConnection=new PDO($dbLoginInfo->getUrl(), $dbLoginInfo->getUsername(), $dbLoginInfo->getPassword());
				// Allowing PDO to throw Exceptions
				DbService::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			//Returning an Instance of a PDO Object. 
			return DbService::$dbConnection;
		}

		public function closeDbConnection(){
			DbService::$dbConnection=null;
		}

		public function getHostName(){
			$dbLoginInfo=new DbLoginConsts();
			return $dbLoginInfo->getHostName();
		}
	}
?>