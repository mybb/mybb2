(function ($, window) {
	window.MyBB = window.MyBB || {};

	window.MyBB.Quotes = function Quotes() {

		// MultiQuote
		$(".quoteButton").on("click", this.multiQuoteButton.bind(this));

		this.showQuoteBar();

		$("#quoteBar__select").on("click", $.proxy(this.addQuotes, this));
		$("#quoteBar__deselect").on("click", $.proxy(this.removeQuotes, this));

		this.quoteButtons();
	};

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
				$textarea.val($textarea.val() + json.message);
			}
		}).always(function () {
			MyBB.Spinner.remove();
		});

		$quoteBar.hide();
		MyBB.Cookie.unSet('quotes');
		this.quoteButtons();
		return false;
	};

	window.MyBB.Quotes.prototype.removeQuotes = function removeQuotes() {
		$quoteBar = $("#quoteBar");
		$quoteBar.hide();
		MyBB.Cookie.unSet('quotes');
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

})
(jQuery, window);