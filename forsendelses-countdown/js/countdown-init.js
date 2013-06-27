jQuery(function(){

	var curTime = new Date(),
		note = jQuery('#note'),
	//date string for today
	ts = new Date(curTime.getFullYear(), curTime.getMonth(), curTime.getDate(), 16, 00)

	//is it past 16:00 we are counting for tommorrow. 
	if((new Date()) > ts){
		var curTime = new Date();
		ts = new Date(curTime.getFullYear(), curTime.getMonth(), curTime.getDate() + 1, 16, 00)
	}
	
	jQuery('#countdown').countdown({
		timestamp	: ts,
		callback	: function(days, hours, minutes, seconds){
			
			var message = "";
			
			message += days + " day" + ( days==1 ? '':'s' ) + ", ";
			message += hours + " hour" + ( hours==1 ? '':'s' ) + ", ";
			message += minutes + " minute" + ( minutes==1 ? '':'s' ) + " and ";
			message += seconds + " second" + ( seconds==1 ? '':'s' ) + " <br />";
			message += "indtil næste forsendelse!";
			//note.html(message);
		}
	});
	
});
