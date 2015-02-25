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
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy9icm93c2VyaWZ5L25vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJqcy9tYWluLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FDQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCIkKCdodG1sJykuYWRkQ2xhc3MoJ2pzJyk7XG5cbiQoZnVuY3Rpb24gKCkge1xuXHRcblx0JCgnLm5vanMnKS5oaWRlKCk7XG5cblx0aWYoTW9kZXJuaXpyLnRvdWNoKVxuXHR7XG5cdFx0JCgnLnJhZGlvLWJ1dHRvbnMgLnJhZGlvLWJ1dHRvbiwgLmNoZWNrYm94LWJ1dHRvbnMgLmNoZWNrYm94LWJ1dHRvbicpLmNsaWNrKGZ1bmN0aW9uKCkge1xuXG5cdFx0fSk7XG5cdH1cblxuXHRlbHNlXG5cdHtcblx0XHQkKCdzcGFuLmljb25zIGksIGEsIC5jYXB0aW9uJykucG93ZXJUaXAoeyBwbGFjZW1lbnQ6ICdzJywgc21hcnRQbGFjZW1lbnQ6IHRydWUgfSk7XG5cdH1cblxuXHQkKCcudXNlci1saW5rcywgI21haW4tbWVudScpLmRyb3BpdCh7IHN1Ym1lbnVFbDogJ2Rpdi5kcm9wZG93bicgfSk7XG5cdCQoJy5kcm9wZG93bi1tZW51JykuZHJvcGl0KHsgc3VibWVudUVsOiAndWwuZHJvcGRvd24nLCB0cmlnZ2VyRWw6ICdzcGFuLmRyb3Bkb3duLWJ1dHRvbicgfSk7XG5cdCQoXCJpbnB1dFt0eXBlPW51bWJlcl1cIikuc3RlcHBlcigpO1xuXHQkKFwiLnBhc3N3b3JkLXRvZ2dsZVwiKS5oaWRlU2hvd1Bhc3N3b3JkKGZhbHNlLCB0cnVlKTtcblxuXHQkKCcuY2xlYXItc2VsZWN0aW9uLXBvc3RzIGEnKS5jbGljayhmdW5jdGlvbihldmVudCkge1xuXHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0JCgnLnRocmVhZCcpLmZpbmQoJ2lucHV0W3R5cGU9Y2hlY2tib3hdOmNoZWNrZWQnKS5yZW1vdmVBdHRyKCdjaGVja2VkJykuY2xvc2VzdChcIi5wb3N0XCIpLnJlbW92ZUNsYXNzKFwiaGlnaGxpZ2h0XCIpO1xuXHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbicpLnJlbW92ZUNsYXNzKCdmbG9hdGluZycpO1xuXHR9KTtcblxuXHQkKCcuY2xlYXItc2VsZWN0aW9uLXRocmVhZHMgYScpLmNsaWNrKGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHQkKCcudGhyZWFkLWxpc3QnKS5maW5kKCdpbnB1dFt0eXBlPWNoZWNrYm94XTpjaGVja2VkJykucmVtb3ZlQXR0cignY2hlY2tlZCcpLmNsb3Nlc3QoXCIudGhyZWFkXCIpLnJlbW92ZUNsYXNzKFwiaGlnaGxpZ2h0XCIpO1xuXHRcdCQoJy5jaGVja2JveC1zZWxlY3QuY2hlY2stYWxsJykuZmluZCgnaW5wdXRbdHlwZT1jaGVja2JveF06Y2hlY2tlZCcpLnJlbW92ZUF0dHIoJ2NoZWNrZWQnKTtcblx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5yZW1vdmVDbGFzcygnZmxvYXRpbmcnKTtcblx0fSk7XG5cblx0JCgnLmNsZWFyLXNlbGVjdGlvbi1mb3J1bXMgYScpLmNsaWNrKGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHQkKCcuZm9ydW0tbGlzdCcpLmZpbmQoJ2lucHV0W3R5cGU9Y2hlY2tib3hdOmNoZWNrZWQnKS5yZW1vdmVBdHRyKCdjaGVja2VkJykuY2xvc2VzdChcIi5mb3J1bVwiKS5yZW1vdmVDbGFzcyhcImhpZ2hsaWdodFwiKTtcblx0XHQkKCcuY2hlY2tib3gtc2VsZWN0LmNoZWNrLWFsbCcpLmZpbmQoJ2lucHV0W3R5cGU9Y2hlY2tib3hdOmNoZWNrZWQnKS5yZW1vdmVBdHRyKCdjaGVja2VkJyk7XG5cdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykucmVtb3ZlQ2xhc3MoJ2Zsb2F0aW5nJyk7XG5cdH0pO1xuXG5cdCQoXCIjc2VhcmNoIC5zZWFyY2gtYnV0dG9uXCIpLmNsaWNrKGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHQkKFwiI3NlYXJjaCAuc2VhcmNoLWNvbnRhaW5lclwiKS5zbGlkZURvd24oKTtcblx0fSk7XG5cblx0JChcIi5wb3N0IDpjaGVja2JveFwiKS5jaGFuZ2UoZnVuY3Rpb24oKSB7XG5cdFx0JCh0aGlzKS5jbG9zZXN0KFwiLnBvc3RcIikudG9nZ2xlQ2xhc3MoXCJoaWdobGlnaHRcIiwgdGhpcy5jaGVja2VkKTtcblxuXHRcdHZhciBjaGVja2VkX2JveGVzID0gJCgnLmhpZ2hsaWdodCcpLmxlbmd0aDtcblxuXHRcdGlmKGNoZWNrZWRfYm94ZXMgPT0gMSlcblx0XHR7XG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5hZGRDbGFzcygnZmxvYXRpbmcnKTtcblx0XHR9XG5cblx0XHRpZihjaGVja2VkX2JveGVzID09IDApXG5cdFx0e1xuXHRcdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykucmVtb3ZlQ2xhc3MoJ2Zsb2F0aW5nJyk7XG5cdFx0fVxuXG5cdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uIC5zZWxlY3Rpb24tY291bnQnKS50ZXh0KCcgKCcrY2hlY2tlZF9ib3hlcysnKScpXG5cdH0pO1xuXG5cdCQoXCIudGhyZWFkIC5jaGVja2JveC1zZWxlY3QgOmNoZWNrYm94XCIpLmNoYW5nZShmdW5jdGlvbigpIHtcblx0XHQkKHRoaXMpLmNsb3Nlc3QoXCIudGhyZWFkXCIpLnRvZ2dsZUNsYXNzKFwiaGlnaGxpZ2h0XCIsIHRoaXMuY2hlY2tlZCk7XG5cblx0XHR2YXIgY2hlY2tlZF9ib3hlcyA9ICQoJy5oaWdobGlnaHQnKS5sZW5ndGg7XG5cblx0XHRpZihjaGVja2VkX2JveGVzID09IDEpXG5cdFx0e1xuXHRcdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykuYWRkQ2xhc3MoJ2Zsb2F0aW5nJyk7XG5cdFx0fVxuXG5cdFx0aWYoY2hlY2tlZF9ib3hlcyA9PSAwKVxuXHRcdHtcblx0XHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbicpLnJlbW92ZUNsYXNzKCdmbG9hdGluZycpO1xuXHRcdH1cblxuXHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbiAuc2VsZWN0aW9uLWNvdW50JykudGV4dCgnICgnK2NoZWNrZWRfYm94ZXMrJyknKVxuXHR9KTtcblxuXHQkKFwiLmZvcnVtIC5jaGVja2JveC1zZWxlY3QgOmNoZWNrYm94XCIpLmNoYW5nZShmdW5jdGlvbigpIHtcblx0XHQkKHRoaXMpLmNsb3Nlc3QoXCIuZm9ydW1cIikudG9nZ2xlQ2xhc3MoXCJoaWdobGlnaHRcIiwgdGhpcy5jaGVja2VkKTtcblxuXHRcdHZhciBjaGVja2VkX2JveGVzID0gJCgnLmhpZ2hsaWdodCcpLmxlbmd0aDtcblxuXHRcdGlmKGNoZWNrZWRfYm94ZXMgPT0gMSlcblx0XHR7XG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5hZGRDbGFzcygnZmxvYXRpbmcnKTtcblx0XHR9XG5cblx0XHRpZihjaGVja2VkX2JveGVzID09IDApXG5cdFx0e1xuXHRcdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uJykucmVtb3ZlQ2xhc3MoJ2Zsb2F0aW5nJyk7XG5cdFx0fVxuXG5cdFx0JCgnLmlubGluZS1tb2RlcmF0aW9uIC5zZWxlY3Rpb24tY291bnQnKS50ZXh0KCcgKCcrY2hlY2tlZF9ib3hlcysnKScpO1xuXHR9KTtcblxuXHQkKFwiLmNoZWNrYm94LXNlbGVjdC5jaGVjay1hbGwgOmNoZWNrYm94XCIpLmNsaWNrKGZ1bmN0aW9uKCkge1xuXHRcdCQodGhpcykuY2xvc2VzdCgnc2VjdGlvbicpLmZpbmQoJ2lucHV0W3R5cGU9Y2hlY2tib3hdJykucHJvcCgnY2hlY2tlZCcsIHRoaXMuY2hlY2tlZCk7XG5cdFx0JCh0aGlzKS5jbG9zZXN0KCdzZWN0aW9uJykuZmluZCgnLmNoZWNrYm94LXNlbGVjdCcpLmNsb3Nlc3QoJy50aHJlYWQnKS50b2dnbGVDbGFzcyhcImhpZ2hsaWdodFwiLCB0aGlzLmNoZWNrZWQpO1xuXHRcdCQodGhpcykuY2xvc2VzdCgnc2VjdGlvbicpLmZpbmQoJy5jaGVja2JveC1zZWxlY3QnKS5jbG9zZXN0KCcuZm9ydW0nKS50b2dnbGVDbGFzcyhcImhpZ2hsaWdodFwiLCB0aGlzLmNoZWNrZWQpO1xuXG5cdFx0dmFyIGNoZWNrZWRfYm94ZXMgPSAkKCcuaGlnaGxpZ2h0JykubGVuZ3RoO1xuXG5cdFx0aWYoY2hlY2tlZF9ib3hlcyA+PSAxKVxuXHRcdHtcblx0XHRcdCQoJy5pbmxpbmUtbW9kZXJhdGlvbicpLmFkZENsYXNzKCdmbG9hdGluZycpO1xuXHRcdH1cblxuXHRcdGlmKGNoZWNrZWRfYm94ZXMgPT0gMClcblx0XHR7XG5cdFx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24nKS5yZW1vdmVDbGFzcygnZmxvYXRpbmcnKTtcblx0XHR9XG5cblx0XHQkKCcuaW5saW5lLW1vZGVyYXRpb24gLnNlbGVjdGlvbi1jb3VudCcpLnRleHQoJyAoJytjaGVja2VkX2JveGVzKycpJyk7XG5cdH0pO1xuXG4vKlx0JCgnLnBvc3QucmVwbHkgdGV4dGFyZWEuZWRpdG9yLCAuZm9ybSB0ZXh0YXJlYS5lZGl0b3InKS5zY2VkaXRvcih7XG5cdFx0cGx1Z2luczogJ2JiY29kZScsXG5cdFx0c3R5bGU6ICdqcy92ZW5kb3Ivc2NlZGl0b3IvanF1ZXJ5LnNjZWRpdG9yLmRlZmF1bHQubWluLmNzcycsXG5cdFx0ZW1vdGljb25zUm9vdDogJ2Fzc2V0cy9pbWFnZXMvJyxcblx0XHR0b29sYmFyOiAnYm9sZCxpdGFsaWMsdW5kZXJsaW5lfGZvbnQsc2l6ZSxjb2xvcixyZW1vdmVmb3JtYXR8bGVmdCxjZW50ZXIscmlnaHR8aW1hZ2UsbGluayx1bmxpbmt8ZW1vdGljb24seW91dHViZXxidWxsZXRsaXN0LG9yZGVyZWRsaXN0fHF1b3RlLGNvZGV8c291cmNlJyxcblx0XHRyZXNpemVXaWR0aDogZmFsc2UsXG5cdFx0YXV0b2ZvY3VzOiBmYWxzZSxcblx0XHRhdXRvZm9jdXNFbmQ6IGZhbHNlXG5cdH0pOyovXG59KTtcblxuZnVuY3Rpb24gbW9kYWwocGFnZSwgZmluZCkge1xuXHRpZihwYWdlWzBdID09ICcvJylcblx0XHRwYWdlID0gcGFnZS5zdWJzdHIoMSk7XG5cblx0JC5nZXQoJy8nK3BhZ2UsIGZ1bmN0aW9uKGFucykge1xuXHRcdC8vIGZpbmQgaXMgb3B0aW9uYWwgYW5kIGRlZmF1bHRzIHRvICcjY29udGVudCdcblx0XHRpZih0eXBlb2YgZmluZCA9PSAndW5kZWZpbmVkJykgeyBmaW5kID0gJyNjb250ZW50JzsgfVxuXG5cdFx0dmFyIG9iaiA9ICQoYW5zKTtcblx0XHR2YXIgaHRtbCA9ICQoZmluZCwgb2JqKS5odG1sKCk7XG5cdFx0JCgnPGRpdiBjbGFzcz1cIm1vZGFsRGlhbG9nXCI+JytodG1sKyc8L2Rpdj4nKS5hcHBlbmRUbygnYm9keScpLm1vZGFsKCk7XG5cdFx0JCgnLm1vZGFsSGlkZScpLmhpZGUoKTtcblx0XHQkKFwiaW5wdXRbdHlwZT1udW1iZXJdXCIpLnN0ZXBwZXIoKTtcdFxuXHRcdCQoXCIucGFzc3dvcmQtdG9nZ2xlXCIpLmhpZGVTaG93UGFzc3dvcmQoZmFsc2UsIHRydWUpO1xuXHR9KTtcbn0iXX0=
