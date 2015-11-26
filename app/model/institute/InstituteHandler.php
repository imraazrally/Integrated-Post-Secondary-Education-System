<?php
	//DAO Object to manage MySQL queries
	class InstituteHandler{
		//@param=PDO;
		private $dbConnection;
		/* @param=array(String)
		 * This array will contain all attributes that needs to be converted from Abbreviated->Full Title. 
		*/
		private $listOfAbbreviationsToReplace;

		public function __construct($dbConnection){
			$this->dbConnection=$dbConnection;
			$this->listOfAbbreviationsToReplace=$this->getListOfAbbreviatedAttributes();
		}

		//@return=Array(FETCH_OBJ)
		public function getListOfInstituteNamesAndIds(){
			$SQL="SELECT unitid, instnm FROM base_table";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->execute();
			$listOfInstituteNames=array();
			while($result=$statement->fetch(PDO::FETCH_OBJ)) array_push($listOfInstituteNames, $result);
			return $listOfInstituteNames;
		}

		public function getInstituteSummaryById($unitId){
			$SQL="SELECT unitid, addr, city, stabbr, zip, webaddr, longitud, latitude FROM base_table WHERE unitid=:unitid";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->bindParam(":unitid",$unitId);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_OBJ);
		}

	
		public function getInstituteInfoByUnitId($id){
			$SQL="SELECT * FROM base_table WHERE unitid=:unitid";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->bindParam(":unitid",$id);
			$statement->execute();
			return $this->expandAbbreviatedName($statement->fetch(PDO::FETCH_OBJ));
		}

		/*@return=Array(STRING)
			This function will return a list of attributes (abbreviated)
		*/
		public function getListOfFieldNames(){
			$SQL="DESCRIBE base_table";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->execute();
			$fieldNames=array();
			while($result=$statement->fetch(PDO::FETCH_OBJ)) array_push($fieldNames, $result->Field);
			return $fieldNames;
		}

		/*@return=String
			Since the varnames are abbreviated, we retrieve the full titile by referring to vartitle table
			This function will take as input the abbreviated title and return the full title
		*/
		public function getVarTitleUsingVarName($varName){
			$SQL="SELECT vartitle FROM var_list WHERE LCASE(varname)=:varname";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->bindParam(":varname",$varName);
			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_OBJ);
			return $result->vartitle;
		}

		/*
			below are abbreviations related functions
		*/
		private function expandAbbreviatedName($instituteInfo){
			//Converting to array for ease of looping
			$instituteInfo=json_decode(json_encode($instituteInfo),true);
			//For each abbreviation that needs to be replaced, we retrieve the fullname using the abbreviation
			foreach($this->listOfAbbreviationsToReplace as $abbreviation){
				$instituteInfo[strtolower($abbreviation)]=$this->getFullNameUsingAbbreviation($abbreviation,$instituteInfo[strtolower($abbreviation)]);
			}
			return $instituteInfo;
		}

		private function getFullNameUsingAbbreviation($varname,$codeval){
			error_reporting(E_ERROR | E_PARSE);
			try{
				$SQL="SELECT valuelabel FROM frequencies WHERE varname=:varname and codevalue=:codevalue";
				$statement=$this->dbConnection->prepare($SQL);
				$statement->bindParam(":varname",$varname);
				$statement->bindParam(":codevalue",$codeval);
				$statement->execute();
				$result=$statement->fetch(PDO::FETCH_OBJ);
				return $result->valuelabel;
			}catch(Exception $e){
				return $codeval;
			}
		}

		private function getListOfAbbreviatedAttributes(){
			$listOfAbbreviationsToReplace=array();
			$SQL="SELECT DISTINCT varname FROM frequencies";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->execute();
			while($result=$statement->fetch(PDO::FETCH_OBJ)) array_push($listOfAbbreviationsToReplace,$result->varname);
			return $listOfAbbreviationsToReplace;
		}


		/*
			Param 1= object returned by $filterHandler->getListOfStates();
		*/
		public function getStatePublicInstituteCount($listOfStates){
			$ranks=array();
			
			$SQL="SELECT
							valuelabel		AS state, 
							COUNT(openpubl) AS count
				  FROM 
				  			base_table, frequencies 
				  WHERE 
				  			LCASE(base_table.stabbr)=:state 
				  					 AND 
				  			frequencies.codevalue=stabbr";

			foreach($listOfStates as $state){
				$statement=$this->dbConnection->prepare($SQL);
				$statement->bindParam(":state",strtolower($state->abbreviatedState));
				$statement->execute();
				$result=$statement->fetch(PDO::FETCH_OBJ);
				array_push($ranks,$result);
			}

			return $ranks;
		}

	}


?>