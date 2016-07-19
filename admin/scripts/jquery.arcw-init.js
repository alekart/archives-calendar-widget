// init the archives calendar with default settings
jQuery(function ($) {
	$('.calendar-archives').archivesCW();

	var lastRow = $('.calendar-archives').find('.week-row:last-child');

	$.each(lastRow, function(){
		var row = $(this),
			nodays = row.find('.noday').length;
			console.log(nodays);
		if(row.find('.noday').length == 7){
			row.remove();
		}
	});
});
