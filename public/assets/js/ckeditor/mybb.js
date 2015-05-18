//TODO: smiliesvar
SmilieCodes = [
	':)',
	':-\\',
	'(:',
	':|'
];
var Smilies = {
	':)': '1.png',
	':-\\': '2.png',
	'(:': '3.png',
	':|': '4.png'
};

var Editor = CKEDITOR.replace('message', {
	customConfig: 'mybbconfig.js'
});

