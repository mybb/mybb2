var modal = function mymodal(page, find) {
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

module.exports = modal;
console.log(modal);