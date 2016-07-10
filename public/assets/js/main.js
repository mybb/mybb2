(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Cookie = {
		cookiePrefix: '',
		cookiePath: '/',
		cookieDomain: '',

		init: function () {
			MyBB.Settings = MyBB.Settings || {};
			if (typeof MyBB.Settings.cookiePrefix != 'undefined') {
				this.cookiePrefix = MyBB.Settings.cookiePrefix;
			}
			if (typeof MyBB.Settings.cookiePath != 'undefined') {
				this.cookiePath = MyBB.Settings.cookiePath;
			}
			if (typeof MyBB.Settings.cookieDomain != 'undefined') {
				this.cookieDomain = MyBB.Settings.cookieDomain;
			}
		},

		get: function (name) {
			this.init();

			name = this.cookiePrefix + name;
			return $.cookie(name);
		},

		set: function (name, value, expires) {
			this.init();

			name = this.cookiePrefix + name;
			if (!expires) {
				expires = 157680000; // 5*365*24*60*60 => 5 years
			}

			expire = new Date();
			expire.setTime(expire.getTime() + (expires * 1000));

			options = {
				expires: expire,
				path: this.cookiePath,
				domain: this.cookieDomain
			};

			return $.cookie(name, value, options);
		},

		unset: function (name) {
			this.init();

			name = this.cookiePrefix + name;

			options = {
				path: this.cookiePath,
				domain: this.cookieDomain
			};
			return $.removeCookie(name, options);
		}
	}
})
(jQuery, window);
(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Spinner = {
		inProgresses: 0,
		add: function () {
			this.inProgresses++;
			if (this.inProgresses == 1) {
				$("#spinner").show();
			}
		},
		remove: function () {
			this.inProgresses--;
			if (this.inProgresses == 0) {
				$("#spinner").hide();
			}
		}
	}
})
(jQuery, window);
(function($, window) {
    window.MyBB = window.MyBB || {};
    
	window.MyBB.Modals = function Modals()
	{
		$("*[data-modal]").on("click", this.toggleModal).bind(this);
		$.modal.defaults.closeText = 'x';
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
					closeText: ''
				}),
				modalContent = "";
		} else {
			// Nope, it's one of those darn children.
			var modalOpener = event.target,
				modalSelector = $(modalOpener).parent().data("modal"),
				modalFind = $(modalOpener).data("modal-find"),
				modal = $('<div/>', {
	    			"class": "modal-dialog",
					closeText: ''
				}),
				modalContent = "";
		}

		if (modalSelector.substring(0, 1) === "." || modalSelector.substring(0, 1) === "#") {
			// Assume using a local, existing HTML element.
			modalContent = $(modalSelector).html();
			modal.html(modalContent);
			modal.appendTo("body").modal({
				zIndex: 1000,
				closeText: ''
			});
			$('.modalHide').hide();
			$("input[type=number]").stepper();
			$(".password-toggle").hideShowPassword(false, true);
			new window.MyBB.Avatar();
		} else {
			// Assume modal content is coming from an AJAX request

			// data-modal-find is optional, default to "#content"
			if (modalFind === undefined) {
				modalFind = "#content";
			}

			MyBB.Spinner.add();

			var modalParams = $(event.currentTarget).attr('data-modal-params');
			if (modalParams) {
				modalParams = JSON.parse(modalParams);
				console.log(modalParams);
			} else {
				modalParams = {};
			}

			$.get('/'+modalSelector, modalParams, function(response) {
				var responseObject = $(response);

				modalContent = $(modalFind, responseObject).html();
				modal.html(modalContent);
				modal.appendTo("body").modal({
					zIndex: 1000,
					closeText: ''
				});
				$('.modalHide').hide();
				$("input[type=number]").stepper();
				$(".password-toggle").hideShowPassword(false, true);
				new window.MyBB.Avatar();

				// Remove modal after close
				modal.on($.modal.CLOSE, function() {
					$(this).remove();
				})
			}).always(function() {
				MyBB.Spinner.remove();
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


		// Confirm Delete
		$(".delete a").on("click", $.proxy(this.confirmDelete, this));
	};

	// Show and hide posts
	window.MyBB.Posts.prototype.togglePost = function togglePost(event) {
		event.preventDefault();
		// Are we minimized or not?
		if($(event.target).hasClass("fa-minus"))
		{
			// Perhaps instead of hide, apply a CSS class?
			$(event.target).parent().parent().parent().addClass("post--hidden");
			// Make button a plus sign for expanding
			$(event.target).addClass("fa-plus");
			$(event.target).removeClass("fa-minus");

		} else {
			// We like this person again
			$(event.target).parent().parent().parent().removeClass("post--hidden");
			// Just in case we change our mind again, show the hide button
			$(event.target).addClass("fa-minus");
			$(event.target).removeClass("fa-show");
		}
	};

	// Confirm Delete
	window.MyBB.Posts.prototype.confirmDelete = function confirmDelete(event) {
		return confirm(Lang.get('topic.confirmDelete'));
	};

	var posts = new window.MyBB.Posts();

})(jQuery, window);
(function($, window) {
    window.MyBB = window.MyBB || {};
    
	window.MyBB.Polls = function Polls()
	{
		this.optionElement = $('#option-simple').clone().attr('id', '').removeClass('hidden').addClass('poll-option').hide();
		$('#option-simple').remove();

		this.removeOption($('#add-poll .poll-option'));

		$('#new-option').click($.proxy(this.addOption, this));

		$('#poll-maximum-options').hide();

		$('#poll-is-multiple').change($.proxy(this.toggleMaxOptionsInput, this)).change();

		var $addPollButton = $("#add-poll-button");
		$addPollButton.click($.proxy(this.toggleAddPoll, this));
		if($addPollButton.length) {
			if($('#add-poll-input').val() === '1') {
				$('#add-poll').slideDown();
			}
		}

		this.timePicker();
	};

	window.MyBB.Polls.prototype.timePicker = function timePicker() {
		$('#poll-end-at').datetimepicker({
			format: 'Y-m-d H:i:s',
			lang: 'mybb',
			i18n: {
				mybb: {
					months: [
						Lang.get('general.months.january'),
						Lang.get('general.months.february'),
						Lang.get('general.months.march'),
						Lang.get('general.months.april'),
						Lang.get('general.months.may'),
						Lang.get('general.months.june'),
						Lang.get('general.months.july'),
						Lang.get('general.months.august'),
						Lang.get('general.months.september'),
						Lang.get('general.months.october'),
						Lang.get('general.months.november'),
						Lang.get('general.months.december')
					],
					dayOfWeek: [
						Lang.get('general.dayOfWeek.sun'),
						Lang.get('general.dayOfWeek.mon'),
						Lang.get('general.dayOfWeek.tue'),
						Lang.get('general.dayOfWeek.wed'),
						Lang.get('general.dayOfWeek.thu'),
						Lang.get('general.dayOfWeek.fri'),
						Lang.get('general.dayOfWeek.sat')
					]
				}
			},
			minDate: 0
		});
	};

	window.MyBB.Polls.prototype.toggleAddPoll = function toggleAddPoll() {
		if($('#add-poll-input').val() === '1') {
			$('#add-poll-input').val(0);
			$('#add-poll').slideUp();
		} else {
			$('#add-poll-input').val(1);
			$('#add-poll').slideDown();
		}
		return false;
	};

	window.MyBB.Polls.prototype.addOption = function addOption(event) {
		var num_options = $('#add-poll .poll-option').length;
		if(num_options >= 10) { // TODO: settings
			alert(Lang.choice('poll.errorManyOptions', 10)); // TODO: JS Error
			return false;
		}
		var $option = this.optionElement.clone();
		$option.find('input').attr('name', 'option['+(num_options+1)+']');
		$('#add-poll .poll-option').last().after($option);
		$option.slideDown();
		this.removeOption($option);
		return false;
	};

	window.MyBB.Polls.prototype.removeOption = function bindRemoveOption($parent) {
		$parent.find('.remove-option').click($.proxy(function(event) {
			var $me = $(event.target),
				$myParent = $me.parents('.poll-option');
			if($('.poll-option').length <= 2) // TODO: settings
			{
				alert(Lang.choice('poll.errorFewOptions', 2)); // TODO: JS Error
				return false;
			}

			$myParent.slideUp(500);

			setTimeout($.proxy(function() {
				$myParent.remove();
				this.fixOptionsName();
			}, this), 500);
		}, this));
		// if(!Modernizr.touch) {
		// 	$parent.find('.remove-option').powerTip({ placement: 's', smartPlacement: true });
		// }
	};

	window.MyBB.Polls.prototype.fixOptionsName = function() {
		var i = 0;
		$('#add-poll .poll-option').each(function() {
			i++;
			$(this).find('input').attr('name', 'option['+i+']');
		});
	};

	window.MyBB.Polls.prototype.toggleMaxOptionsInput = function toggleMaxOptionsInput(event) {
		me = event.target;
		if($(me).is(':checked')) {
			$('#poll-maximum-options').slideDown();
		}
		else {
			$('#poll-maximum-options').slideUp();
		}
	};

	var polls = new window.MyBB.Polls();

})(jQuery, window);
(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Quotes = function Quotes() {

		// MultiQuote
		$(".quote-button").on("click", this.multiQuoteButton.bind(this));

		this.showQuoteBar();

		$(".quote-bar__select").on("click", $.proxy(this.addQuotes, this));
		$(".quote-bar__view").on("click", $.proxy(this.viewQuotes, this));
		$(".quote-bar__deselect").on("click", $.proxy(this.removeQuotes, this));

		$('.quick-quote .fast').on('click', $.proxy(this.quickQuote, this));
		$('.quick-quote .add').on('click', $.proxy(this.quickAddQuote, this));

		$('.quote__select').on("click", $.proxy(this.quoteAdd, this));
		$('.quote__remove').on("click", $.proxy(this.quoteRemove, this));
		$("body").on("mouseup", $.proxy(this.checkQuickQuote, this));

		this.quoteButtons();
	};

	window.MyBB.Quotes.prototype.quickQuote = function quickQuote(event) {
		var $me = $(event.target);
		if (!$me.hasClass('quick-quote')) {
			$me = $me.parents('.quick-quote');
		}

		var $post = $me.parents('.post');

		if ($me.data('content')) {
			$content = $('<div/>');
			$content.html($me.data('content'));
			this.addQuote($post.data('postid'), $post.data('type'), $content.text());
		}
		this.hideQuickQuote();
	}

	window.MyBB.Quotes.prototype.quickAddQuote = function quickAddQuote(event) {
		var $me = $(event.target),
			quotes = this.getQuotes();
		if (!$me.hasClass('quick-quote')) {
			$me = $me.parents('.quick-quote');
		}

		var $post = $me.parents('.post');

		if ($me.data('content')) {
			$content = $('<div/>');
			$content.html($me.data('content'));
			quotes.push({
				'id': $post.data('type') + '_' + $post.data('postid'),
				'data': $content.text()
			});
			MyBB.Cookie.set('quotes', JSON.stringify(quotes));

			this.showQuoteBar();
		}
		this.hideQuickQuote();
	}

	window.MyBB.Quotes.prototype.checkQuickQuote = function checkQuickQuote(event) {
		var $me = $(event.target);
		if ($me.hasClass('quick-quote') || $me.parents('.quick-quote').length) {
			return false;
		}
		if (!$me.hasClass('post')) {
			$me = $me.parents('.post');
		}

		if ($me && $me.length) {
			var pid = $me.data('postid');

			if ($.trim(window.getSelection().toString())) {
				if (elementContainsSelection($me.find('.post__body')[0])) {
					this.showQuickQuote(pid);
				}
				else {
					this.hideQuickQuote();
				}
			}
			else {
				this.hideQuickQuote();
			}
		}
		else {
			this.hideQuickQuote();
		}
	}

	window.MyBB.Quotes.prototype.showQuickQuote = function showQuckQuote(pid) {
		var selection = window.getSelection(),
			range = selection.getRangeAt(0),
			rect = range.getBoundingClientRect();
		$elm = $("#post-" + pid).find('.quick-quote').show().data('content', $.trim(window.getSelection().toString()));
		$elm.css({
			'top': (window.scrollY + rect.top - $elm.outerHeight() - 4) + 'px',
			'left': (window.scrollX + rect.left - (($elm.outerWidth() - rect.width) / 2)) + 'px'
		});
	}

	window.MyBB.Quotes.prototype.hideQuickQuote = function () {
		$('.post .quick-quote').hide().data('content', '');
	}

	window.MyBB.Quotes.prototype.getQuotes = function getQuotes() {
		var quotes = MyBB.Cookie.get('quotes'),
			myQuotes = [];
		if (!quotes) {
			quotes = [];
		}
		else {
			quotes = JSON.parse(quotes);
		}
		$.each(quotes, function (key, quote) {
			if (quote != null) {
				myQuotes.push(quote);
			}
		});

		MyBB.Cookie.set('quotes', JSON.stringify(myQuotes));
		return myQuotes;
	};

	// MultiQuote
	window.MyBB.Quotes.prototype.multiQuoteButton = function multiQuoteButton(event) {
		event.preventDefault();
		var $me = $(event.target);
		if (!$me.hasClass('quote-button')) {
			$me = $me.parents('.quote-button');
		}
		var $post = $me.parents('.post');

		var postId = parseInt($post.data('postid')),
			type = $post.data('type'),
			quotes = this.getQuotes();

		if (postId) {
			var removed = false;
			$.each(quotes, function(key, quote) {
				if(typeof quote != 'string') {
					return;
				}
				if(quote == type + '_' + postId) {
					delete quotes[key];
					removed = true;
				}
			});
			if (!removed) {
				quotes.push(type + '_' + postId);
				$me.find('.quote-button__add').hide();
				$me.find('.quote-button__remove').show();
			}
			else {
				$me.find('.quote-button__add').show();
				$me.find('.quote-button__remove').hide();
			}

			MyBB.Cookie.set('quotes', JSON.stringify(quotes));

			this.showQuoteBar();
			return false;
		}

	};

	window.MyBB.Quotes.prototype.showQuoteBar = function showQuoteBar() {
		var quotes = this.getQuotes();

		if (quotes.length) {
			$(".quote-bar").show();
		}
		else {
			$(".quote-bar").hide();
		}
	};

	window.MyBB.Quotes.prototype.addQuotes = function addQuotes() {
		var quotes = this.getQuotes(),
			$quoteBar = $(".quote-bar"),
			$textarea = $($quoteBar.data('textarea'));

		MyBB.Spinner.add();

		$.ajax({
			url: '/post/quotes',
			data: {
				'posts': quotes,
				'_token': $quoteBar.parents('form').find('input[name=_token]').val()
			},
			method: 'POST'
		}).done(function (json) {
			if (json.error) {
				alert(json.error);// TODO: js error
			}
			else {
				var value = $textarea.val();
				if (value && value.substr(-2) != "\n\n") {
					if (value.substr(-1) != "\n") {
						value += "\n";
					}
					value += "\n";
				}
				$textarea.val(value + json.message).focus();
			}
			$.modal.close();
		}).always(function () {
			MyBB.Spinner.remove();
		});

		$quoteBar.hide();
		MyBB.Cookie.unset('quotes');
		this.quoteButtons();
		return false;
	};

	window.MyBB.Quotes.prototype.addQuote = function addQuote(postid, type, content) {
		var $textarea = $("#message");

		MyBB.Spinner.add();

		$.ajax({
			url: '/post/quotes',
			data: {
				'posts': [
					{
						'id': type + '_' + postid,
						'data': content
					}
				],
				'_token': $(".quote-bar").parents('form').find('input[name=_token]').val()
			},
			method: 'POST'
		}).done(function (json) {
			if (json.error) {
				alert(json.error);// TODO: js error
			}
			else {
				var value = $textarea.val();
				if (value && value.substr(-2) != "\n\n") {
					if (value.substr(-1) != "\n") {
						value += "\n";
					}
					value += "\n";
				}
				$textarea.val(value + json.message).focus();
			}
		}).always(function () {
			MyBB.Spinner.remove();
		});

		this.hideQuickQuote();

		return false;
	};

	window.MyBB.Quotes.prototype.viewQuotes = function viewQuotes() {
		MyBB.Spinner.add();

		$.ajax({
			url: '/post/quotes/all',
			data: {
				'posts': this.getQuotes(),
				'_token': $(".quote-bar").parents('form').find('input[name=_token]').val()
			},
			method: 'POST'
		}).done($.proxy(function (data) {
			var modalContent = $("#content", $(data)),
				modal = $('<div/>', {
					"class": "modal-dialog view-quotes",
					closeText: ''
				});
			modalContent.find('.view-quotes__quotes').css({
				'max-height': ($(window).height()-250)+'px'
			});
			modal.html(modalContent.html());
			modal.appendTo("body").modal({
				zIndex: 1000,
				closeText: ''
			});

			if(Modernizr.touch)
			{
				$('.radio-buttons .radio-button, .checkbox-buttons .checkbox-button').click(function() {

				});
			}
			else
			{
				$('span.icons i, a, .caption, time').powerTip({ placement: 's', smartPlacement: true });
			}

			$('.quote__select').on("click", $.proxy(this.quoteAdd, this));
			$('.quote__remove').on("click", $.proxy(this.quoteRemove, this));
			$(".select-all-quotes").on("click", $.proxy(this.addQuotes, this));
			$('.modal-hide').hide();
		}, this)).always(function () {
			MyBB.Spinner.remove();
		});

		this.hideQuickQuote();

		return false;
	};

	window.MyBB.Quotes.prototype.removeQuotes = function removeQuotes() {
		$quoteBar = $(".quote-bar");
		$quoteBar.hide();
		MyBB.Cookie.unset('quotes');
		this.quoteButtons();
		return false;
	};

	window.MyBB.Quotes.prototype.quoteButtons = function quoteButtons() {
		var quotes = this.getQuotes();

		$('.quote-button__add').show();
		$('.quote-button__remove').hide();

		$.each(quotes, function (key, quote) {
			if (typeof quote != 'string') {
				return;
			}
			quote = quote.split('_');
			type = quote[0];
			postId = parseInt(quote[1]);
			var $quoteButton = $("#post-" + postId + "[data-type='" + type + "']").find('.quoteButton');
			$quoteButton.find('.quote-button__add').hide();
			$quoteButton.find('.quote-button__remove').show();
		})
	}

	window.MyBB.Quotes.prototype.quoteAdd = function quoteAdd(event) {
		var $me = $(event.target),
			$post = $me.parents('.content-quote'),
			$textarea = $("#message"),
			quotes = this.getQuotes();

		var value = $textarea.val();
		if (value && value.substr(-2) != "\n\n") {
			if (value.substr(-1) != "\n") {
				value += "\n";
			}
			value += "\n";
		}
		$textarea.val(value + $post.data('quote')).focus();

		delete quotes[$post.data('id')];
		MyBB.Cookie.set('quotes', JSON.stringify(quotes));
		$post.slideUp('fast');

		if(this.getQuotes().length == 0) {
			$.modal.close();
		}

		while($post.next().length) {
			$post = $post.next();
			$post.data('id', $post.data('id')-1);
		}

		this.quoteButtons();
		this.showQuoteBar();
	}

	window.MyBB.Quotes.prototype.quoteRemove = function quoteRemove(event) {
		var $me = $(event.target),
			$post = $me.parents('.content-quote'),
			quotes = this.getQuotes();

		delete quotes[$post.data('id')];
		MyBB.Cookie.set('quotes', JSON.stringify(quotes));
		$post.slideUp('fast');

		if(this.getQuotes().length == 0) {
			$.modal.close();
		}

		while($post.next().length) {
			$post = $post.next();
			$post.data('id', $post.data('id')-1);
		}

		this.quoteButtons();
		this.showQuoteBar();
		return false;
	}

	var quotes = new window.MyBB.Quotes();


	// Helper functions
	// http://stackoverflow.com/questions/8339857
	function isOrContains(node, container) {
		while (node) {
			if (node === container) {
				return true;
			}
			node = node.parentNode;
		}
		return false;
	}

	function elementContainsSelection(el) {
		var sel;
		if (window.getSelection) {
			sel = window.getSelection();
			if (sel.rangeCount > 0) {
				for (var i = 0; i < sel.rangeCount; ++i) {
					if (!isOrContains(sel.getRangeAt(i).commonAncestorContainer, el)) {
						return false;
					}
				}
				return true;
			}
		} else if ((sel = document.selection) && sel.type != "Control") {
			return isOrContains(sel.createRange().parentElement(), el);
		}
		return false;
	}

})
(jQuery, window);
(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Avatar = function Avatar() {
		this.dropAvatar();
	};

	window.MyBB.Avatar.prototype.dropAvatar = function dropAvatar()
	{
		var $avatarDrop = $('#avatar-drop');
		if ($avatarDrop.length == 0 || $avatarDrop.is(':visible') == $('#avatar-drop-area').is(':visible')) {
			return;
		}

		var avatarDropUrl = $avatarDrop.attr('action');
		Dropzone.autoDiscover = false;
		var avatarDrop = new Dropzone("#avatar-drop", {
			url: avatarDropUrl + "?ajax=1",
			acceptedFiles: "image/*",
			clickable: '#file-input',
			paramName: "avatar_file",
			thumbnailWidth: null,
			thumbnailHeight: null,
			uploadMultiple: false,
			init: function () {
				$("#avatar-drop-area").show();
			},
			previewTemplate: '<div style="display:none" />'
		});

		avatarDrop.on("thumbnail", function (file, dataUrl) {
			$("#crop").find('.jcrop').find('img').attr('src', dataUrl);//TODO: use better selection
		});

		avatarDrop.on("sending", function (file) {
			MyBB.Spinner.add();
		});
		avatarDrop.on("complete", function () {
			MyBB.Spinner.remove();
		});
		avatarDrop.on("success", function (file) {
			var data = $.parseJSON(file.xhr.responseText);
			if (data.needCrop == true) {
				$("<a />").data('modal', '#crop').click((new MyBB.Modals()).toggleModal).click(); // TODO: create a method in modal.js for it
				var $jcrop = $('.modal').find('.jcrop');

				function JcropUpdateInputs(c) {
					$jcrop.find('.jcrop-x').val(c.x);
					$jcrop.find('.jcrop-y').val(c.y);
					$jcrop.find('.jcrop-x2').val(c.x2);
					$jcrop.find('.jcrop-y2').val(c.y2);
					$jcrop.find('.jcrop-w').val(c.w);
					$jcrop.find('.jcrop-h').val(c.h);
				}

				$jcrop.find('img').Jcrop({
					onChange: JcropUpdateInputs,
					onSelect: JcropUpdateInputs
				});

				$('.modal').find('.crop-img').click(function () {
					MyBB.Spinner.add();
					$.post('/account/avatar/crop?ajax=1', {
						x: $jcrop.find('.jcrop-x').val(),
						y: $jcrop.find('.jcrop-y').val(),
						x2: $jcrop.find('.jcrop-x2').val(),
						y2: $jcrop.find('.jcrop-y2').val(),
						w: $jcrop.find('.jcrop-w').val(),
						h: $jcrop.find('.jcrop-h').val(),
						"_token": $('input[name="_token"]').val()
					}).done(function (data) {
						if (data.success) {
							alert(data.message); // TODO: JS Message
							$.modal.close();
							$(".my-avatar").attr('src', data.avatar);
						} else {
							alert(data.error);// TODO: JS Error
						}
					}).always(function () {
						MyBB.Spinner.remove();
					});
				});
			}
		});
	}

	var avatar = new window.MyBB.Avatar();

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

	$("#search .search-button").click(function(event) {
		event.preventDefault();
		$("#search .search-container").slideDown();
	});

	autosize($('.post textarea'));

	$('a.show-menu__link').click(function(e) {
		if(menu == 0)
		{
			menu = 1;
			openMenu(e);
		}

		else
		{
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
function getTooltipContent(element) {
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

function escapeHTML(string) {
	if(typeof string == 'string') {
		return String(string).replace(/[&<>"'\/]/g, function (s) {
			return entityMap[s];
		});
	}

	return string;
}

function submitFormAsGet(id, newRoute) {
	var form = $('#' + id);
	form.find("input[name=_token]").val('');

	if(newRoute != null) {
		form.attr('action', newRoute);
	}

	form.attr('method', 'get').submit();
	return false;
}

function openMenu(e) {
	e.preventDefault();
	$("body").animate({'background-position-x': '0px'}, 200, function() { });
	$(".sidebar-menu").animate({marginLeft: "0px"}, 200, function() { });
	$(".page-body").animate({marginLeft: "225px", marginRight: "-225px"}, 200, function() { });
}

function closeMenu(e) {
	e.preventDefault();
	$("body").animate({'background-position-x': '-225px'}, 200, function() { });
	$(".sidebar-menu").animate({marginLeft: "-225px"}, 200, function() { });
	$(".page-body").animate({marginLeft: "0", marginRight: "0"}, 200, function() { });
}

(function($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Moderation = function Moderation()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // inline moderation click handling
        $('a[data-moderate]').click($.proxy(function (e) {
            e.preventDefault();

            MyBB.Spinner.add();

            var moderation_content = $('[data-moderation-content]').first();
            $.post('/moderate', {
                moderation_name: $(e.currentTarget).attr('data-moderate'),
                moderation_content: moderation_content.attr('data-moderation-content'),
                moderation_ids: window.MyBB.Moderation.getSelectedIds(),
                moderation_source_type: moderation_content.attr('data-moderation-source-type'),
                moderation_source_id: moderation_content.attr('data-moderation-source-id')
            }, function (response) {
                document.location.reload();
            });
        }, this));

        // inline reverse moderation click handling
        $('a[data-moderate-reverse]').click($.proxy(function (e) {
            e.preventDefault();

            MyBB.Spinner.add();

            var moderation_content = $('[data-moderation-content]').first();
            $.post('/moderate/reverse', {
                moderation_name: $(e.currentTarget).attr('data-moderate-reverse'),
                moderation_content: moderation_content.attr('data-moderation-content'),
                moderation_ids: window.MyBB.Moderation.getSelectedIds(),
                moderation_source_type: moderation_content.attr('data-moderation-source-type'),
                moderation_source_id: moderation_content.attr('data-moderation-source-id')
            }, function (response) {
                document.location.reload();
            });
        }, this));

        // moderation bar clear selection handling
        $('.clear-selection a').click(function(e) {
            $('[data-moderation-content] input[type=checkbox]:checked').removeAttr('checked');
            $('[data-moderation-content] .highlight').removeClass('highlight');
            $('.inline-moderation').removeClass('floating');
        });

        // post level inline moderation checkbox handling
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

        // topic level inline moderation checkbox handling
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

        $('li[data-moderation-multi]').hide();
    };

    // get the IDs of elements currently selected
    window.MyBB.Moderation.getSelectedIds = function getSelectedIds()
    {
        return $('input[type=checkbox][data-moderation-id]:checked').map(function () {
            return $(this).attr('data-moderation-id');
        }).get();
    };

    // grab the current selection and inject it into the modal so we can submit through a normal form
    window.MyBB.Moderation.injectModalParams = function injectFormData(element)
    {
        var moderation_content = $('[data-moderation-content]').first();
        $(element).attr('data-modal-params', JSON.stringify({
            moderation_content: moderation_content.attr('data-moderation-content'),
            moderation_ids: window.MyBB.Moderation.getSelectedIds(),
            moderation_source_type: moderation_content.attr('data-moderation-source-type'),
            moderation_source_id: moderation_content.attr('data-moderation-source-id')
        }));
    };

    var moderation = new window.MyBB.Moderation();

})(jQuery, window);

//# sourceMappingURL=main.js.map
