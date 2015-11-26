<?php
	class FilterHandler{
		private $dbConnection;
		private $instituteHandler;

		public function __construct($dbConnection,$instituteHandler){
			$this->dbConnection=$dbConnection;
			$this->instituteHandler=$instituteHandler;
		}

		/*
			@ParamType=stdObj [abbreviatedState, stateFullname];	
		*/

		public function getListOfStates(){
			/*
				This function returns a list of states the colleges belong to (abbreviated);
			*/

			$listOfStates=array();

			//Execute SQL
			$SQL="SELECT 
						DISTINCT (stabbr) AS abbreviatedState, 
						valuelabel 		  AS stateFullName 

				  FROM base_table, frequencies WHERE codevalue=stabbr ORDER BY valuelabel";
			
			$statement=$this->dbConnection->prepare($SQL);
			$statement->execute();

			//Fetch
			while($result=$statement->fetch(PDO::FETCH_OBJ)){
				array_push($listOfStates, $result);
			}

			//Returning an array that contains a list of abbreviated states
			return $listOfStates;
		}

		/*
			returnParam=stdObj[instnm,unitid]
			This function returns a list of unit id and names filtered by {state}{level}{medical}{hospital}
		*/
		

		public function getFilteredIdsAndNames($SQL,$name,$state,$level,$hospital,$medical){
			$listOfNamesAndIds=array();
			//Dynamically Building Query String Filtered
			
			$statement=$this->dbConnection->prepare($SQL);
			if(!empty($name))			$statement->bindParam(":name",$name);
			if(!empty($state)) 			$statement->bindParam(":state",$state);
			if(!empty($level))			$statement->bindParam(":level",$level);
			if(!empty($hospital))		$statement->bindParam(":hospital",$hospital);
			if(!empty($medical))		$statement->bindParam(":medical",$medical);
			$statement->execute();

			while($result=$statement->fetch(PDO::FETCH_OBJ)){
				array_push($listOfNamesAndIds,$result);
			}

			return $listOfNamesAndIds;

		}


		/*
			This function computes the nearest colleges to the base longitude, latitude in $state
		*/

		public function getNearbyInstituteNamesAndDistance($baseLatitude, $baseLongitude, $state){
			$nearbyInstitutes=array();

			//Query
			$SQL="SELECT unitid, instnm, longitud, latitude FROM base_table WHERE LCASE(stabbr)=:state AND hloffer>=9";
			$statement=$this->dbConnection->prepare($SQL);
			$statement->bindParam(":state",$state);
			$statement->execute();

			//Computing distance from Base Point to Result Point
			while ($result=$statement->fetch(PDO::FETCH_OBJ)) {
				$theta=$baseLongitude-$result->longitud;
				
				$distance = (sin(deg2rad($baseLatitude)) * sin(deg2rad($result->latitude))) + 
			                (cos(deg2rad($baseLatitude)) * cos(deg2rad($result->latitude)) * 
			                cos(deg2rad($theta))); 

                $distance = acos($distance); 
   				$distance = rad2deg($distance); 
    			$distance = $distance * 60 * 1.1515; 
    			array_push($nearbyInstitutes, array("unitid"=>$result->unitid, "instnm"=>$result->instnm, "distance"=>round($distance,2)));
			}

			/*
				Insertion Sorting the distance
			*/

			for($i=1; $i<count($nearbyInstitutes); $i++){
				$j=$i;
				while($j>0 && $nearbyInstitutes[$j]['distance']<$nearbyInstitutes[$j-1]['distance']){
					$temp=$nearbyInstitutes[$j-1];
					$nearbyInstitutes[$j-1]=$nearbyInstitutes[$j];
					$nearbyInstitutes[$j]=$temp;
					$j--;
				}
			}

			return $nearbyInstitutes;
		}

	}

?>