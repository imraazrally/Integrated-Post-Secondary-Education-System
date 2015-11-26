<?php
	//Login Information for the PDO
	class DbLoginConsts{
		private $loginInfo;
		//On Initilization, We Parse the XML file into an Object.
		function __construct(){
			$this->loginInfo=simplexml_load_file("app/model/db/mysql.xml");
		}

		public function getUrl(){
			return $this->loginInfo->url;
		}

		public function getUsername(){
			return $this->loginInfo->username;
		}

		public function getPassword(){
			return $this->loginInfo->password;
		}

		public function getHostName(){
			return $this->loginInfo->hostname;
		}

	}

?>