<?php
	class StateRankByInstituteTemplate{

		public static function printTemplate($stateInstituteCount){
			echo "<table class='card w3-table w3-bordered w3-dark-grey w3-red stateTable' >
					<tr class='w3-light-grey'><th><h2>State</h2></th><th><h2>Available Institutes</h2></th>";
					
			foreach($stateInstituteCount as $state){
				echo "<tr>  <td><h3>$state->state</h3></td>  <td><h3>$state->count</h3></td>   </tr>";	
			}

			echo "</table>";
		}

		
	}

?>