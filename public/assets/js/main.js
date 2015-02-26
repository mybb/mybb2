(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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
		$('span.icons i, a, .caption').powerTip({ placement: 's', smartPlacement: true });
	}

	$('.user-links, #main-menu').dropit({ submenuEl: 'div.dropdown' });
	$('.dropdown-menu').dropit({ submenuEl: 'ul.dropdown', triggerEl: 'span.dropdown-button' });
	$("input[type=number]").stepper();
	$(".password-toggle").hideShowPassword(false, true);

	$('.clear-selection-posts a').click(function(event) {
		event.preventDefault();
		$('.thread').find('input[type=checkbox]:checked').removeAttr('checked').closest(".post").removeClass("highlight");
		$('.inline-moderation').removeClass('floating');
	});

	$('.clear-selection-threads a').click(function(event) {
		event.preventDefault();
		$('.thread-list').find('input[type=checkbox]:checked').removeAttr('checked').closest(".thread").removeClass("highlight");
		$('.checkbox-select.check-all').find('input[type=checkbox]:checked').removeAttr('checked');
		$('.inline-moderation').removeClass('floating');
	});

	$('.clear-selection-forums a').click(function(event) {
		event.preventDefault();
		$('.forum-list').find('input[type=checkbox]:checked').removeAttr('checked').closest(".forum").removeClass("highlight");
		$('.checkbox-select.check-all').find('input[type=checkbox]:checked').removeAttr('checked');
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

		if(checked_boxes == 0)
		{
			$('.inline-moderation').removeClass('floating');
		}

		$('.inline-moderation .selection-count').text(' ('+checked_boxes+')')
	});

	$(".thread .checkbox-select :checkbox").change(function() {
		$(this).closest(".thread").toggleClass("highlight", this.checked);

		var checked_boxes = $('.highlight').length;

		if(checked_boxes == 1)
		{
			$('.inline-moderation').addClass('floating');
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

function modal(page, find) {
	if(page[0] == '/')
		page = page.substr(1);

	$.get('/'+page, function(ans) {
		// find is optional and defaults to '#content'
		if(typeof find == 'undefined') { find = '#content'; }

		var obj = $(ans);
		var html = $(find, obj).html();
		$('<div class="modalDialog">'+html+'</div>').appendTo('body').modal();
		$('.modalHide').hide();
		$("input[type=number]").stepper();	
		$(".password-toggle").hideShowPassword(false, true);
	});
}
},{}]},{},[1])
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlc1xcYnJvd3NlcmlmeVxcbm9kZV9tb2R1bGVzXFxicm93c2VyLXBhY2tcXF9wcmVsdWRlLmpzIiwianMvbWFpbi5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwiJCgnaHRtbCcpLmFkZENsYXNzKCdqcycpO1xyXG5cclxuJChmdW5jdGlvbiAoKSB7XHJcblx0XHJcblx0JCgnLm5vanMnKS5oaWRlKCk7XHJcblxyXG5cdGlmKE1vZGVybml6ci50b3VjaClcclxuXHR7XHJcblx0XHQkKCcucmFkaW8tYnV0dG9ucyAucmFkaW8tYnV0dG9uLCAuY2hlY2tib3gtYnV0dG9ucyAuY2hlY2tib3gtYnV0dG9uJykuY2xpY2soZnVuY3Rpb24oKSB7XHJcblxyXG5cdFx0fSk7XHJcblx0fVxyXG5cclxuXHRlbHNlXHJcblx0e1xyXG5cdFx0JCgnc3Bhbi5pY29ucyBpLCBhLCAuY2FwdGlvbicpLnBvd2VyVGlwKHsgcGxhY2VtZW50OiAncycsIHNtYXJ0UGxhY2VtZW50OiB0cnVlIH0pO1xyXG5cdH1cclxuXHJcblx0JCgnLnVzZXItbGlua3MsICNtYWluLW1lbnUnKS5kcm9waXQoeyBzdWJtZW51RWw6ICdkaXYuZHJvcGRvd24nIH0pO1xyXG5cdCQoJy5kcm9wZG93bi1tZW51JykuZHJvcGl0KHsgc3VibWVudUVsOiAndWwuZHJvcGRvd24nLCB0cmlnZ2VyRWw6ICdzcGFuLmRyb3Bkb3duLWJ1dHRvbicgfSk7XHJcblx0JChcImlucHV0W3R5cGU9bnVtYmVyXVwiKS5zdGVwcGVyKCk7XHJcblx0JChcIi5wYXNzd29yZC10b2dnbGVcIikuaGlkZVNob3dQYXNzd29yZChmYWxzZSwgdHJ1ZSk7XHJcblxyXG5cdCQoJy5jbGVhci1zZWxlY3Rpb24tcG9zdHMgYScpLmNsaWNrKGZ1bmN0aW9uKGV2ZW50KSB7XHJcblx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cdFx0JCgnLnRocmVhZCcpLmZpbmQoJ2lucHV0W3R5cGU9Y2hlY2tib3hdOmNoZWNrZWQnKS5yZW1vdmVBdHRyKCdjaGVja2VkJykuY2xvc2VzdChcIi5wb3N0XCIpLnJlbW92ZUNsYXNzKFwiaGlnaGxpZ2h0XCIpO1xyXG5cdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykucmVtb3ZlQ2xhc3MoJ2Zsb2F0aW5nJyk7XHJcblx0fSk7XHJcblxyXG5cdCQoJy5jbGVhci1zZWxlY3Rpb24tdGhyZWFkcyBhJykuY2xpY2soZnVuY3Rpb24oZXZlbnQpIHtcclxuXHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcblx0XHQkKCcudGhyZWFkLWxpc3QnKS5maW5kKCdpbnB1dFt0eXBlPWNoZWNrYm94XTpjaGVja2VkJykucmVtb3ZlQXR0cignY2hlY2tlZCcpLmNsb3Nlc3QoXCIudGhyZWFkXCIpLnJlbW92ZUNsYXNzKFwiaGlnaGxpZ2h0XCIpO1xyXG5cdFx0JCgnLmNoZWNrYm94LXNlbGVjdC5jaGVjay1hbGwnKS5maW5kKCdpbnB1dFt0eXBlPWNoZWNrYm94XTpjaGVja2VkJykucmVtb3ZlQXR0cignY2hlY2tlZCcpO1xyXG5cdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykucmVtb3ZlQ2xhc3MoJ2Zsb2F0aW5nJyk7XHJcblx0fSk7XHJcblxyXG5cdCQoJy5jbGVhci1zZWxlY3Rpb24tZm9ydW1zIGEnKS5jbGljayhmdW5jdGlvbihldmVudCkge1xyXG5cdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHRcdCQoJy5mb3J1bS1saXN0JykuZmluZCgnaW5wdXRbdHlwZT1jaGVja2JveF06Y2hlY2tlZCcpLnJlbW92ZUF0dHIoJ2NoZWNrZWQnKS5jbG9zZXN0KFwiLmZvcnVtXCIpLnJlbW92ZUNsYXNzKFwiaGlnaGxpZ2h0XCIpO1xyXG5cdFx0JCgnLmNoZWNrYm94LXNlbGVjdC5jaGVjay1hbGwnKS5maW5kKCdpbnB1dFt0eXBlPWNoZWNrYm94XTpjaGVja2VkJykucmVtb3ZlQXR0cignY2hlY2tlZCcpO1xyXG5cdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykucmVtb3ZlQ2xhc3MoJ2Zsb2F0aW5nJyk7XHJcblx0fSk7XHJcblxyXG5cdCQoXCIjc2VhcmNoIC5zZWFyY2gtYnV0dG9uXCIpLmNsaWNrKGZ1bmN0aW9uKGV2ZW50KSB7XHJcblx0XHRldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cdFx0JChcIiNzZWFyY2ggLnNlYXJjaC1jb250YWluZXJcIikuc2xpZGVEb3duKCk7XHJcblx0fSk7XHJcblxyXG5cdCQoXCIucG9zdCA6Y2hlY2tib3hcIikuY2hhbmdlKGZ1bmN0aW9uKCkge1xyXG5cdFx0JCh0aGlzKS5jbG9zZXN0KFwiLnBvc3RcIikudG9nZ2xlQ2xhc3MoXCJoaWdobGlnaHRcIiwgdGhpcy5jaGVja2VkKTtcclxuXHJcblx0XHR2YXIgY2hlY2tlZF9ib3hlcyA9ICQoJy5oaWdobGlnaHQnKS5sZW5ndGg7XHJcblxyXG5cdFx0aWYoY2hlY2tlZF9ib3hlcyA9PSAxKVxyXG5cdFx0e1xyXG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5hZGRDbGFzcygnZmxvYXRpbmcnKTtcclxuXHRcdH1cclxuXHJcblx0XHRpZihjaGVja2VkX2JveGVzID09IDApXHJcblx0XHR7XHJcblx0XHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbicpLnJlbW92ZUNsYXNzKCdmbG9hdGluZycpO1xyXG5cdFx0fVxyXG5cclxuXHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbiAuc2VsZWN0aW9uLWNvdW50JykudGV4dCgnICgnK2NoZWNrZWRfYm94ZXMrJyknKVxyXG5cdH0pO1xyXG5cclxuXHQkKFwiLnRocmVhZCAuY2hlY2tib3gtc2VsZWN0IDpjaGVja2JveFwiKS5jaGFuZ2UoZnVuY3Rpb24oKSB7XHJcblx0XHQkKHRoaXMpLmNsb3Nlc3QoXCIudGhyZWFkXCIpLnRvZ2dsZUNsYXNzKFwiaGlnaGxpZ2h0XCIsIHRoaXMuY2hlY2tlZCk7XHJcblxyXG5cdFx0dmFyIGNoZWNrZWRfYm94ZXMgPSAkKCcuaGlnaGxpZ2h0JykubGVuZ3RoO1xyXG5cclxuXHRcdGlmKGNoZWNrZWRfYm94ZXMgPT0gMSlcclxuXHRcdHtcclxuXHRcdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykuYWRkQ2xhc3MoJ2Zsb2F0aW5nJyk7XHJcblx0XHR9XHJcblxyXG5cdFx0aWYoY2hlY2tlZF9ib3hlcyA9PSAwKVxyXG5cdFx0e1xyXG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5yZW1vdmVDbGFzcygnZmxvYXRpbmcnKTtcclxuXHRcdH1cclxuXHJcblx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24gLnNlbGVjdGlvbi1jb3VudCcpLnRleHQoJyAoJytjaGVja2VkX2JveGVzKycpJylcclxuXHR9KTtcclxuXHJcblx0JChcIi5mb3J1bSAuY2hlY2tib3gtc2VsZWN0IDpjaGVja2JveFwiKS5jaGFuZ2UoZnVuY3Rpb24oKSB7XHJcblx0XHQkKHRoaXMpLmNsb3Nlc3QoXCIuZm9ydW1cIikudG9nZ2xlQ2xhc3MoXCJoaWdobGlnaHRcIiwgdGhpcy5jaGVja2VkKTtcclxuXHJcblx0XHR2YXIgY2hlY2tlZF9ib3hlcyA9ICQoJy5oaWdobGlnaHQnKS5sZW5ndGg7XHJcblxyXG5cdFx0aWYoY2hlY2tlZF9ib3hlcyA9PSAxKVxyXG5cdFx0e1xyXG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5hZGRDbGFzcygnZmxvYXRpbmcnKTtcclxuXHRcdH1cclxuXHJcblx0XHRpZihjaGVja2VkX2JveGVzID09IDApXHJcblx0XHR7XHJcblx0XHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbicpLnJlbW92ZUNsYXNzKCdmbG9hdGluZycpO1xyXG5cdFx0fVxyXG5cclxuXHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbiAuc2VsZWN0aW9uLWNvdW50JykudGV4dCgnICgnK2NoZWNrZWRfYm94ZXMrJyknKTtcclxuXHR9KTtcclxuXHJcblx0JChcIi5jaGVja2JveC1zZWxlY3QuY2hlY2stYWxsIDpjaGVja2JveFwiKS5jbGljayhmdW5jdGlvbigpIHtcclxuXHRcdCQodGhpcykuY2xvc2VzdCgnc2VjdGlvbicpLmZpbmQoJ2lucHV0W3R5cGU9Y2hlY2tib3hdJykucHJvcCgnY2hlY2tlZCcsIHRoaXMuY2hlY2tlZCk7XHJcblx0XHQkKHRoaXMpLmNsb3Nlc3QoJ3NlY3Rpb24nKS5maW5kKCcuY2hlY2tib3gtc2VsZWN0JykuY2xvc2VzdCgnLnRocmVhZCcpLnRvZ2dsZUNsYXNzKFwiaGlnaGxpZ2h0XCIsIHRoaXMuY2hlY2tlZCk7XHJcblx0XHQkKHRoaXMpLmNsb3Nlc3QoJ3NlY3Rpb24nKS5maW5kKCcuY2hlY2tib3gtc2VsZWN0JykuY2xvc2VzdCgnLmZvcnVtJykudG9nZ2xlQ2xhc3MoXCJoaWdobGlnaHRcIiwgdGhpcy5jaGVja2VkKTtcclxuXHJcblx0XHR2YXIgY2hlY2tlZF9ib3hlcyA9ICQoJy5oaWdobGlnaHQnKS5sZW5ndGg7XHJcblxyXG5cdFx0aWYoY2hlY2tlZF9ib3hlcyA+PSAxKVxyXG5cdFx0e1xyXG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5hZGRDbGFzcygnZmxvYXRpbmcnKTtcclxuXHRcdH1cclxuXHJcblx0XHRpZihjaGVja2VkX2JveGVzID09IDApXHJcblx0XHR7XHJcblx0XHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbicpLnJlbW92ZUNsYXNzKCdmbG9hdGluZycpO1xyXG5cdFx0fVxyXG5cclxuXHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbiAuc2VsZWN0aW9uLWNvdW50JykudGV4dCgnICgnK2NoZWNrZWRfYm94ZXMrJyknKTtcclxuXHR9KTtcclxuXHJcbi8qXHQkKCcucG9zdC5yZXBseSB0ZXh0YXJlYS5lZGl0b3IsIC5mb3JtIHRleHRhcmVhLmVkaXRvcicpLnNjZWRpdG9yKHtcclxuXHRcdHBsdWdpbnM6ICdiYmNvZGUnLFxyXG5cdFx0c3R5bGU6ICdqcy92ZW5kb3Ivc2NlZGl0b3IvanF1ZXJ5LnNjZWRpdG9yLmRlZmF1bHQubWluLmNzcycsXHJcblx0XHRlbW90aWNvbnNSb290OiAnYXNzZXRzL2ltYWdlcy8nLFxyXG5cdFx0dG9vbGJhcjogJ2JvbGQsaXRhbGljLHVuZGVybGluZXxmb250LHNpemUsY29sb3IscmVtb3ZlZm9ybWF0fGxlZnQsY2VudGVyLHJpZ2h0fGltYWdlLGxpbmssdW5saW5rfGVtb3RpY29uLHlvdXR1YmV8YnVsbGV0bGlzdCxvcmRlcmVkbGlzdHxxdW90ZSxjb2RlfHNvdXJjZScsXHJcblx0XHRyZXNpemVXaWR0aDogZmFsc2UsXHJcblx0XHRhdXRvZm9jdXM6IGZhbHNlLFxyXG5cdFx0YXV0b2ZvY3VzRW5kOiBmYWxzZVxyXG5cdH0pOyovXHJcbn0pO1xyXG5cclxuZnVuY3Rpb24gbW9kYWwocGFnZSwgZmluZCkge1xyXG5cdGlmKHBhZ2VbMF0gPT0gJy8nKVxyXG5cdFx0cGFnZSA9IHBhZ2Uuc3Vic3RyKDEpO1xyXG5cclxuXHQkLmdldCgnLycrcGFnZSwgZnVuY3Rpb24oYW5zKSB7XHJcblx0XHQvLyBmaW5kIGlzIG9wdGlvbmFsIGFuZCBkZWZhdWx0cyB0byAnI2NvbnRlbnQnXHJcblx0XHRpZih0eXBlb2YgZmluZCA9PSAndW5kZWZpbmVkJykgeyBmaW5kID0gJyNjb250ZW50JzsgfVxyXG5cclxuXHRcdHZhciBvYmogPSAkKGFucyk7XHJcblx0XHR2YXIgaHRtbCA9ICQoZmluZCwgb2JqKS5odG1sKCk7XHJcblx0XHQkKCc8ZGl2IGNsYXNzPVwibW9kYWxEaWFsb2dcIj4nK2h0bWwrJzwvZGl2PicpLmFwcGVuZFRvKCdib2R5JykubW9kYWwoKTtcclxuXHRcdCQoJy5tb2RhbEhpZGUnKS5oaWRlKCk7XHJcblx0XHQkKFwiaW5wdXRbdHlwZT1udW1iZXJdXCIpLnN0ZXBwZXIoKTtcdFxyXG5cdFx0JChcIi5wYXNzd29yZC10b2dnbGVcIikuaGlkZVNob3dQYXNzd29yZChmYWxzZSwgdHJ1ZSk7XHJcblx0fSk7XHJcbn0iXX0=
