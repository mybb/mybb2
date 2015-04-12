(function ($, window) {
    window.MyBB = window.MyBB || {};

    window.MyBB.Quotes = function Posts() {

        // MultiQuote
        $(".quoteButton").on("click", $.proxy(this.multiQuoteButton, this));

        this.showQuoteBar();

        $("#quoteBar__select").on("click", $.proxy(this.addQuotes, this));
        $("#quoteBar__deselect").on("click", $.proxy(this.removeQuotes, this));
    };

    window.MyBB.Quotes.prototype.getQuotes = function getQuotes() {
        var quotes = $.cookie('quotes');
        if (!quotes) {
            quotes = [];
        }
        else {
            quotes = quotes.split(',');
        }

        $.each(quotes, function (index, quote) {
            if (!quote) {
                delete quotes[index];
            }
        });

        return quotes;


    };

    // MultiQuote
    window.MyBB.Quotes.prototype.multiQuoteButton = function multiQuoteButton(event) {
        event.preventDefault();

        var $me = $(event.target),
            postId = $me.data('postid'),
            quotes = this.getQuotes();


        if (postId) {
            if (quotes.indexOf(postId) == -1) {
                quotes.push(postId);
            }
            else {
                delete quotes[quotes.indexOf(postId)];
            }

            $.cookie('quotes', quotes.join(','));

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

        // TODO: spinner

        $.ajax({
            url:'/post/quotes',
            data: {
                'posts': quotes,
                '_token': $quoteBar.parents('form').find('input[name=_token]').val()
            },
            method: 'POST'
        }).done(function(json) {
            if(json.error) {
                alert(json.error);// TODO: js error
            }
            else {
                $textarea.val($textarea.val() + json.message);
            }
        });

        $quoteBar.hide();
        $.cookie('quotes', '');
        return false;
    };

    window.MyBB.Quotes.prototype.removeQuotes = function removeQuotes() {
        $quoteBar = $("#quoteBar");
        $quoteBar.hide();
        $.cookie('quotes', '');
        return false;
    };

    var quotes = new window.MyBB.Quotes();

})
(jQuery, window);