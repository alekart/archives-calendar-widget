jQuery(function($){
    $(document).on("ajaxStop", function()
    {
        if(arcwidget && arcwidget.search('archives_calendar') >= 0 && arcwidget.search('-savewidget') >= 0)
        {
            arcwidget = arcwidget.replace("-savewidget", "").replace("widget-", "");
            arcwidget = $('#widgets-right').find('.'+arcwidget);
            arw_set_view(arcwidget);
            arw_theme(arcwidget);
            arcwidget.on('click', '.accordion-section:not(.open)', function(){
               if(arcwidget.find('.accordion-section.open').length)
                   $(this).parent().find('.accordion-section.open .accordion-section-content').slideUp('fast', function(){$(this).parent().removeClass('open')});
               $(this).find('.accordion-section-content').slideDown('fast', function(){$(this).parent().addClass('open')});
            })
            .on('click', '.accordion-section.open .accordion-section-title', function(){
                $(this).parent().find('.accordion-section-content').slideUp('fast', function(){$(this).parent().removeClass('open')});
            });
        }
    });
    $(document).on("ajaxStart", function(e){
        arcwidget = $(e.currentTarget.activeElement).attr('id');
    });

    $('#widgets-right .archives-calendar').each(function(){
        arw_set_view($(this));
        arw_theme($(this));
    });

    $('#widgets-right').on('change', '#arw-view', function(){
        var viewOpt = $(this).parents('.widget-content').find('#arw-month_view-option');
        var yearOpt = $(this).parents('.widget-content').find('#arw-year_view-option');
        if($(this).val()==1)
        {
            viewOpt.parent().show().css('display', 'inline-block');
            yearOpt.parent().hide();
            yearOpt.attr('checked', false);
        }
        else
        {
            viewOpt.parent().hide();
            yearOpt.parent().show();
        }
    });

    $('body').on('change', 'input:checkbox', function () {
        if($(this).attr('id') == "arw-theme-option"){
            var widget = $(this).parents('.archives-calendar');
            arw_theme(widget);
        }
    });

    function arw_set_view(elem)
    {
        var month_view = elem.find('#arw-view').val();
        var viewOpt = elem.find('#arw-month_view-option');
        var yearOpt = elem.find('#arw-year_view-option');
        if( month_view == 1 )
        {
            yearOpt.parent().hide();
            yearOpt.attr('checked', false);
            viewOpt.parent().show().css('display', 'inline-block');
        }
        else
            viewOpt.hide();
    }

    function arw_theme(widget){
        var themes = widget.find("#arw-theme-list");
        console.log(themes);
        if( widget.find('#arw-theme-option').is(':checked'))
            $(themes).show();
        else
            $(themes).hide();
    }
});
