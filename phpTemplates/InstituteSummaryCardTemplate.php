<?php
	class InstituteSummaryCardTemplate{
		/*
			A template for displaying Institute Summaries
		*/
		public static function printTemplate($instituteName, $instituteInfo ){
			echo "<div class='w3-card-4 card w3-light-grey' >
				
				<header class='w3-container w3-khaki'>
					<h2>$instituteName</h2>
				</header>

				<button class='btn-success detailsBtn' onclick='displayDetails($instituteInfo->unitid)'>More Info</button>
				<button class='btn-primary detailsBtn' onclick='displayNearby($instituteInfo->longitud, $instituteInfo->latitude, \"$instituteInfo->stabbr\")'>Nearby Institutes with Doctors Level</button>

				<br>
			  	
			  	<br><b> Name:</b> $instituteInfo->unitid
			  	<br><b> Address:</b> $instituteInfo->addr, $instituteInfo->city, $instituteInfo->zip, $instituteInfo->stabbr.
			  	<br><b> Website:</b> <a href='$instituteInfo->webaddr'>$instituteInfo->webaddr</a>
			  	
			  	<div id='$instituteInfo->unitid'></div><br>
			  	
			  </div>";
		}
				

	}
		

?>