var editor1, editor2;

jQuery(function ($) {
    editor1 = ace.edit("editor1");
    editor1.setTheme("ace/theme/monokai");
    editor1.getSession().setMode("ace/mode/css");

    editor2 = ace.edit("editor2");
    editor2.setTheme("ace/theme/monokai");
    editor2.getSession().setMode("ace/mode/css");

    editor1.on("change", function () {
        $('#codesource1').html(editor1.getValue());
        $('#arwprev').html(editor1.getValue());
    });

    editor2.on("change", function () {
        $('#codesource2').html(editor2.getValue());
        $('#arwprev').html(editor2.getValue());
    });

    /* THEMER TABS **/
    /* TABS */
    if (typeof themer_tab !== 'undefined')
        checktabs(themer_tab);

    var navTabWrapper = $('.nav-tab-wrapper.custom'),
        navTab = navTabWrapper.find('a.nav-tab');

    navTab.click(function (e) {
        e.preventDefault();
        if ($(this).is('.nav-tab-active, .notab'))
            return;
        var index = $(this).index();
        $('#current_tab').val(index);
        navTabWrapper.find('a.nav-tab-active').toggleClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        openThemeTab( $(this).attr('href') );
    });


    function checktabs() {
        $('#current_tab').val(themer_tab);
        navTabWrapper.find('a.nav-tab-active').removeClass('nav-tab-active');
        var tab = $(".nav-tab-wrapper.custom a.nav-tab:eq(" + themer_tab + ")").toggleClass('nav-tab-active').attr('href');
        openThemeTab(tab);
    }

    function openThemeTab(tab) {
        //console.log(tab);
        $('.tabs .tab').removeClass('active-tab');
        $('.tabs ' + tab).addClass('active-tab');
        $('#arwprev').html($('#codesource' + +( parseInt($('#current_tab').val()) + 1 )).html());
        editor1.resize();
        editor2.resize();
    }
});