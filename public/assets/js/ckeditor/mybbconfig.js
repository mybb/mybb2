/**
 * @license Copyright (c) 2003-2015, MyBB Group
 */

CKEDITOR.editorConfig = function (config) {

	config.extraPlugins = 'mybbmycode,mybbinsertcode,mybbinsertphp';
	config.toolbar = [
		['Source', '-', 'NewPage'],
		['Cut', 'Copy', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
		['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],
		['Maximize'],
		'/',
		['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'],
		['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
		['Blockquote', '-', 'mybbinsertcode', 'mybbinsertphp'],
		['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Font', 'FontSize', 'TextColor'],
		['HorizontalRule', 'Image', '-', 'Link', 'Unlink', '-', 'SpecialChar']
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;div;pre';

	config.fontSize_sizes = 'X Small/x-small;Small/small;Meduim/medium;Large/large;X Large/x-large;XX Large/xx-large';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	config.image_previewText = ' ';
};
