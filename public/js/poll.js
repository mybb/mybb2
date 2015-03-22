(function($, window) {
    window.MyBB = window.MyBB || {};
    
	window.MyBB.Polls = function Polls()
	{
		this.optionElement = $('#option-simple').clone().removeClass('hidden').addClass('poll-option').hide();
		$('#option-simple').remove();

		this.removeOption($('#add-poll .poll-option'));

		$('#new-option').click($.proxy(this.addOption, this));

		$('#poll-maximum-options').hide();

		$('#poll-is-multiple').change($.proxy(this.toggleMaxOptionsInput, this));

		$("#add-poll-button").click($.proxy(this.toggleAddPoll, this));
	};

	window.MyBB.Polls.prototype.toggleAddPoll = function toggleAddPoll() {;
		if($('#add-poll-input').val() === '1') {
			$('#add-poll-input').val(0);
			$('#add-poll').slideUp();
		} else {
			$('#add-poll-input').val(1);
			$('#add-poll').slideDown();
		}
		return false;
	}
	window.MyBB.Polls.prototype.addOption = function addOption(event) {
		var num_options = $('#add-poll .poll-option').length;
		if(num_options >= 10) { // TODO: settings
			return false;
		}
		var $option = this.optionElement.clone();
		$('#add-poll .poll-option').last().after($option);
		$option.slideDown();
		this.removeOption($option);
		return false;
	};

	window.MyBB.Polls.prototype.removeOption = function bindRemoveOption($parent) {
		$parent.find('.remove-option').click(function() {
			if($('.poll-option').length <= 2)
			{
				return false;
			}

			$(this).parents('.poll-option').slideUp(500);

			setTimeout($.proxy(function() {
				$(this).parents('.poll-option').remove();
			}, this), 500);
		});
		if(!Modernizr.touch) {
			$parent.find('.remove-option').powerTip({ placement: 's', smartPlacement: true });
		}
	};

	window.MyBB.Polls.prototype.toggleMaxOptionsInput = function toggleMaxOptionsInput(event) {
		me = event.target;
		if($(me).is(':checked')) {
			$('#poll-maximum-options').slideDown();
		}
		else {
			$('#poll-maximum-options').slideUp();
		}
	}

	var polls = new window.MyBB.Polls();

})(jQuery, window);