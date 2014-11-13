/* PREVIEW MODAL */
jQuery(document).ready(function($) {
    $('.calendar-archives.preview a').on('click', function(e) {
        e.preventDefault();
    });
    $('.preview_theme_select li.preview-theme').on('click', function(){
        var d = (new Date()).getTime();
        var theme = $(this).attr('id');
        $(".arcw.preview-zone .calendar-archives ").addClass(theme);
        select_preview(theme);
        $("#ac_preview_css").remove();
        $("head").append('<link id="ac_preview_css" href="' + ARCWPATH + '/themes/' + theme + '.css?v=' + d + '" type="text/css" rel="stylesheet" />');
    });

    $('.button.preview_theme').on('click', function(){
        console.log('click preview')
        var d = (new Date()).getTime();
        var theme = $('select.theme_select').val();
        $(".arcw.preview-zone .calendar-archives ").addClass(theme);
        //$('#themepreview option[value='+css+']').attr('selected', true);
        select_preview(theme);
        $("#ac_preview_css").remove();
        $("head").append('<link id="ac_preview_css" href="' + ARCWPATH + '/themes/' + theme + '.css?v=' + d + '" type="text/css" rel="stylesheet" />');
    });

    $('.ok_theme').on('click', function(){
        tb_remove();
        $('select.theme_select option[value='+$('.preview-theme.selected').attr('id')+']').attr('selected', true);
    });
    $('.cancel_theme').on('click', function(){
        tb_remove();
    });

    function select_preview(theme){
        $('.preview_theme_select li').removeClass('selected');
        $('.preview_theme_select li#'+theme).addClass('selected');
    }

    /* PREVIEW CALENDAR MENU */
    $('.calendar-archives').find('.arrow-down').on('click', function()
    {
        $(this).parent().children('.menu').show();
    });

    $('.calendar-archives').find('.menu')
        .mouseleave(function()
        {
            var menu = $(this);
            $('html').data('arctimer', setTimeout(
                function(){
                    menu.parent().children('.menu').hide();
                },
                300
            ));
        })
        .mouseenter(function(){
            if($('html').data('arctimer'))
                clearTimeout($('html').data('timer'));
        });


    /* THEMER TABS **/
    /* TABS */
    if(typeof themer_tab !== 'undefined')
        checktabs(themer_tab);

    $(".nav-tab-wrapper.custom a.nav-tab").click(function(e){
        e.preventDefault();
        if($(this).is('.nav-tab-active, .notab'))
            return;
        var index = $(this).index();
        $('#current_tab').val(index);
        $(".nav-tab-wrapper.custom a.nav-tab-active").toggleClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        var tab = $(".nav-tab-wrapper.custom a.nav-tab-active").attr('href');
        openThemeTab(tab);
    });
    function checktabs(){
        $('#current_tab').val(themer_tab);
        $(".nav-tab-wrapper.custom a.nav-tab-active").removeClass('nav-tab-active');
        var tab = $(".nav-tab-wrapper.custom a.nav-tab:eq("+themer_tab+")").toggleClass('nav-tab-active').attr('href');
        openThemeTab(tab);
    }
    function openThemeTab(tab){
        console.log(tab);
        $('.tabs .tab').removeClass('active-tab');
        $('.tabs '+tab).addClass('active-tab');
        $('#arwprev').html( $('#codesource' +  + ( parseInt($('#current_tab').val()) + 1 ) ).html() );
        editor1.resize();
        editor2.resize();
    }
});