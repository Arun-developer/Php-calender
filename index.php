<?php
require_once 'class.calendar.php';
$phpCalendar = new PHPCalendar ();
?>
<html>
<head>
<link href="style.css" type="text/css" rel="stylesheet" />
<title>PHP Calendar</title>
</head>
<body>

<form>
	<h1>PHP Calendar</h1>
	
	<div class="item">
	 
	  <p>Month</p>
		<select name="month" id="month">
			<option value=''> Select Month </option>
			<option value='1'>Janaury</option>
			<option value='2'>February</option>
			<option value='3'>March</option>
			<option value='4'>April</option>
			<option value='5'>May</option>
			<option value='6'>June</option>
			<option value='7'>July</option>
			<option value='8'>August</option>
			<option value='9'>September</option>
			<option value='10'>October</option>
			<option value='11'>November</option>
			<option value='12'>December</option>
		</select>
		 <p>Year</p>
	  <input type="text" name="year" id="year" class="year" maxlength="4" placeholder="Enter Year">
	  <div class="btn-block">
          <button type="submit" id="submit_btn" href="/">Show Calendar</button>
        </div>
		<div  id="calendar-html-output">
			<?php
			/* $calendarHTML = $phpCalendar->getCalendarHTML();
			echo $calendarHTML; */
			?>
		</div>
	
</form>

<div id="body-overlay"><div><img src="loading.gif" width="64px" height="64px"/></div></div>
<script src="jquery-1.11.2.min.js" type="text/javascript"></script>
<script>
$('.year').keyup(function(e){
  if (/\D/g.test(this.value)){
    this.value = this.value.replace(/\D/g, '');
  }
});
$('#submit_btn').on('click',function(e){
	//e.preventDefault();
	var month = $('#month').val();
	var year = $('#year').val();
	error = 0;
	if(month ==''){
		$("#calendar-html-output").css('display','none');
		alert('Please select the month');
		error = 1;
		return false;
	}else if(year ==''){
		$("#calendar-html-output").css('display','none');
		alert('Please enter the year');
		error = 1;
		return false;
	}
	if(error != 1){
		$("#calendar-html-output").css('display','block');
	}
	
	$.ajax({
		url: "calendar-ajax.php",
		type: "POST",
		data:'month='+month+'&year='+year,
		success: function(response){
			setInterval(function() {$("#body-overlay").hide(); },500);
			$("#calendar-html-output").html(response);	
		},
		error: function(){} 
	});
	
	return false;
	
});
$(document).ready(function(){
	$(document).on("click", '.prev', function(event) { 
		var month =  $(this).data("prev-month");
		var year =  $(this).data("prev-year");
		getCalendar(month,year);
	});
	$(document).on("click", '.next', function(event) { 
		var month =  $(this).data("next-month");
		var year =  $(this).data("next-year");
		getCalendar(month,year);
	});
	$(document).on("blur", '#currentYear', function(event) { 
		var month =  $('#currentMonth').text();
		var year = $('#currentYear').text();
		getCalendar(month,year);
	});
});
function getCalendar(month,year){
	console.log(month);
	console.log(year);
	$("#body-overlay").show();
	$.ajax({
		url: "calendar-ajax.php",
		type: "POST",
		data:'month='+month+'&year='+year,
		success: function(response){
			setInterval(function() {$("#body-overlay").hide(); },500);
			$("#calendar-html-output").html(response);	
		},
		error: function(){} 
	});
	
}
</script>
</body>
</html>