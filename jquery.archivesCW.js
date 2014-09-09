/*
 *  archivesCW v1.0 - jQuery plugin as a part of the WordPress plugin "Archives Calendar Widget"
 *  Copyright (C) 2013  Aleksei Polechin (http://alek.be)
 *  
 *  This plugin is a part of a WordPress plugin and will not do anything if used without the "Archives Calendar Widget" (WordPress plugin)
 *  It is used for easier customisation and for multiple widgets support.
*/
(function($)
{
	$.fn.archivesCW = function(options)
	{
		if(!options || typeof(options) == 'object')
		{
			var options = $.extend( {}, $.fn.archivesCW.defaults, options );
		}
		
		return this.each
		(
			function()
			{
				new $archivesCW($(this), options);
			}
		);
	}

	$archivesCW = function(elem, options)
	{
		var $wearein = parseInt(elem.find('.current.year').attr('rel'));
		var totalyears = elem.find('.year-select > a.year').length;
		if(totalyears <= 1) elem.find('.arrow-down').hide();
		aCalSetYearSelect();
		aCalCheckArrows();

		elem.find('.prev-year').on('click', function(e)
		{
			e.preventDefault();
			if( $(this).is('.disabled') ) return;
			goToYear($wearein + 1, options);
		});

		elem.find('.next-year').on('click', function(e)
		{
			e.preventDefault();
			if( $(this).is('.disabled') ) return;
			goToYear($wearein - 1, options);
		});

		elem.find('.arrow-down').on('click', function()
		{
			if($.isFunction(options.showDropdown))
				options.showDropdown($(this).parent().children('.year-select'));
		});

		elem.find('.year-select')
		.mouseleave(function()
		{
			var menu = $(this);
			$(this).data('timer', setTimeout(
				function(){
					if($.isFunction(options.hideDropdown))
						options.hideDropdown(menu);
				},
				300
			));
		})
		.mouseenter(function(){
			if($(this).data('timer'))
				clearTimeout($(this).data('timer'));
		});

		elem.find('.year-select a.year').on('click', function(e)
		{
			e.preventDefault();
			if( $(this).is('.selected') ) return;

			$(this).find('.year-select a').removeClass('selected');

			var rel = parseInt($(this).attr('rel'));
			goToYear(rel, options);

			if($.isFunction(options.hideDropdown))
				options.hideDropdown($(this).parent());
		});

		function aCalCheckArrows()
		{
			if($wearein == totalyears-1)
				elem.find('.prev-year').addClass('disabled');
			else 
				elem.find('.prev-year').removeClass('disabled');
			if($wearein == 0)
				elem.find('.next-year').addClass('disabled');
			else
				elem.find('.next-year').removeClass('disabled');
		}

		function goToYear(goTo, options)
		{
			var wearein = $wearein;

			// go next (more recent)
			if(goTo < wearein)
			{
				if($.isFunction(options.goNext))
				{
					options.goNext(elem, wearein, goTo);
				}
				else
				{
					$.fn.archivesCW.defaults.goNext(elem, wearein, goTo);
				}
			}
			// go prev (older)
			else
			{
				if($.isFunction(options.goPrev))
				{
					options.goPrev(elem, wearein, goTo);
				}
				else
				{
					$.fn.archivesCW.defaults.goPrev(elem, wearein, goTo);
				}
			}

			$wearein = goTo;
			var $year = elem.find('.year-select a.year[rel='+$wearein+']');
			elem.find('a.year-title').attr( 'href', $year.attr('href') ).html( $year.html() );
			aCalCheckArrows();
			aCalSetYearSelect();
		};
		function aCalSetYearSelect()
		{
			elem.find('.year-select').find('a.selected, a[rel='+$wearein+']').toggleClass('selected');
			elem.find('.year-select').css('top', - elem.find('.year-select').find('a.selected').index() * parseInt(elem.find('.year-nav').height()) );
		}
	};

	$.fn.archivesCW.defaults = {
		goNext: function(cal, actual, goTo)
		{
			/// EDIT THIS CODE TO CHANGE ANIMATION
			cal.find('.archives-years .year')
			.css({
				'margin-left': '-100%',
				'opacity': 1
			});

			cal.find('.archives-years .year[rel='+actual+']')
			.css({
				'margin-left': 0,
				'z-index': 2
			})
			.animate({
				'opacity': .5
			}, 300);

			cal.find('.archives-years .year[rel='+goTo+']')
			.css({
				'z-index': 3
			})
			.animate({
				'margin-left': 0
			});
		},
		goPrev: function(cal, actual, goTo)
		{
			/// EDIT THIS CODE TO CHANGE ANIMATION
			cal.find('.archives-years .year:not(.last)')
			.css({
				'margin-left': '-100%',
				'opacity': 1
			});

			cal.find('.archives-years .year[rel='+goTo+']')
			.css({
				'margin-left': 0,
				'opacity': .3,
				'z-index': 2
			}).animate({
				'opacity': 1
			}, 300);

			cal.find('.archives-years .year[rel='+actual+']')
			.css({
				'margin-left': 0,
				'z-index': 3
			}).animate({
				'margin-left': '-100%'
			});
		},
		showDropdown: function(menu)
		{
			menu.show();
		},
		hideDropdown: function(menu)
		{
			menu.hide();
		}
	};

})(jQuery);