(function ($) {

	function appendContent($el, content) {
		if (!content) return;

		// Simple test for a jQuery element
		$el.append(content.jquery ? content.clone() : content);
	}

	function appendBody($body, $element, opt) {
		// Clone for safety and convenience
		// Calls clone(withDataAndEvents = true) to copy form values.
		let $content = $element.clone(opt.formValues);

		if (opt.formValues) {
			// Copy original select and textarea values to their cloned counterpart
			// Makes up for inability to clone select and textarea values with clone(true)
			copyValues($element, $content, "select, textarea");
		}

		if (opt.removeScripts) {
			$content.find("script").remove();
		}

		if (opt.printContainer) {
			// grab $.selector as container
			$content.appendTo($body);
		} else {
			// otherwise just print interior elements of container
			$content.each(function () {
				$(this).children().appendTo($body)
			});
		}
	}

	// Copies values from origin to clone for passed in elementSelector
	function copyValues(origin, clone, elementSelector) {
		let $originalElements = origin.find(elementSelector);

		clone.find(elementSelector).each(function (index, item) {
			$(item).val($originalElements.eq(index).val());
		});
	}

	let opt;
	$.fn.printThis = function (options) {
		opt = $.extend({}, $.fn.printThis.defaults, options);
		let $element = this instanceof jQuery ? this : $(this);

		let strFrameName = "printThis-" + (new Date()).getTime();
		const isMsie = /msie/i.exec(navigator.userAgent) !== null;

		if (window.location.hostname !== document.domain && isMsie) {
			// Ugly IE hacks due to IE not inheriting document.domain from parent
			// checks if document.domain is set by comparing the host name against document.domain
			let iframeSrc = "javascript:document.write(\"<head><script>document.domain=\\\"" + document.domain + "\\\";</script></head><body></body>\")";
			let printI = document.createElement("iframe");
			printI.name = "printIframe";
			printI.id = strFrameName;
			printI.className = "MSIE";
			document.body.appendChild(printI);
			printI.src = iframeSrc;

		} else {
			// other browsers inherit document.domain, and IE works if document.domain is not explicitly set
			let $frame = $("<iframe id='" + strFrameName + "' name='printIframe' />");
			$frame.appendTo("body");
		}

		let $iframe = $("#" + strFrameName);

		// show frame if in debug mode
		if (!opt.debug) $iframe.css({
			position: "absolute",
			width: "0px",
			height: "0px",
			left: "-600px",
			top: "-600px"
		});

		// before print callback
		if (typeof opt.beforePrint === "function") {
			opt.beforePrint();
		}

		// $iframe.ready() and $iframe.load were inconsistent between browsers
		setTimeout(function () {

			// Add doctype to fix the style difference between printing and render
			function setDocType($iframe, doctype) {
				let win, doc;
				win = $iframe.get(0);
				win = win.contentWindow || win.contentDocument || win;
				doc = win.document || win.contentDocument || win;
				doc.open();
				doc.write(doctype);
				doc.close();
			}

			if (opt.doctypeString) {
				setDocType($iframe, opt.doctypeString);
			}

			let $doc = $iframe.contents(),
				$head = $doc.find("head"),
				$body = $doc.find("body"),
				$base = $("base"),
				baseURL;

			// add base tag to ensure elements use the parent domain
			if (opt.base === true && $base.length > 0) {
				// take the base tag from the original page
				baseURL = $base.attr("href");
			} else if (typeof opt.base === "string") {
				// An exact base string is provided
				baseURL = opt.base;
			} else {
				// Use the page URL as the base
				baseURL = document.location.protocol + "//" + document.location.host;
			}

			$head.append("<base href='" + baseURL + "'>");

			// import page stylesheets
			if (opt.importCSS) $("link[rel=stylesheet]").each(function () {
				let href = $(this).attr("href");
				if (href) {
					let media = $(this).attr("media") || "all";
					$head.append("<link type='text/css' rel='stylesheet' href='" + href + "' media='" + media + "'>");
				}
			});

			// import style tags
			if (opt.importStyle) $("style").each(function () {
				$head.append(this.outerHTML);
			});

			// add title of the page
			if (opt.pageTitle) $head.append("<title>" + opt.pageTitle + "</title>");

			// import additional stylesheet(s)
			if (opt.loadCSS) {
				if ($.isArray(opt.loadCSS)) {
					jQuery.each(opt.loadCSS, function (index, value) {
						$head.append("<link type='text/css' rel='stylesheet' href='" + this + "'>");
					});
				} else {
					$head.append("<link type='text/css' rel='stylesheet' href='" + opt.loadCSS + "'>");
				}
			}

			let pageHtml = $("html")[0];

			// CSS VAR in html tag when dynamic apply e.g. document.documentElement.style.setProperty("--foo", bar);
			$doc.find("html").prop("style", pageHtml.style.cssText);

			// copy "root" tag classes
			let tag = opt.copyTagClasses;
			if (tag) {
				tag = tag === true ? "bh" : tag;
				if (tag.indexOf("b") !== -1) {
					$body.addClass($("body")[0].className);
				}
				if (tag.indexOf("h") !== -1) {
					$doc.find("html").addClass(pageHtml.className);
				}
			}

			// copy ":root" tag classes
			tag = opt.copyTagStyles;
			if (tag) {
				tag = tag === true ? "bh" : tag;
				if (tag.indexOf("b") !== -1) {
					$body.attr("style", $("body")[0].style.cssText);
				}
				if (tag.indexOf("h") !== -1) {
					$doc.find("html").attr("style", pageHtml.style.cssText);
				}
			}

			// print header
			appendContent($body, opt.header);

			if (opt.canvas) {
				// add canvas data-ids for easy access after cloning.
				let canvasId = 0;
				// .addBack("canvas") adds the top-level element if it is a canvas.
				$element.find("canvas").addBack("canvas").each(function () {
					$(this).attr("data-printthis", canvasId++);
				});
			}

			appendBody($body, $element, opt);

			if (opt.canvas) {
				// Re-draw new canvases by referencing the originals
				$body.find("canvas").each(function () {
					let cid = $(this).data("printthis"),
						$src = $("[data-printthis='" + cid + "']");

					this.getContext("2d").drawImage($src[0], 0, 0);

					// Remove the markup from the original
					if ($.isFunction($.fn.removeAttr)) {
						$src.removeAttr("data-printthis");
					} else {
						$.each($src, function (i, el) {
							el.removeAttribute("data-printthis");
						});
					}
				});
			}

			// remove inline styles
			if (opt.removeInline) {
				// Ensure there is a selector, even if it"s been mistakenly removed
				let selector = opt.removeInlineSelector || "*";
				// $.removeAttr available jQuery 1.7+
				if ($.isFunction($.removeAttr)) {
					$body.find(selector).removeAttr("style");
				} else {
					$body.find(selector).attr("style", "");
				}
			}

			// print "footer"
			appendContent($body, opt.footer);

			// attach event handler function to beforePrint event
			function attachOnBeforePrintEvent($iframe, beforePrintHandler) {
				let win = $iframe.get(0);
				win = win.contentWindow || win.contentDocument || win;

				if (typeof beforePrintHandler === "function") {
					if ("matchMedia" in win) {
						win.matchMedia("print").addListener(function (mql) {
							if (mql.matches) beforePrintHandler();
						});
					} else {
						win.onbeforeprint = beforePrintHandler;
					}
				}
			}
			attachOnBeforePrintEvent($iframe, opt.beforePrintEvent);

			setTimeout(function () {
				if ($iframe.hasClass("MSIE")) {
					// check if the iframe was created with the ugly hack
					// and perform another ugly hack out of neccessity
					window.frames["printIframe"].focus();
					$head.append("<script> window.print(); </script>");
				} else {
					// proper method
					if ("print" in window) {
						$iframe[0].contentWindow.document.execCommand("print", false, null);
					} else {
						$iframe[0].contentWindow.focus();
						$iframe[0].contentWindow.print();
					}
				}

				// remove iframe after print
				if (!opt.debug) {
					setTimeout(function () {
						$iframe.remove();

					}, 1000);
				}

				// after print callback
				if (typeof opt.afterPrint === "function") {
					opt.afterPrint();
				}

			}, opt.printDelay);

		}, 333);

	};

	// defaults
	$.fn.printThis.defaults = {
		debug: false, // show the iframe for debugging
		importCSS: true, // import parent page css
		importStyle: true, // import style tags
		printContainer: true, // print outer container/$.selector
		loadCSS: "", // path to additional css file - use an array [] for multiple
		pageTitle: "", // add title to print page
		removeInline: false, // remove inline styles from print elements
		removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
		printDelay: 1000, // variable print delay
		header: null, // prefix to html
		footer: null, // postfix to html
		base: false, // preserve the BASE tag or accept a string for the URL
		formValues: true, // preserve input/form values
		canvas: true, // copy canvas content
		doctypeString: "<!DOCTYPE html>", // enter a different doctype for older markup
		removeScripts: false, // remove script tags from print content
		copyTagClasses: true, // copy classes from the html & body tag
		copyTagStyles: true, // copy styles from html & body tag (for CSS Variables)
		beforePrintEvent: null, // callback function for printEvent in iframe
		beforePrint: null, // function called before iframe is filled
		afterPrint: null // function called before iframe is removed
	};
})(jQuery);
