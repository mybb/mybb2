$('html').addClass('js');

$(function () {
	
	$('.nojs').hide();

	if(Modernizr.touch)
	{
		$('.radio-buttons .radio-button, .checkbox-buttons .checkbox-button').click(function() {

		});
	}

	else
	{
		$('span.icons i, a, .caption, time').powerTip({ placement: 's', smartPlacement: true });
	}

	$('.user-navigation__links, #main-menu').dropit({ submenuEl: 'div.dropdown' });
	$('.dropdown-menu').dropit({ submenuEl: 'ul.dropdown', triggerEl: 'span.dropdown-button' });
	$("input[type=number]").stepper();
	$(".password-toggle").hideShowPassword(false, true);

    $('.clear-selection a').click(function(e) {
        $('[data-moderation-content] input[type=checkbox]:checked').removeAttr('checked');
        $('[data-moderation-content] .highlight').removeClass('highlight');
        $('.inline-moderation').removeClass('floating');
    });

	$("#search .search-button").click(function(event) {
		event.preventDefault();
		$("#search .search-container").slideDown();
	});

	$(".post :checkbox").change(function() {
		$(this).closest(".post").toggleClass("highlight", this.checked);

		var checked_boxes = $('.highlight').length;

		if(checked_boxes == 1)
		{
			$('.inline-moderation').addClass('floating');
		}

		if (checked_boxes > 1)
		{
			$('li[data-moderation-multi]').show();
		} else {
			$('li[data-moderation-multi]').hide();
		}

		if(checked_boxes == 0)
		{
			$('.inline-moderation').removeClass('floating');
		}

		$('.inline-moderation .selection-count').text(' ('+checked_boxes+')')
	});

	$(".topic-list .topic :checkbox").change(function() {
		$(this).closest(".topic").toggleClass("highlight", this.checked);

		var checked_boxes = $('.highlight').length;

		if(checked_boxes == 1)
		{
			$('.inline-moderation').addClass('floating');
		}

		if (checked_boxes > 1)
		{
			$('li[data-moderation-multi]').show();
		} else {
			$('li[data-moderation-multi]').hide();
		}

		if(checked_boxes == 0)
		{
			$('.inline-moderation').removeClass('floating');
		}

		$('.inline-moderation .selection-count').text(' ('+checked_boxes+')')
	});

	$(".forum .checkbox-select :checkbox").change(function() {
		$(this).closest(".forum").toggleClass("highlight", this.checked);

		var checked_boxes = $('.highlight').length;

		if(checked_boxes == 1)
		{
			$('.inline-moderation').addClass('floating');
		}

		if(checked_boxes == 0)
		{
			$('.inline-moderation').removeClass('floating');
		}

		$('.inline-moderation .selection-count').text(' ('+checked_boxes+')');
	});

	$(".checkbox-select.check-all :checkbox").click(function() {
		$(this).closest('section').find('input[type=checkbox]').prop('checked', this.checked);
		$(this).closest('section').find('.checkbox-select').closest('.thread').toggleClass("highlight", this.checked);
		$(this).closest('section').find('.checkbox-select').closest('.forum').toggleClass("highlight", this.checked);

		var checked_boxes = $('.highlight').length;

		if(checked_boxes >= 1)
		{
			$('.inline-moderation').addClass('floating');
		}

		if(checked_boxes == 0)
		{
			$('.inline-moderation').removeClass('floating');
		}

		$('.inline-moderation .selection-count').text(' ('+checked_boxes+')');
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
