(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Quotes = function Quotes() {

		// MultiQuote
		$(".quoteButton").on("click", this.multiQuoteButton.bind(this));

		this.showQuoteBar();

		$("#quoteBar__select").on("click", $.proxy(this.addQuotes, this));
		$("#quoteBar__view").on("click", $.proxy(this.viewQuotes, this));
		$("#quoteBar__deselect").on("click", $.proxy(this.removeQuotes, this));

		$('.quickQuote .fast').on('click', $.proxy(this.quickQuote, this));
		$('.quickQuote .add').on('click', $.proxy(this.quickAddQuote, this));

		$('.quote__select').on("click", $.proxy(this.quoteAdd, this));
		$('.quote__remove').on("click", $.proxy(this.quoteRemove, this));
		$("body").on("mouseup", $.proxy(this.checkQuickQuote, this));

		this.quoteButtons();
	};

	window.MyBB.Quotes.prototype.quickQuote = function quickQuote(event) {
		var $me = $(event.target);
		if (!$me.hasClass('quickQuote')) {
			$me = $me.parents('.quickQuote');
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
		if (!$me.hasClass('quickQuote')) {
			$me = $me.parents('.quickQuote');
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
		if ($me.hasClass('quickQuote') || $me.parents('.quickQuote').length) {
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
		$elm = $("#post-" + pid).find('.quickQuote').show().data('content', $.trim(window.getSelection().toString()));
		$elm.css({
			'top': (window.scrollY + rect.top - $elm.outerHeight() - 4) + 'px',
			'left': (window.scrollX + rect.left - (($elm.outerWidth() - rect.width) / 2)) + 'px'
		});
	}

	window.MyBB.Quotes.prototype.hideQuickQuote = function () {
		$('.post .quickQuote').hide().data('content', '');
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
		if (!$me.hasClass('quoteButton')) {
			$me = $me.parents('.quoteButton');
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
				$me.find('.quoteButton__add').hide();
				$me.find('.quoteButton__remove').show();
			}
			else {
				$me.find('.quoteButton__add').show();
				$me.find('.quoteButton__remove').hide();
			}

			MyBB.Cookie.set('quotes', JSON.stringify(quotes));

			this.showQuoteBar();
			return false;
		}

	};

	window.MyBB.Quotes.prototype.showQuoteBar = function showQuoteBar() {
		var quotes = this.getQuotes();

		if (quotes.length) {
			$("#quoteBar").show();
		}
		else {
			$("#quoteBar").hide();
		}
	};

	window.MyBB.Quotes.prototype.addQuotes = function addQuotes() {
		var quotes = this.getQuotes(),
			$quoteBar = $("#quoteBar"),
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
				'_token': $("#quoteBar").parents('form').find('input[name=_token]').val()
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
				'_token': $("#quoteBar").parents('form').find('input[name=_token]').val()
			},
			method: 'POST'
		}).done($.proxy(function (data) {
			var modalContent = $("#content", $(data)),
				modal = $('<div/>', {
					"class": "modal-dialog viewQuotes"
				});
			modalContent.find('.post-quotes').css({
				'max-height': ($(window).height()-100)+'px'
			});
			modal.html(modalContent.html());
			modal.appendTo("body").modal({
				zIndex: 1000
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
			$(".selectAllQuotes").on("click", $.proxy(this.addQuotes, this));
			$('.modalHide').hide();
		}, this)).always(function () {
			MyBB.Spinner.remove();
		});

		this.hideQuickQuote();

		return false;
	};

	window.MyBB.Quotes.prototype.removeQuotes = function removeQuotes() {
		$quoteBar = $("#quoteBar");
		$quoteBar.hide();
		MyBB.Cookie.unset('quotes');
		this.quoteButtons();
		return false;
	};

	window.MyBB.Quotes.prototype.quoteButtons = function quoteButtons() {
		var quotes = this.getQuotes();

		$('.quoteButton__add').show();
		$('.quoteButton__remove').hide();

		$.each(quotes, function (key, quote) {
			if (typeof quote != 'string') {
				return;
			}
			quote = quote.split('_');
			type = quote[0];
			postId = parseInt(quote[1]);
			var $quoteButton = $("#post-" + postId + "[data-type='" + type + "']").find('.quoteButton');
			$quoteButton.find('.quoteButton__add').hide();
			$quoteButton.find('.quoteButton__remove').show();
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