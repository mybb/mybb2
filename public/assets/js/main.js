(function($, window) {
    window.MyBB = window.MyBB || {};
    
	window.MyBB.Modals = function Modals()
	{
		$("*[data-modal]").on("click", this.toggleModal).bind(this);
	};

	window.MyBB.Modals.prototype.toggleModal = function toggleModal(event) {
		event.preventDefault();

		// Check to make sure we're clicking the link and not a child of the link
		if(event.target.nodeName === "A")
		{
			// Woohoo, it's the link!
			var modalOpener = event.target,
				modalSelector = $(modalOpener).data("modal"),
				modalFind = $(modalOpener).data("modal-find"),
				modal = $('<div/>', {
	    			"class": "modal-dialog",
				}),
				modalContent = "";
		} else {
			// Nope, it's one of those darn children.
			var modalOpener = event.target,
				modalSelector = $(modalOpener).parent().data("modal"),
				modalFind = $(modalOpener).data("modal-find"),
				modal = $('<div/>', {
	    			"class": "modal-dialog",
				}),
				modalContent = "";
		}

		if (modalSelector.substring(0, 1) === "." || modalSelector.substring(0, 1) === "#") {
			// Assume using a local, existing HTML element.
			modalContent = $(modalSelector).html();
			modal.html(modalContent);
			modal.appendTo("body").modal();
			$('.modalHide').hide();
			$("input[type=number]").stepper();
			$(".password-toggle").hideShowPassword(false, true);
		} else {
			// Assume modal content is coming from an AJAX request

			// data-modal-find is optional, default to "#content"
			if (modalFind === undefined) {
				modalFind = "#content";
			}

			$.get('/'+modalSelector, function(response) {
				var responseObject = $(response);

				modalContent = $(modalFind, responseObject).html();
				modal.html(modalContent);
				modal.appendTo("body").modal();
				$('.modalHide').hide();
				$("input[type=number]").stepper();
				$(".password-toggle").hideShowPassword(false, true);
			});
		}
	};
    
    var modals = new window.MyBB.Modals(); // TODO: put this elsewhere :)
})(jQuery, window);
(function($, window) {
    window.MyBB = window.MyBB || {};
    
	window.MyBB.Posts = function Posts()
	{
		// Show and hide posts
		$(".postToggle").on("click", this.togglePost).bind(this);
	};

	// Show and hide posts
	window.MyBB.Posts.prototype.togglePost = function togglePost(event) {
		event.preventDefault();
		// Are we minimized or not?
		if($(event.target).hasClass("fa-minus"))
		{
			// Perhaps instead of hide, apply a CSS class?
			$(event.target).parent().parent().siblings().hide();
			// Make button a plus sign for expanding
			$(event.target).addClass("fa-plus");
			$(event.target).removeClass("fa-minus");

		} else {
			// We like this person again
			$(event.target).parent().parent().siblings().show();
			// Just in case we change our mind again, show the hide button
			$(event.target).addClass("fa-minus");
			$(event.target).removeClass("fa-show");	
		}
	};

	var posts = new window.MyBB.Posts();

})(jQuery, window);
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