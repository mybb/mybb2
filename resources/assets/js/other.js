$('html').addClass('js');

$(function () {

    $('.nojs').hide();

    if (Modernizr.touch) {
        $('.radio-buttons .radio-button, .checkbox-buttons .checkbox-button').click(function () {

        });
    } else {
        $('span.icons i, a, .caption, time').powerTip({ placement: 's', smartPlacement: true });
    }

    // var userNavigationDropdown = new Dropit('.user-navigation__links', { submenuEl: 'div.dropdown' });
    // var mainMenuDropdown = new Dropit('.main-menu__container');
    var dropdownMenu = new Dropit('.dropdown-menu', { submenuEl: 'ul.dropdown', triggerEl: 'span.dropdown-button' });
    
    // $('.user-navigation__links, #main-menu').dropit({ submenuEl: 'div.dropdown' });
    // $('.dropdown-menu').dropit({ submenuEl: 'ul.dropdown', triggerEl: 'span.dropdown-button' });
    $("input[type=number]").stepper();
    $(".password-toggle").hideShowPassword(false, true);

    $("#search .search-button").click(function (event) {
        event.preventDefault();
        $("#search .search-container").slideDown();
    });

    autosize($('.post textarea'));

    $('a.show-menu__link').click(function (e) {
        if (menu == 0) {
            menu = 1;
            openMenu(e);
        } else {
            menu = 0;
            closeMenu(e);
        }
    });

/*	$('.post.reply textarea.editor, .form textarea.editor').sceditor({
		plugins: 'bbcode',
		style: 'js/vendor/sceditor/jquery.sceditor.default.min.css',
		emoticonsRoot: 'assets/images/',
		toolbar: 'bold,italic,underline|font,size,color,removeformat|left,center,right|image,link,unlink|emoticon,youtube|bulletlist,orderedlist|quote,code|source',
		resizeWidth: false,
		autofocus: false,
		autofocusEnd: false
	});*/
});

// Overwrite the powertip helper function - it's nearly the same
function getTooltipContent(element)
{
    var tipText = element.data(DATA_POWERTIP),
        tipObject = element.data(DATA_POWERTIPJQ),
        tipTarget = element.data(DATA_POWERTIPTARGET),
        targetElement,
        content;

    if (tipText) {
        if ($.isFunction(tipText)) {
            tipText = tipText.call(element[0]);
        }
        content = tipText;
    } else if (tipObject) {
        if ($.isFunction(tipObject)) {
            tipObject = tipObject.call(element[0]);
        }
        if (tipObject.length > 0) {
            content = tipObject.clone(true, true);
        }
    } else if (tipTarget) {
        targetElement = $('#' + tipTarget);
        if (targetElement.length > 0) {
            content = targetElement.html();
        }
    }

    // Except we're escaping html
    return escapeHTML(content);
}

// Source: http://stackoverflow.com/questions/24816/escaping-html-strings-with-jquery

var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
};

function escapeHTML(string)
{
    if (typeof string == 'string') {
        return String(string).replace(/[&<>"'\/]/g, function (s) {
            return entityMap[s];
        });
    }

    return string;
}

function submitFormAsGet(id, newRoute)
{
    var form = $('#' + id);
    form.find("input[name=_token]").val('');

    if (newRoute != null) {
        form.attr('action', newRoute);
    }

    form.attr('method', 'get').submit();
    return false;
}

function openMenu(e)
{
    e.preventDefault();
    $("body").animate({'background-position-x': '0px'}, 200, function () { });
    $(".sidebar-menu").animate({marginLeft: "0px"}, 200, function () { });
    $(".page-body").animate({marginLeft: "225px", marginRight: "-225px"}, 200, function () { });
}

function closeMenu(e)
{
    e.preventDefault();
    $("body").animate({'background-position-x': '-225px'}, 200, function () { });
    $(".sidebar-menu").animate({marginLeft: "-225px"}, 200, function () { });
    $(".page-body").animate({marginLeft: "0", marginRight: "0"}, 200, function () { });
}
