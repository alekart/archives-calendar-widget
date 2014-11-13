jQuery(document).ready(function($)
{
	$('.color-field').wpColorPicker({
		change: function(event, ui){
			var $field = $(this).parent().find('.color-field');
			var id = $(this).parent().find('.color-field').attr('id').split('__');
			var element = id[0];
			var param = id[1];
			if($field.is('.cal'))
				$( '.themer .'+element ).css(param, $field.val() );
			else 
				$( '.'+element ).css(param, $field.val() );
		}
	}).keypress(function(e){
		if (e.keyCode == 10 || e.keyCode == 13) 
			e.preventDefault();
	});

	$('.customize-control-nav_bg .color-field').on('change', function(){
		$('.themer .cal-nav').css('background', $(this).val());
	});
});

function colorChange(event, ui){
	alert(ui)
}