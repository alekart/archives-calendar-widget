/* PREVIEW MODAL */
jQuery(document).ready(function ($) {
    function loadThemePreview(theme) {
      var d = (new Date()).getTime();
      $(".arcw.preview-zone .calendar-archives ").addClass(theme);
      select_preview(theme);
      $("#ac_preview_css").remove();
      $("head").append('<link id="ac_preview_css" href="' + ARCWPATH + '/themes/' + theme + '.css?v=' + d + '" type="text/css" rel="stylesheet" />');
    }

    $('.calendar-archives.preview a').on('click', function (e) {
        e.preventDefault();
    });
    $('.preview_theme_select li.preview-theme').on('click', function () {
        var theme = $(this).attr('id');
        loadThemePreview(theme);
    });

    $('.button.preview_theme').on('click', function () {
        var theme = $('select.theme_select').val();
        loadThemePreview(theme);
    });

    $('.ok_theme').on('click', function () {
        tb_remove();
        $('select.theme_select option[value=' + $('.preview-theme.selected').attr('id') + ']').attr('selected', true);
    });
    $('.cancel_theme').on('click', function () {
        tb_remove();
    });

    function select_preview(theme) {
        $('.preview_theme_select li').removeClass('selected');
        $('.preview_theme_select li#' + theme).addClass('selected');
    }

    // I'M A DEV
    var imADevCheckbox = $('#imadev:checkbox');
    imADevCheckbox.attr("checked", null);
    imADevCheckbox.change(function() {
        if($(this).is(':checked')){
            $('.imadev').addClass('visible');
        }
        else
            $('.imadev').removeClass('visible');
    });

    /* PREVIEW CALENDAR MENU */
    var $calendarArchives = $('.calendar-archives');

    $calendarArchives.find('.arrow-down').on('click', function () {
        $(this).parent().children('.menu').show();
    });

    $calendarArchives.find('.menu')
        .mouseleave(function () {
            var menu = $(this);
            window.arctimer = setTimeout(
                function () {
                    menu.parent().children('.menu').hide();
                },
                300
            );
        })
        .mouseenter(function () {
            if (window.arctimer) {
                clearTimeout(window.arctimer);
                window.arctimer = undefined;
            }
        });
});
