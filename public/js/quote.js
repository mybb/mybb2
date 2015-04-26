(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Quotes = function Quotes() {

		// MultiQuote
		$(".quoteButton").on("click", this.multiQuoteButton.bind(this));

		this.showQuoteBar();

		$("#quoteBar__select").on("click", $.proxy(this.addQuotes, this));
		$("#quoteBar__deselect").on("click", $.proxy(this.removeQuotes, this));

		$('.quickQuote').on('click', $.proxy(this.quickQuote, this));
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
			$content = $('<div/>')
			$content.html($me.data('content'));
			this.addQuote($post.data('postid'), $content.text());
		}
	}

	window.MyBB.Quotes.prototype.checkQuickQuote = function checkQuickQuote(event) {
		var $me = $(event.target);
		if ($me.hasClass('quickQuote')) {
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
		$("#post-" + pid).find('.quickQuote').show().data('content', $.trim(window.getSelection().toString()));
	}

	window.MyBB.Quotes.prototype.hideQuickQuote = function () {
		$('.post .quickQuote').hide().data('content', '');
	}

	window.MyBB.Quotes.prototype.getQuotes = function getQuotes() {
		var quotes = MyBB.Cookie.get('quotes');
		if (!quotes) {
			quotes = [];
		}
		else {
			quotes = quotes.split('-');
		}
		$.each(quotes, function (key, quote) {
			quotes[key] = parseInt(quote);
			if (!quotes[key]) {
				delete quotes[key];
			}
		});
		return quotes;


	};

	// MultiQuote
	window.MyBB.Quotes.prototype.multiQuoteButton = function multiQuoteButton(event) {
		event.preventDefault();
		var $me = $(event.target);
		if (!$me.hasClass('quoteButton')) {
			$me = $me.parents('.quoteButton');
		}
		var postId = parseInt($me.data('postid')),
			quotes = this.getQuotes();

		if (postId) {
			if (quotes.indexOf(postId) == -1) {
				quotes.push(postId);
				$me.find('.quoteButton__add').hide();
				$me.find('.quoteButton__remove').show();
			}
			else {
				delete quotes[quotes.indexOf(postId)];
				$me.find('.quoteButton__add').show();
				$me.find('.quoteButton__remove').hide();
			}

			MyBB.Cookie.set('quotes', quotes.join('-'));

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
				$textarea.val(value + json.message);
			}
		}).always(function () {
			MyBB.Spinner.remove();
		});

		$quoteBar.hide();
		MyBB.Cookie.unset('quotes');
		this.quoteButtons();
		return false;
	};

	window.MyBB.Quotes.prototype.addQuote = function addQuote(postid, content) {
		var $textarea = $("#message");

		MyBB.Spinner.add();

		$.ajax({
			url: '/post/quote',
			data: {
				'postid': postid,
				'content': content,
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

		$.each(quotes, function (key, postId) {
			var $quoteButton = $("#post-" + postId).find('.quoteButton');
			$quoteButton.find('.quoteButton__add').hide();
			$quoteButton.find('.quoteButton__remove').show();
		})
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