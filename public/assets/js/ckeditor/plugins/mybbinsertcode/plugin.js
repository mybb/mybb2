/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.add('mybbinsertcode',
	{
		requires: 'dialog',
		lang: 'en', // %REMOVE_LINE_CORE%
		icons: 'mybbinsertcode', // %REMOVE_LINE_CORE%
		init: function (editor) {
			if (CKEDITOR.config.mybbinsertcode_class) {
				CKEDITOR.addCss(
					'div.' + CKEDITOR.config.mybbinsertcode_class + ' {' +
					CKEDITOR.config.mybbinsertcode_style +
					'}' +
					'div.' + CKEDITOR.config.mybbinsertcode_class + ':before {' +
					'display:block;border-bottom: 1px solid #ccc;font-weight: bold;padding-bottom: 3px;margin: 0 0 10px 0;content:\'' + editor.lang.mybbinsertcode.code + ':\'' +
					'}'
				);
			}
			// allowed and required content is the same for this plugin
			var required = CKEDITOR.config.mybbinsertcode_class ? ( 'div( ' + CKEDITOR.config.mybbinsertcode_class + ' )' ) : 'div';
			editor.addCommand('mybbinsertcode', new CKEDITOR.dialogCommand('mybbinsertcode', {
				allowedContent: required,
				requiredContent: required
			}));
			editor.ui.addButton && editor.ui.addButton('mybbinsertcode',
				{
					label: editor.lang.mybbinsertcode.title,
					icon: this.path + 'icons/mybbinsertcode.png',
					command: 'mybbinsertcode',
					toolbar: 'insert,99'
				});

			if (editor.contextMenu) {
				editor.addMenuGroup('code');
				editor.addMenuItem('mybbinsertcode',
					{
						label: editor.lang.mybbinsertcode.edit,
						icon: this.path + 'icons/mybbinsertcode.png',
						command: 'mybbinsertcode',
						group: 'code'
					});
				editor.contextMenu.addListener(function (element) {
					if (element)
						element = element.getAscendant('pre', true);
					if (element && !element.isReadOnly() && element.hasClass(editor.config.mybbinsertcode_class))
						return {mybbinsertcode: CKEDITOR.TRISTATE_OFF};
					return null;
				});
			}

			CKEDITOR.dialog.add('mybbinsertcode', function (editor) {
				return {
					title: editor.lang.mybbinsertcode.title,
					minWidth: 540,
					minHeight: 380,
					contents: [
						{
							id: 'general',
							label: editor.lang.mybbinsertcode.code,
							elements: [
								{
									type: 'textarea',
									id: 'contents',
									label: editor.lang.mybbinsertcode.code,
									cols: 140,
									rows: 22,
									validate: CKEDITOR.dialog.validate.notEmpty(editor.lang.mybbinsertcode.notEmpty),
									required: true,
									setup: function (element) {
										var html = element.getHtml();
										if (html) {
											var div = document.createElement('div');
											div.innerHTML = html.replace("\n", "<br>");
											this.setValue(div.firstChild.nodeValue);
										}
									},
									commit: function (element) {
										element.setHtml(CKEDITOR.tools.htmlEncode(this.getValue()).replace(/\n/g, "<br>"));
									}
								}
							]
						}
					],
					onShow: function () {
						var sel = editor.getSelection(),
							element = sel.getStartElement();
						if (element)
							element = element.getAscendant('div', true);

						if (!element || element.getName() != 'div' || !element.hasClass(editor.config.mybbinsertcode_class)) {
							element = editor.document.createElement('div');
							this.insertMode = true;
						}
						else
							this.insertMode = false;

						this.pre = element;
						this.setupContent(this.pre);
					},
					onOk: function () {
						if (editor.config.mybbinsertcode_class)
							this.pre.setAttribute('class', editor.config.mybbinsertcode_class);

						if (this.insertMode)
							editor.insertElement(this.pre);

						this.commitContent(this.pre);
					}
				};
			});
		}
	});

if (typeof(CKEDITOR.config.mybbinsertcode_style) == 'undefined')
	CKEDITOR.config.mybbinsertcode_style = 'background: #fff;border: 1px solid #ccc;padding: 10px;margin: 1px 0;';
if (typeof(CKEDITOR.config.mybbinsertcode_class) == 'undefined')
	CKEDITOR.config.mybbinsertcode_class = 'codeblock-code';