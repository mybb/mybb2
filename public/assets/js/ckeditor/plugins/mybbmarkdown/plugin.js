/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

(function () {
	CKEDITOR.on('dialogDefinition', function (ev) {
		var tab,
			name = ev.data.name,
			definition = ev.data.definition;

		if (name == 'link') {
			definition.removeContents('target');
			definition.removeContents('upload');
			definition.removeContents('advanced');
			tab = definition.getContents('info');
			tab.remove('emailSubject');
			tab.remove('emailBody');
		} else if (name == 'image') {
			definition.removeContents('advanced');
			tab = definition.getContents('Link');
			tab.remove('cmbTarget');
			tab = definition.getContents('info');
			tab.remove('txtAlt');
			tab.remove('basic');
		}
	});

	var markdownMap = {
			'**': 'strong',
			u: 'u',
			'_': 'em',
			s: 'strike',
			color: 'span',
			size: 'span',
			font: 'span',
			align: 'div',
			code: 'div',
			php: 'div',
			quote: 'blockquote',
			url: 'a',
			email: 'span',
			img: 'span',
			'*': 'li',
			list: 'ol',
			'-------------------': 'hr'
		},
		convertMap = {
			strong: '**',
			b: '**',
			u: 'u',
			em: '_',
			i: '_',
			s: 's',
			strike: 's',
			li: '*',
			hr: '-------------------'
		},
		tagnameMap = {
			strong: 'b',
			em: 'i',
			u: 'u',
			strike: 's',
			li: '*',
			ul: 'list',
			ol: 'list',
			a: 'link',
			img: 'img',
			blockquote: 'quote',
			hr: 'hr'
		},
		stylesMap = {
			color: 'color',
			size: 'font-size',
			font: 'font-family',
			align: 'text-align'
		},
		attributesMap = {
			url: 'href',
			email: 'mailhref',
			quote: 'cite',
			list: 'listType',
			code: 'codeblock',
			php: 'codeblock'
		};

	// List of block-like tags.
	var dtd = CKEDITOR.dtd,
		blockLikeTags = CKEDITOR.tools.extend({table: 1}, dtd.$block, dtd.$listItem, dtd.$tableContent, dtd.$list);

	var semicolonFixRegex = /\s*(?:;\s*|$)/;

	function serializeStyleText(stylesObject) {
		var styleText = '';
		for (var style in stylesObject) {
			var styleVal = stylesObject[style],
				text = ( style + ':' + styleVal ).replace(semicolonFixRegex, ';');

			styleText += text;
		}
		return styleText;
	}

	var smileyReverseMap = {},
		smileyRegExp = [];

	// Build regexp for the list of smiley text.
	for (var i in SmilieCodes) {
		smileyReverseMap[SmilieCodes[i]] = i;
		smileyRegExp.push(SmilieCodes[i].replace(/\(|\)|\:|\/|\*|\-|\|/g, function (match) {
			return '\\' + match;
		}));
	}

	smileyRegExp = new RegExp(smileyRegExp.join('|'), 'g');

	var decodeHtml = (function () {
		var regex = [],
			entities = {
				nbsp: '\u00A0', // IE | FF
				shy: '\u00AD', // IE
				gt: '\u003E', // IE | FF |   --   | Opera
				lt: '\u003C' // IE | FF | Safari | Opera
			};

		for (var entity in entities)
			regex.push(entity);

		regex = new RegExp('&(' + regex.join('|') + ');', 'g');

		return function (html) {
			return html.replace(regex, function (match, entity) {
				return entities[entity];
			});
		};
	})();

	CKEDITOR.MarkDownParser = function () {
		this._ = {
			bbcPartsRegex: /(?:[{\_\*\-}])/ig
		};
	};

	CKEDITOR.MarkDownParser.prototype = {
		parse: function (markdown) {
			var md = window.markdownit();
			this.onText(md.render(markdown), 1);
		}
	};

	/**
	 * Creates a {@link CKEDITOR.htmlParser.fragment} from an HTML string.
	 *
	 *        var fragment = CKEDITOR.htmlParser.fragment.fromHtml( '<b>Sample</b> Text' );
	 *        alert( fragment.children[ 0 ].name );        // 'b'
	 *        alert( fragment.children[ 1 ].value );    // ' Text'
	 *
	 * @static
	 * @member CKEDITOR.htmlParser.fragment
	 * @param {String} source The HTML to be parsed, filling the fragment.
	 * @returns {CKEDITOR.htmlParser.fragment} The fragment created.
	 */
	CKEDITOR.htmlParser.fragment.fromMarkDown = function (source) {
		var parser = new CKEDITOR.MarkDownParser(),
			fragment = new CKEDITOR.htmlParser.fragment(),
			pendingInline = [],
			pendingBrs = 0,
			currentNode = fragment,
			returnPoint;

		function checkPending(newTagName) {
			if (pendingInline.length > 0) {
				for (var i = 0; i < pendingInline.length; i++) {
					var pendingElement = pendingInline[i],
						pendingName = pendingElement.name,
						pendingDtd = CKEDITOR.dtd[pendingName],
						currentDtd = currentNode.name && CKEDITOR.dtd[currentNode.name];

					if (( !currentDtd || currentDtd[pendingName] ) && ( !newTagName || !pendingDtd || pendingDtd[newTagName] || !CKEDITOR.dtd[newTagName] )) {
						// Get a clone for the pending element.
						pendingElement = pendingElement.clone();

						// Add it to the current node and make it the current,
						// so the new element will be added inside of it.
						pendingElement.parent = currentNode;
						currentNode = pendingElement;

						// Remove the pending element (back the index by one
						// to properly process the next entry).
						pendingInline.splice(i, 1);
						i--;
					}
				}
			}
		}

		function checkPendingBrs(tagName, closing) {
			var len = currentNode.children.length,
				previous = len > 0 && currentNode.children[len - 1],
				lineBreakParent = !previous && writer.getRule(tagnameMap[currentNode.name], 'breakAfterOpen'),
				lineBreakPrevious = previous && previous.type == CKEDITOR.NODE_ELEMENT && writer.getRule(tagnameMap[previous.name], 'breakAfterClose'),
				lineBreakCurrent = tagName && writer.getRule(tagnameMap[tagName], closing ? 'breakBeforeClose' : 'breakBeforeOpen');

			if (pendingBrs && ( lineBreakParent || lineBreakPrevious || lineBreakCurrent ))
				pendingBrs--;

			// 1. Either we're at the end of block, where it requires us to compensate the br filler
			// removing logic (from htmldataprocessor).
			// 2. Or we're at the end of pseudo block, where it requires us to compensate
			// the bogus br effect.
			if (pendingBrs && tagName in blockLikeTags)
				pendingBrs++;

			while (pendingBrs && pendingBrs--)
				currentNode.children.push(previous = new CKEDITOR.htmlParser.element('br'));
		}

		function addElement(node, target) {
			checkPendingBrs(node.name, 1);

			target = target || currentNode || fragment;

			var len = target.children.length,
				previous = len > 0 && target.children[len - 1] || null;

			node.previous = previous;
			node.parent = target;

			target.children.push(node);

			if (node.returnPoint) {
				currentNode = node.returnPoint;
				delete node.returnPoint;
			}
		}

		parser.onTagOpen = function (tagName, attributes) {
			var element = new CKEDITOR.htmlParser.element(tagName, attributes);

			// This is a tag to be removed if empty, so do not add it immediately.
			if (CKEDITOR.dtd.$removeEmpty[tagName]) {
				pendingInline.push(element);
				return;
			}

			var currentName = currentNode.name;

			var currentDtd = currentName && ( CKEDITOR.dtd[currentName] || ( currentNode._.isBlockLike ? CKEDITOR.dtd.div : CKEDITOR.dtd.span ) );

			// If the element cannot be child of the current element.
			if (currentDtd && !currentDtd[tagName]) {
				var reApply = false,
					addPoint; // New position to start adding nodes.

				// If the element name is the same as the current element name,
				// then just close the current one and append the new one to the
				// parent. This situation usually happens with <p>, <li>, <dt> and
				// <dd>, specially in IE. Do not enter in this if block in this case.
				if (tagName == currentName)
					addElement(currentNode, currentNode.parent);
				else if (tagName in CKEDITOR.dtd.$listItem) {
					parser.onTagOpen('ul', {});
					addPoint = currentNode;
					reApply = true;
				} else {
					addElement(currentNode, currentNode.parent);

					// The current element is an inline element, which
					// cannot hold the new one. Put it in the pending list,
					// and try adding the new one after it.
					pendingInline.unshift(currentNode);
					reApply = true;
				}

				if (addPoint)
					currentNode = addPoint;
				// Try adding it to the return point, or the parent element.
				else
					currentNode = currentNode.returnPoint || currentNode.parent;

				if (reApply) {
					parser.onTagOpen.apply(this, arguments);
					return;
				}
			}

			checkPending(tagName);
			checkPendingBrs(tagName);

			element.parent = currentNode;
			element.returnPoint = returnPoint;
			returnPoint = 0;

			if (element.isEmpty)
				addElement(element);
			else
				currentNode = element;
		};

		parser.onTagClose = function (tagName) {
			// Check if there is any pending tag to be closed.
			for (var i = pendingInline.length - 1; i >= 0; i--) {
				// If found, just remove it from the list.
				if (tagName == pendingInline[i].name) {
					pendingInline.splice(i, 1);
					return;
				}
			}

			var pendingAdd = [],
				newPendingInline = [],
				candidate = currentNode;

			while (candidate.type && candidate.name != tagName) {
				// If this is an inline element, add it to the pending list, if we're
				// really closing one of the parents element later, they will continue
				// after it.
				if (!candidate._.isBlockLike)
					newPendingInline.unshift(candidate);

				// This node should be added to it's parent at this point. But,
				// it should happen only if the closing tag is really closing
				// one of the nodes. So, for now, we just cache it.
				pendingAdd.push(candidate);

				candidate = candidate.parent;
			}

			if (candidate.type) {
				// Add all elements that have been found in the above loop.
				for (i = 0; i < pendingAdd.length; i++) {
					var node = pendingAdd[i];
					addElement(node, node.parent);
				}

				currentNode = candidate;


				addElement(candidate, candidate.parent);

				// The parent should start receiving new nodes now, except if
				// addElement changed the currentNode.
				if (candidate == currentNode)
					currentNode = currentNode.parent;

				pendingInline = pendingInline.concat(newPendingInline);
			}
		};

		parser.onText = function (text) {
			var currentDtd = CKEDITOR.dtd[currentNode.name];
			if (!currentDtd || currentDtd['#']) {
				checkPendingBrs();
				checkPending();

				text.replace(/(\r\n|[\r\n])|[^\r\n]*/g, function (piece, lineBreak) {
					var lastIndex = 0;
					if (lineBreak !== undefined && lineBreak.length)
						pendingBrs++;
					else if (piece.length) {

						// Create smiley from text emotion.
						piece.replace(smileyRegExp, function (match, index) {
							addElement(new CKEDITOR.htmlParser.text(piece.substring(lastIndex, index)), currentNode);
							addElement(new CKEDITOR.htmlParser.element('smiley', {desc: match}), currentNode);
							lastIndex = index + match.length;
						});

						if (lastIndex != piece.length) {
							addElement(new CKEDITOR.htmlParser.text(piece.substring(lastIndex, piece.length)), currentNode);
						}
					}
				});
			}
		};

		// Parse it.
		parser.parse(CKEDITOR.tools.htmlEncode(source));

		// Close all hanging nodes.
		while (currentNode.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT) {
			var parent = currentNode.parent,
				node = currentNode;

			addElement(node, parent);
			currentNode = parent;
		}

		return fragment;
	};

	var MarkDownWriter = CKEDITOR.tools.createClass({
		base: CKEDITOR.htmlParser.basicWriter,

		/**
		 * Creates an `htmlWriter` class instance.
		 *
		 * @constructor
		 */
		$: function () {
			// Call the base contructor.
			this.base();

			/**
			 * The characters to be used for each indentation step.
			 *
			 *        // Use tab for indentation.
			 *        editorInstance.dataProcessor.writer.indentationChars = '\t';
			 */
			this.indentationChars = '\t';

			/**
			 * The characters to be used to close "self-closing" elements, like `<br>` or `<img>`.
			 *
			 *        // Use HTML4 notation for self-closing elements.
			 *        editorInstance.dataProcessor.writer.selfClosingEnd = '>';
			 */
			this.selfClosingEnd = ' />';

			/**
			 * The characters to be used for line breaks.
			 *
			 *        // Use CRLF for line breaks.
			 *        editorInstance.dataProcessor.writer.lineBreakChars = '\r\n';
			 */
			this.lineBreakChars = '\n';

			this.sortAttributes = 1;

			this._.indent = 0;
			this._.indentation = '';
			// Indicate preformatted block context status. (#5789)
			this._.inPre = 0;
			this._.rules = {};

			var dtd = CKEDITOR.dtd;

/*			for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent)) {
				this.setRules(e, {
					indent: !dtd[e]['#'],
					breakBeforeOpen: 1,
					breakBeforeClose: !dtd[e]['#'],
					breakAfterClose: 1,
					needsSpace: ( e in dtd.$block ) && !( e in {li: 1, dt: 1, dd: 1} )
				});
			}

			this.setRules('br', {breakAfterOpen: 1});

			this.setRules('title', {
				indent: 0,
				breakAfterOpen: 0
			});

			this.setRules('style', {
				indent: 0,
				breakBeforeClose: 1
			});

			this.setRules('pre', {
				breakAfterOpen: 1, // Keep line break after the opening tag
				indent: 0 // Disable indentation on <pre>.
			});*/
		},

		proto: {
			/**
			 * Writes the tag opening part for an opener tag.
			 *
			 *        // Writes '<p'.
			 *        writer.openTag( 'p', { class : 'MyClass', id : 'MyId' } );
			 *
			 * @param {String} tagName The element name for this tag.
			 * @param {Object} attributes The attributes defined for this tag. The
			 * attributes could be used to inspect the tag.
			 */
			openTag: function (tagName) {
				var rules = this._.rules[tagName];

				if (this._.afterCloser && rules && rules.needsSpace && this._.needsSpace)
					this._.output.push('\n');

				if (this._.indent)
					this.indentation();
				// Do not break if indenting.
				else if (rules && rules.breakBeforeOpen) {
					this.lineBreak();
					this.indentation();
				}

				this._.output.push('<', tagName);

				this._.afterCloser = 0;
			},

			/**
			 * Writes the tag closing part for an opener tag.
			 *
			 *        // Writes '>'.
			 *        writer.openTagClose( 'p', false );
			 *
			 *        // Writes ' />'.
			 *        writer.openTagClose( 'br', true );
			 *
			 * @param {String} tagName The element name for this tag.
			 * @param {Boolean} isSelfClose Indicates that this is a self-closing tag,
			 * like `<br>` or `<img>`.
			 */
			openTagClose: function (tagName, isSelfClose) {
				var rules = this._.rules[tagName];

				if (isSelfClose) {
					this._.output.push(this.selfClosingEnd);

					if (rules && rules.breakAfterClose)
						this._.needsSpace = rules.needsSpace;
				} else {
					this._.output.push('>');

					if (rules && rules.indent)
						this._.indentation += this.indentationChars;
				}

				if (rules && rules.breakAfterOpen)
					this.lineBreak();
				tagName == 'pre' && ( this._.inPre = 1 );
			},

			/**
			 * Writes an attribute. This function should be called after opening the
			 * tag with {@link #openTagClose}.
			 *
			 *        // Writes ' class="MyClass"'.
			 *        writer.attribute( 'class', 'MyClass' );
			 *
			 * @param {String} attName The attribute name.
			 * @param {String} attValue The attribute value.
			 */
			attribute: function (attName, attValue) {

				if (typeof attValue == 'string') {
					this.forceSimpleAmpersand && ( attValue = attValue.replace(/&amp;/g, '&') );
					// Browsers don't always escape special character in attribute values. (#4683, #4719).
					attValue = CKEDITOR.tools.htmlEncodeAttr(attValue);
				}

				this._.output.push(' ', attName, '="', attValue, '"');
			},

			/**
			 * Writes a closer tag.
			 *
			 *        // Writes '</p>'.
			 *        writer.closeTag( 'p' );
			 *
			 * @param {String} tagName The element name for this tag.
			 */
			closeTag: function (tagName) {
				var rules = this._.rules[tagName];

				if (rules && rules.indent)
					this._.indentation = this._.indentation.substr(this.indentationChars.length);

				if (this._.indent)
					this.indentation();
				// Do not break if indenting.
				else if (rules && rules.breakBeforeClose) {
					this.lineBreak();
					this.indentation();
				}

				this._.output.push('</', tagName, '>');
				tagName == 'pre' && ( this._.inPre = 0 );

				if (rules && rules.breakAfterClose) {
					this.lineBreak();
					this._.needsSpace = rules.needsSpace;
				}

				this._.afterCloser = 1;
			},

			/**
			 * Writes text.
			 *
			 *        // Writes 'Hello Word'.
			 *        writer.text( 'Hello Word' );
			 *
			 * @param {String} text The text value
			 */
			text: function (text) {
				if (this._.indent) {
					this.indentation();
					!this._.inPre && ( text = CKEDITOR.tools.ltrim(text) );
				}

				this._.output.push(text);
			},

			/**
			 * Writes a comment.
			 *
			 *        // Writes "<!-- My comment -->".
			 *        writer.comment( ' My comment ' );
			 *
			 * @param {String} comment The comment text.
			 */
			comment: function (comment) {
				if (this._.indent)
					this.indentation();

				this._.output.push('<!--', comment, '-->');
			},

			/**
			 * Writes a line break. It uses the {@link #lineBreakChars} property for it.
			 *
			 *        // Writes '\n' (e.g.).
			 *        writer.lineBreak();
			 */
			lineBreak: function () {
/*				if (!this._.inPre && this._.output.length > 0)
					this._.output.push(this.lineBreakChars);*/
				this._.indent = 1;
			},

			/**
			 * Writes the current indentation character. It uses the {@link #indentationChars}
			 * property, repeating it for the current indentation steps.
			 *
			 *        // Writes '\t' (e.g.).
			 *        writer.indentation();
			 */
			indentation: function () {
				if (!this._.inPre && this._.indentation)
					this._.output.push(this._.indentation);
				this._.indent = 0;
			},

			/**
			 * Empties the current output buffer. It also brings back the default
			 * values of the writer flags.
			 *
			 *        writer.reset();
			 */
			reset: function () {
				this._.output = [];
				this._.indent = 0;
				this._.indentation = '';
				this._.afterCloser = 0;
				this._.inPre = 0;
			},

			getHtml: function (reset) {
				var markdown = this._.output.join('');

				if (reset)
					this.reset();

				var und = new upndown();
				return decodeHtml(und.convert(markdown));
			},

			/**
			 * Sets formatting rules for a given element. Possible rules are:
			 *
			 * * `indent` &ndash; indent the element content.
			 * * `breakBeforeOpen` &ndash; break line before the opener tag for this element.
			 * * `breakAfterOpen` &ndash; break line after the opener tag for this element.
			 * * `breakBeforeClose` &ndash; break line before the closer tag for this element.
			 * * `breakAfterClose` &ndash; break line after the closer tag for this element.
			 *
			 * All rules default to `false`. Each function call overrides rules that are
			 * already present, leaving the undefined ones untouched.
			 *
			 * By default, all elements available in the {@link CKEDITOR.dtd#$block},
			 * {@link CKEDITOR.dtd#$listItem}, and {@link CKEDITOR.dtd#$tableContent}
			 * lists have all the above rules set to `true`. Additionaly, the `<br>`
			 * element has the `breakAfterOpen` rule set to `true`.
			 *
			 *        // Break line before and after "img" tags.
			 *        writer.setRules( 'img', {
		 *			breakBeforeOpen: true
		 *			breakAfterOpen: true
		 *		} );
			 *
			 *        // Reset the rules for the "h1" tag.
			 *        writer.setRules( 'h1', {} );
			 *
			 * @param {String} tagName The name of the element for which the rules are set.
			 * @param {Object} rules An object containing the element rules.
			 */
			setRules: function (tagName, rules) {
				var currentRules = this._.rules[tagName];

				if (currentRules)
					CKEDITOR.tools.extend(currentRules, rules, true);
				else
					this._.rules[tagName] = rules;
			},

			getRule: function (tagName, ruleName) {
				return this._.rules[tagName] && this._.rules[tagName][ruleName];
			}
		}
	});

	var writer = new MarkDownWriter();

	CKEDITOR.plugins.add('mybbmarkdown', {
		requires: 'entities',

		// Adapt some critical editor configuration for better support
		// of MarkDown environment.
		beforeInit: function (editor) {
			var config = editor.config;

			CKEDITOR.tools.extend(config, {
				// This one is for backwards compatibility only as
				// editor#enterMode is already set at this stage (#11202).
				enterMode: CKEDITOR.ENTER_BR,
				basicEntities: false,
				entities: false,
				fillEmptyBlocks: false
			}, true);

			editor.filter.disable();

			// Since CKEditor 4.3, editor#(active)enterMode is set before
			// beforeInit. Properties got to be updated (#11202).
			editor.activeEnterMode = editor.enterMode = CKEDITOR.ENTER_BR;
		},

		init: function (editor) {
			var config = editor.config;

			function MarkDownToHtml(code) {
				var fragment = CKEDITOR.htmlParser.fragment.fromMarkDown(code),
					writer = new CKEDITOR.htmlParser.basicWriter();

				fragment.writeHtml(writer, markdownFilter);
				return writer.getHtml(true);
			}

			var markdownFilter = new CKEDITOR.htmlParser.filter();
			markdownFilter.addRules({
				elements: {
					blockquote: function (element) {
						var quoted = new CKEDITOR.htmlParser.element('div');
						quoted.children = element.children;
						element.children = [quoted];
						var citeText = element.attributes.cite;
						if (citeText) {
							var cite = new CKEDITOR.htmlParser.element('cite');
							cite.add(new CKEDITOR.htmlParser.text(citeText.replace(/^"|"$/g, '')));
							delete element.attributes.cite;
							element.children.unshift(cite);
						}
					},
					span: function (element) {
						var markdown;
						if (( markdown = element.attributes.markdown )) {
							if (markdown == 'img') {
								element.name = 'img';
								element.attributes.src = element.children[0].value;
								element.children = [];
							} else if (markdown == 'email') {
								element.name = 'a';
								element.attributes.href = 'mailto:' + element.attributes.mailhref;
							}
							delete element.attributes.markdown;
						}
					},
					ol: function (element) {
						if (element.attributes.listType) {
							if (element.attributes.listType != 'decimal')
								element.attributes.style = 'list-style-type:' + element.attributes.listType;
						} else {
							element.name = 'ul';
						}

						delete element.attributes.listType;
					},
					a: function (element) {
						if (!element.attributes.href)
							element.attributes.href = element.children[0].value;
					},
					smiley: function (element) {
						element.name = 'img';

						var description = element.attributes.desc,
							src = Smilies[description];

						element.attributes = {
							src: src,
							'data-cke-saved-src': src,
							title: description,
							alt: description,
							class: 'smilie',
							'data-smilie': description
						};
					}
				}
			});

			editor.dataProcessor.writer = writer;

			function onSetData(evt) {
				var markdown = evt.data.dataValue;
				evt.data.dataValue = MarkDownToHtml(markdown);
			}

			// Skip the first "setData" call from inline creator, to allow content of
			// HTML to be loaded from the page element.
			if (editor.elementMode == CKEDITOR.ELEMENT_MODE_INLINE)
				editor.once('contentDom', function () {
					editor.on('setData', onSetData);
				});
			else
				editor.on('setData', onSetData);

		},

		afterInit: function (editor) {
			var filters;
			if (editor._.elementsPath) {
				// Eliminate irrelevant elements from displaying, e.g body and p.
				if (( filters = editor._.elementsPath.filters )) {
					filters.push(function (element) {
						var htmlName = element.getName(),
							name = tagnameMap[htmlName] || false;

						// Specialized anchor presents as email.
						if (name == 'link' && element.getAttribute('href').indexOf('mailto:') === 0)
							name = 'email';
						// Styled span could be either size or color.
						else if (htmlName == 'span') {
							if (element.getStyle('font-size'))
								name = 'size';
							else if (element.getStyle('color'))
								name = 'color';
							else if (element.getStyle('font-family'))
								name = 'font';
						}
						else if (htmlName == 'div' || htmlName == 'p') {
							if (element.getStyle('text-align'))
								name = 'align';
							else if (element.getStyle('direction'))
								name = 'align';
							else if (element.hasClass('codeblock-code')) {
								name = 'code';
							}
							else if (element.hasClass('codeblock-php')) {
								name = 'php';
							}
						}
						else if (name == 'img') {
							var src = element.data('cke-saved-src') || element.getAttribute('src');
							if (src && src.indexOf(editor.config.smiley_path) === 0)
								name = 'smiley';
						}

						return name;
					});
				}
			}
		}
	});

})();
