function getListOfInstitutes(){
	$.ajax({
		url: 'ListOfInstitutes.php',
		success: function(success){
			$('#results').html(success);
		}
	});
}

function displayDetails(id){
	$.ajax({
		url:'Details.php',
		data:{'id':id},
		success: function(success){
			$('#'+id).html(success);
		}
	});
}

function displaySelectStates(){
	$.ajax({
		url:'GetListOfStates.php',
		success: function(success){
			var list=JSON.parse(success);
			
			var dropBox="<select id='state'  selected='nj'><option value=''>All States </option>";
			for (i=0; i<list.length; i++) dropBox+="<option value='" + list[i].abbreviatedState + "'>" + list[i].stateFullName + "</option>";
			dropBox+="</select>";

			$('#selectStates').html(dropBox);
		}
	});
}

function filter(){
	var state=document.getElementById('state');
	var state=state.options[state.selectedIndex].value;

	var hospital=selectRadio('hospital');
 	var medical=selectRadio('medical');
 	var level=document.getElementById("level").value;
 	var instnm=document.getElementById("name").value;

	$.ajax({
		url:'Filtered.php',
		data:{'state':state, 'hospital':hospital, 'medical':medical, 'instituteLevel':level, 'name':instnm},
		success:function(success){
			$('#results').html(success);
		}
	});
}

function selectRadio(id){
	var radios = document.getElementsByName(id);

	for (var i = 0, length = radios.length; i < length; i++) {
	    if (radios[i].checked) {
	        // do whatever you want with the checked radio
	        return radios[i].value;
	    }
	}
}

function displayNearby(longitude, latitude, state){
	$.ajax({
		url:'GetNearbyInstitutes.php',
		data:{'longitude':longitude, 'latitude':latitude, 'state':state},
		success: function(success){
			$('#results').html(success);
		}
	});
}


function displayStatesRankedByPublicInstituteCount(){
	$.ajax({
		url: 'GetStateRankedByPubliclyOpenInstitutes.php',
		success: function(success){
			$('#results').html(success);
		}
	});
}