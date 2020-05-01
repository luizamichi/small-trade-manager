(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('jquery'), require('popper.js')) :
		typeof define === 'function' && define.amd ? define(['exports', 'jquery', 'popper.js'], factory) :
			(global = global || self, factory(global.bootstrap = {}, global.jQuery, global.Popper));
}(this, (function (exports, $, Popper) {
	'use strict';

	$ = $ && Object.prototype.hasOwnProperty.call($, 'default') ? $['default'] : $;
	Popper = Popper && Object.prototype.hasOwnProperty.call(Popper, 'default') ? Popper['default'] : Popper;

	function _defineProperties(target, props) {
		for (let i = 0; i < props.length; i++) {
			let descriptor = props[i];
			descriptor.enumerable = descriptor.enumerable || false;
			descriptor.configurable = true;
			if ('value' in descriptor) descriptor.writable = true;
			Object.defineProperty(target, descriptor.key, descriptor);
		}
	}

	function _createClass(Constructor, protoProps, staticProps) {
		if (protoProps) _defineProperties(Constructor.prototype, protoProps);
		if (staticProps) _defineProperties(Constructor, staticProps);
		return Constructor;
	}

	function _defineProperty(obj, key, value) {
		if (key in obj) {
			Object.defineProperty(obj, key, {
				value: value,
				enumerable: true,
				configurable: true,
				writable: true
			});
		} else {
			obj[key] = value;
		}

		return obj;
	}

	function ownKeys(object, enumerableOnly) {
		let keys = Object.keys(object);

		if (Object.getOwnPropertySymbols) {
			let symbols = Object.getOwnPropertySymbols(object);
			if (enumerableOnly) symbols = symbols.filter(function (sym) {
				return Object.getOwnPropertyDescriptor(object, sym).enumerable;
			});
			keys.push.apply(keys, symbols);
		}

		return keys;
	}

	function _objectSpread2(target) {
		for (let i = 1; i < arguments.length; i++) {
			let source = arguments[i] != null ? arguments[i] : {};

			if (i % 2) {
				ownKeys(Object(source), true).forEach(function (key) {
					_defineProperty(target, key, source[key]);
				});
			} else if (Object.getOwnPropertyDescriptors) {
				Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
			} else {
				ownKeys(Object(source)).forEach(function (key) {
					Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
				});
			}
		}

		return target;
	}

	function _inheritsLoose(subClass, superClass) {
		subClass.prototype = Object.create(superClass.prototype);
		subClass.prototype.constructor = subClass;
		subClass.__proto__ = superClass;
	}

	let TRANSITION_END = 'transitionend';
	let MAX_UID = 1000000;
	let MILLISECONDS_MULTIPLIER = 1000;

	function toType(obj) {
		if (obj === null || typeof obj === 'undefined') {
			return '' + obj;
		}

		return {}.toString.call(obj).match(/\s([a-z]+)/i)[1].toLowerCase();
	}

	function getSpecialTransitionEndEvent() {
		return {
			bindType: TRANSITION_END,
			delegateType: TRANSITION_END,
			handle: function handle(event) {
				if ($(event.target).is(this)) {
					return event.handleObj.handler.apply(this, arguments);
				}

				return undefined;
			}
		};
	}

	function transitionEndEmulator(duration) {
		let _this = this;

		let called = false;
		$(this).one(Util.TRANSITION_END, function () {
			called = true;
		});
		setTimeout(function () {
			if (!called) {
				Util.triggerTransitionEnd(_this);
			}
		}, duration);
		return this;
	}

	function setTransitionEndSupport() {
		$.fn.emulateTransitionEnd = transitionEndEmulator;
		$.event.special[Util.TRANSITION_END] = getSpecialTransitionEndEvent();
	}

	let Util = {
		TRANSITION_END: 'bsTransitionEnd',
		getUID: function getUID(prefix) {
			do {
				prefix += ~~(Math.random() * MAX_UID);
			} while (document.getElementById(prefix));

			return prefix;
		},
		getSelectorFromElement: function getSelectorFromElement(element) {
			let selector = element.getAttribute('data-target');

			if (!selector || selector === '#') {
				let hrefAttr = element.getAttribute('href');
				selector = hrefAttr && hrefAttr !== '#' ? hrefAttr.trim() : '';
			}

			try {
				return document.querySelector(selector) ? selector : null;
			} catch (err) {
				return null;
			}
		},
		getTransitionDurationFromElement: function getTransitionDurationFromElement(element) {
			if (!element) {
				return 0;
			}

			let transitionDuration = $(element).css('transition-duration');
			let transitionDelay = $(element).css('transition-delay');
			let floatTransitionDuration = parseFloat(transitionDuration);
			let floatTransitionDelay = parseFloat(transitionDelay);

			if (!floatTransitionDuration && !floatTransitionDelay) {
				return 0;
			}

			transitionDuration = transitionDuration.split(',')[0];
			transitionDelay = transitionDelay.split(',')[0];
			return (parseFloat(transitionDuration) + parseFloat(transitionDelay)) * MILLISECONDS_MULTIPLIER;
		},
		reflow: function reflow(element) {
			return element.offsetHeight;
		},
		triggerTransitionEnd: function triggerTransitionEnd(element) {
			$(element).trigger(TRANSITION_END);
		},
		supportsTransitionEnd: function supportsTransitionEnd() {
			return Boolean(TRANSITION_END);
		},
		isElement: function isElement(obj) {
			return (obj[0] || obj).nodeType;
		},
		typeCheckConfig: function typeCheckConfig(componentName, config, configTypes) {
			for (let property in configTypes) {
				if (Object.prototype.hasOwnProperty.call(configTypes, property)) {
					let expectedTypes = configTypes[property];
					let value = config[property];
					let valueType = value && Util.isElement(value) ? 'element' : toType(value);

					if (!new RegExp(expectedTypes).test(valueType)) {
						throw new Error(componentName.toUpperCase() + ': ' + ('Option "' + property + '" provided type "' + valueType + '" ') + ('but expected type "' + expectedTypes + '".'));
					}
				}
			}
		},
		findShadowRoot: function findShadowRoot(element) {
			if (!document.documentElement.attachShadow) {
				return null;
			}

			if (typeof element.getRootNode === 'function') {
				let root = element.getRootNode();
				return root instanceof ShadowRoot ? root : null;
			}

			if (element instanceof ShadowRoot) {
				return element;
			}

			if (!element.parentNode) {
				return null;
			}

			return Util.findShadowRoot(element.parentNode);
		},
		jQueryDetection: function jQueryDetection() {
			if (typeof $ === 'undefined') {
				throw new TypeError('Bootstrap\'s JavaScript requires jQuery. jQuery must be included before Bootstrap\'s JavaScript.');
			}

			let version = $.fn.jquery.split(' ')[0].split('.');
			let minMajor = 1;
			let ltMajor = 2;
			let minMinor = 9;
			let minPatch = 1;
			let maxMajor = 4;

			if (version[0] < ltMajor && version[1] < minMinor || version[0] === minMajor && version[1] === minMinor && version[2] < minPatch || version[0] >= maxMajor) {
				throw new Error('Bootstrap\'s JavaScript requires at least jQuery v1.9.1 but less than v4.0.0');
			}
		}
	};
	Util.jQueryDetection();
	setTransitionEndSupport();

	let NAME = 'alert';
	let VERSION = '4.5.0';
	let DATA_KEY = 'bs.alert';
	let EVENT_KEY = '.' + DATA_KEY;
	let DATA_API_KEY = '.data-api';
	let JQUERY_NO_CONFLICT = $.fn[NAME];
	let SELECTOR_DISMISS = '[data-dismiss="alert"]';
	let EVENT_CLOSE = 'close' + EVENT_KEY;
	let EVENT_CLOSED = 'closed' + EVENT_KEY;
	let EVENT_CLICK_DATA_API = 'click' + EVENT_KEY + DATA_API_KEY;
	let CLASS_NAME_ALERT = 'alert';
	let CLASS_NAME_FADE = 'fade';
	let CLASS_NAME_SHOW = 'show';

	let Alert = function () {
		function Alert(element) {
			this._element = element;
		}

		let _proto = Alert.prototype;

		_proto.close = function close(element) {
			let rootElement = this._element;

			if (element) {
				rootElement = this._getRootElement(element);
			}

			let customEvent = this._triggerCloseEvent(rootElement);

			if (customEvent.isDefaultPrevented()) {
				return;
			}

			this._removeElement(rootElement);
		};

		_proto.dispose = function dispose() {
			$.removeData(this._element, DATA_KEY);
			this._element = null;
		}
			;

		_proto._getRootElement = function _getRootElement(element) {
			let selector = Util.getSelectorFromElement(element);
			let parent = false;

			if (selector) {
				parent = document.querySelector(selector);
			}

			if (!parent) {
				parent = $(element).closest('.' + CLASS_NAME_ALERT)[0];
			}

			return parent;
		};

		_proto._triggerCloseEvent = function _triggerCloseEvent(element) {
			let closeEvent = $.Event(EVENT_CLOSE);
			$(element).trigger(closeEvent);
			return closeEvent;
		};

		_proto._removeElement = function _removeElement(element) {
			let _this = this;

			$(element).removeClass(CLASS_NAME_SHOW);

			if (!$(element).hasClass(CLASS_NAME_FADE)) {
				this._destroyElement(element);

				return;
			}

			let transitionDuration = Util.getTransitionDurationFromElement(element);
			$(element).one(Util.TRANSITION_END, function (event) {
				return _this._destroyElement(element, event);
			}).emulateTransitionEnd(transitionDuration);
		};

		_proto._destroyElement = function _destroyElement(element) {
			$(element).detach().trigger(EVENT_CLOSED).remove();
		}
			;

		Alert._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let $element = $(this);
				let data = $element.data(DATA_KEY);

				if (!data) {
					data = new Alert(this);
					$element.data(DATA_KEY, data);
				}

				if (config === 'close') {
					data[config](this);
				}
			});
		};

		Alert._handleDismiss = function _handleDismiss(alertInstance) {
			return function (event) {
				if (event) {
					event.preventDefault();
				}

				alertInstance.close(this);
			};
		};

		_createClass(Alert, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION;
			}
		}]);

		return Alert;
	}();

	$(document).on(EVENT_CLICK_DATA_API, SELECTOR_DISMISS, Alert._handleDismiss(new Alert()));

	$.fn[NAME] = Alert._jQueryInterface;
	$.fn[NAME].Constructor = Alert;

	$.fn[NAME].noConflict = function () {
		$.fn[NAME] = JQUERY_NO_CONFLICT;
		return Alert._jQueryInterface;
	};

	let NAME$1 = 'button';
	let VERSION$1 = '4.5.0';
	let DATA_KEY$1 = 'bs.button';
	let EVENT_KEY$1 = '.' + DATA_KEY$1;
	let DATA_API_KEY$1 = '.data-api';
	let JQUERY_NO_CONFLICT$1 = $.fn[NAME$1];
	let CLASS_NAME_ACTIVE = 'active';
	let CLASS_NAME_BUTTON = 'btn';
	let CLASS_NAME_FOCUS = 'focus';
	let SELECTOR_DATA_TOGGLE_CARROT = '[data-toggle^="button"]';
	let SELECTOR_DATA_TOGGLES = '[data-toggle="buttons"]';
	let SELECTOR_DATA_TOGGLE = '[data-toggle="button"]';
	let SELECTOR_DATA_TOGGLES_BUTTONS = '[data-toggle="buttons"] .btn';
	let SELECTOR_INPUT = 'input:not([type="hidden"])';
	let SELECTOR_ACTIVE = '.active';
	let SELECTOR_BUTTON = '.btn';
	let EVENT_CLICK_DATA_API$1 = 'click' + EVENT_KEY$1 + DATA_API_KEY$1;
	let EVENT_FOCUS_BLUR_DATA_API = 'focus' + EVENT_KEY$1 + DATA_API_KEY$1 + ' ' + ('blur' + EVENT_KEY$1 + DATA_API_KEY$1);
	let EVENT_LOAD_DATA_API = 'load' + EVENT_KEY$1 + DATA_API_KEY$1;

	let Button = function () {
		function Button(element) {
			this._element = element;
		}

		let _proto = Button.prototype;

		_proto.toggle = function toggle() {
			let triggerChangeEvent = true;
			let addAriaPressed = true;
			let rootElement = $(this._element).closest(SELECTOR_DATA_TOGGLES)[0];

			if (rootElement) {
				let input = this._element.querySelector(SELECTOR_INPUT);

				if (input) {
					if (input.type === 'radio') {
						if (input.checked && this._element.classList.contains(CLASS_NAME_ACTIVE)) {
							triggerChangeEvent = false;
						} else {
							let activeElement = rootElement.querySelector(SELECTOR_ACTIVE);

							if (activeElement) {
								$(activeElement).removeClass(CLASS_NAME_ACTIVE);
							}
						}
					}

					if (triggerChangeEvent) {
						if (input.type === 'checkbox' || input.type === 'radio') {
							input.checked = !this._element.classList.contains(CLASS_NAME_ACTIVE);
						}

						$(input).trigger('change');
					}

					input.focus();
					addAriaPressed = false;
				}
			}

			if (!(this._element.hasAttribute('disabled') || this._element.classList.contains('disabled'))) {
				if (addAriaPressed) {
					this._element.setAttribute('aria-pressed', !this._element.classList.contains(CLASS_NAME_ACTIVE));
				}

				if (triggerChangeEvent) {
					$(this._element).toggleClass(CLASS_NAME_ACTIVE);
				}
			}
		};

		_proto.dispose = function dispose() {
			$.removeData(this._element, DATA_KEY$1);
			this._element = null;
		}
			;

		Button._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$1);

				if (!data) {
					data = new Button(this);
					$(this).data(DATA_KEY$1, data);
				}

				if (config === 'toggle') {
					data[config]();
				}
			});
		};

		_createClass(Button, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$1;
			}
		}]);

		return Button;
	}();

	$(document).on(EVENT_CLICK_DATA_API$1, SELECTOR_DATA_TOGGLE_CARROT, function (event) {
		let button = event.target;
		let initialButton = button;

		if (!$(button).hasClass(CLASS_NAME_BUTTON)) {
			button = $(button).closest(SELECTOR_BUTTON)[0];
		}

		if (!button || button.hasAttribute('disabled') || button.classList.contains('disabled')) {
			event.preventDefault();
		} else {
			let inputBtn = button.querySelector(SELECTOR_INPUT);

			if (inputBtn && (inputBtn.hasAttribute('disabled') || inputBtn.classList.contains('disabled'))) {
				event.preventDefault();

				return;
			}

			if (initialButton.tagName === 'LABEL' && inputBtn && inputBtn.type === 'checkbox') {
				event.preventDefault();
			}

			Button._jQueryInterface.call($(button), 'toggle');
		}
	}).on(EVENT_FOCUS_BLUR_DATA_API, SELECTOR_DATA_TOGGLE_CARROT, function (event) {
		let button = $(event.target).closest(SELECTOR_BUTTON)[0];
		$(button).toggleClass(CLASS_NAME_FOCUS, /^focus(in)?$/.test(event.type));
	});
	$(window).on(EVENT_LOAD_DATA_API, function () {
		let buttons = [].slice.call(document.querySelectorAll(SELECTOR_DATA_TOGGLES_BUTTONS));

		for (let i = 0, len = buttons.length; i < len; i++) {
			let button = buttons[i];
			let input = button.querySelector(SELECTOR_INPUT);

			if (input.checked || input.hasAttribute('checked')) {
				button.classList.add(CLASS_NAME_ACTIVE);
			} else {
				button.classList.remove(CLASS_NAME_ACTIVE);
			}
		}

		buttons = [].slice.call(document.querySelectorAll(SELECTOR_DATA_TOGGLE));

		for (let _i = 0, _len = buttons.length; _i < _len; _i++) {
			let _button = buttons[_i];

			if (_button.getAttribute('aria-pressed') === 'true') {
				_button.classList.add(CLASS_NAME_ACTIVE);
			} else {
				_button.classList.remove(CLASS_NAME_ACTIVE);
			}
		}
	});

	$.fn[NAME$1] = Button._jQueryInterface;
	$.fn[NAME$1].Constructor = Button;

	$.fn[NAME$1].noConflict = function () {
		$.fn[NAME$1] = JQUERY_NO_CONFLICT$1;
		return Button._jQueryInterface;
	};

	let NAME$2 = 'carousel';
	let VERSION$2 = '4.5.0';
	let DATA_KEY$2 = 'bs.carousel';
	let EVENT_KEY$2 = '.' + DATA_KEY$2;
	let DATA_API_KEY$2 = '.data-api';
	let JQUERY_NO_CONFLICT$2 = $.fn[NAME$2];
	let ARROW_LEFT_KEYCODE = 37;

	let ARROW_RIGHT_KEYCODE = 39;

	let TOUCHEVENT_COMPAT_WAIT = 500;

	let SWIPE_THRESHOLD = 40;
	let Default = {
		interval: 5000,
		keyboard: true,
		slide: false,
		pause: 'hover',
		wrap: true,
		touch: true
	};
	let DefaultType = {
		interval: '(number|boolean)',
		keyboard: 'boolean',
		slide: '(boolean|string)',
		pause: '(string|boolean)',
		wrap: 'boolean',
		touch: 'boolean'
	};
	let DIRECTION_NEXT = 'next';
	let DIRECTION_PREV = 'prev';
	let DIRECTION_LEFT = 'left';
	let DIRECTION_RIGHT = 'right';
	let EVENT_SLIDE = 'slide' + EVENT_KEY$2;
	let EVENT_SLID = 'slid' + EVENT_KEY$2;
	let EVENT_KEYDOWN = 'keydown' + EVENT_KEY$2;
	let EVENT_MOUSEENTER = 'mouseenter' + EVENT_KEY$2;
	let EVENT_MOUSELEAVE = 'mouseleave' + EVENT_KEY$2;
	let EVENT_TOUCHSTART = 'touchstart' + EVENT_KEY$2;
	let EVENT_TOUCHMOVE = 'touchmove' + EVENT_KEY$2;
	let EVENT_TOUCHEND = 'touchend' + EVENT_KEY$2;
	let EVENT_POINTERDOWN = 'pointerdown' + EVENT_KEY$2;
	let EVENT_POINTERUP = 'pointerup' + EVENT_KEY$2;
	let EVENT_DRAG_START = 'dragstart' + EVENT_KEY$2;
	let EVENT_LOAD_DATA_API$1 = 'load' + EVENT_KEY$2 + DATA_API_KEY$2;
	let EVENT_CLICK_DATA_API$2 = 'click' + EVENT_KEY$2 + DATA_API_KEY$2;
	let CLASS_NAME_CAROUSEL = 'carousel';
	let CLASS_NAME_ACTIVE$1 = 'active';
	let CLASS_NAME_SLIDE = 'slide';
	let CLASS_NAME_RIGHT = 'carousel-item-right';
	let CLASS_NAME_LEFT = 'carousel-item-left';
	let CLASS_NAME_NEXT = 'carousel-item-next';
	let CLASS_NAME_PREV = 'carousel-item-prev';
	let CLASS_NAME_POINTER_EVENT = 'pointer-event';
	let SELECTOR_ACTIVE$1 = '.active';
	let SELECTOR_ACTIVE_ITEM = '.active.carousel-item';
	let SELECTOR_ITEM = '.carousel-item';
	let SELECTOR_ITEM_IMG = '.carousel-item img';
	let SELECTOR_NEXT_PREV = '.carousel-item-next, .carousel-item-prev';
	let SELECTOR_INDICATORS = '.carousel-indicators';
	let SELECTOR_DATA_SLIDE = '[data-slide], [data-slide-to]';
	let SELECTOR_DATA_RIDE = '[data-ride="carousel"]';
	let PointerType = {
		TOUCH: 'touch',
		PEN: 'pen'
	};

	let Carousel = function () {
		function Carousel(element, config) {
			this._items = null;
			this._interval = null;
			this._activeElement = null;
			this._isPaused = false;
			this._isSliding = false;
			this.touchTimeout = null;
			this.touchStartX = 0;
			this.touchDeltaX = 0;
			this._config = this._getConfig(config);
			this._element = element;
			this._indicatorsElement = this._element.querySelector(SELECTOR_INDICATORS);
			this._touchSupported = 'ontouchstart' in document.documentElement || navigator.maxTouchPoints > 0;
			this._pointerEvent = Boolean(window.PointerEvent || window.MSPointerEvent);

			this._addEventListeners();
		}

		let _proto = Carousel.prototype;

		_proto.next = function next() {
			if (!this._isSliding) {
				this._slide(DIRECTION_NEXT);
			}
		};

		_proto.nextWhenVisible = function nextWhenVisible() {
			if (!document.hidden && $(this._element).is(':visible') && $(this._element).css('visibility') !== 'hidden') {
				this.next();
			}
		};

		_proto.prev = function prev() {
			if (!this._isSliding) {
				this._slide(DIRECTION_PREV);
			}
		};

		_proto.pause = function pause(event) {
			if (!event) {
				this._isPaused = true;
			}

			if (this._element.querySelector(SELECTOR_NEXT_PREV)) {
				Util.triggerTransitionEnd(this._element);
				this.cycle(true);
			}

			clearInterval(this._interval);
			this._interval = null;
		};

		_proto.cycle = function cycle(event) {
			if (!event) {
				this._isPaused = false;
			}

			if (this._interval) {
				clearInterval(this._interval);
				this._interval = null;
			}

			if (this._config.interval && !this._isPaused) {
				this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval);
			}
		};

		_proto.to = function to(index) {
			let _this = this;

			this._activeElement = this._element.querySelector(SELECTOR_ACTIVE_ITEM);

			let activeIndex = this._getItemIndex(this._activeElement);

			if (index > this._items.length - 1 || index < 0) {
				return;
			}

			if (this._isSliding) {
				$(this._element).one(EVENT_SLID, function () {
					return _this.to(index);
				});
				return;
			}

			if (activeIndex === index) {
				this.pause();
				this.cycle();
				return;
			}

			let direction = index > activeIndex ? DIRECTION_NEXT : DIRECTION_PREV;

			this._slide(direction, this._items[index]);
		};

		_proto.dispose = function dispose() {
			$(this._element).off(EVENT_KEY$2);
			$.removeData(this._element, DATA_KEY$2);
			this._items = null;
			this._config = null;
			this._element = null;
			this._interval = null;
			this._isPaused = null;
			this._isSliding = null;
			this._activeElement = null;
			this._indicatorsElement = null;
		}
			;

		_proto._getConfig = function _getConfig(config) {
			config = _objectSpread2(_objectSpread2({}, Default), config);
			Util.typeCheckConfig(NAME$2, config, DefaultType);
			return config;
		};

		_proto._handleSwipe = function _handleSwipe() {
			let absDeltax = Math.abs(this.touchDeltaX);

			if (absDeltax <= SWIPE_THRESHOLD) {
				return;
			}

			let direction = absDeltax / this.touchDeltaX;
			this.touchDeltaX = 0;

			if (direction > 0) {
				this.prev();
			}

			if (direction < 0) {
				this.next();
			}
		};

		_proto._addEventListeners = function _addEventListeners() {
			let _this2 = this;

			if (this._config.keyboard) {
				$(this._element).on(EVENT_KEYDOWN, function (event) {
					return _this2._keydown(event);
				});
			}

			if (this._config.pause === 'hover') {
				$(this._element).on(EVENT_MOUSEENTER, function (event) {
					return _this2.pause(event);
				}).on(EVENT_MOUSELEAVE, function (event) {
					return _this2.cycle(event);
				});
			}

			if (this._config.touch) {
				this._addTouchEventListeners();
			}
		};

		_proto._addTouchEventListeners = function _addTouchEventListeners() {
			let _this3 = this;

			if (!this._touchSupported) {
				return;
			}

			let start = function start(event) {
				if (_this3._pointerEvent && PointerType[event.originalEvent.pointerType.toUpperCase()]) {
					_this3.touchStartX = event.originalEvent.clientX;
				} else if (!_this3._pointerEvent) {
					_this3.touchStartX = event.originalEvent.touches[0].clientX;
				}
			};

			let move = function move(event) {
				if (event.originalEvent.touches && event.originalEvent.touches.length > 1) {
					_this3.touchDeltaX = 0;
				} else {
					_this3.touchDeltaX = event.originalEvent.touches[0].clientX - _this3.touchStartX;
				}
			};

			let end = function end(event) {
				if (_this3._pointerEvent && PointerType[event.originalEvent.pointerType.toUpperCase()]) {
					_this3.touchDeltaX = event.originalEvent.clientX - _this3.touchStartX;
				}

				_this3._handleSwipe();

				if (_this3._config.pause === 'hover') {
					_this3.pause();

					if (_this3.touchTimeout) {
						clearTimeout(_this3.touchTimeout);
					}

					_this3.touchTimeout = setTimeout(function (event) {
						return _this3.cycle(event);
					}, TOUCHEVENT_COMPAT_WAIT + _this3._config.interval);
				}
			};

			$(this._element.querySelectorAll(SELECTOR_ITEM_IMG)).on(EVENT_DRAG_START, function (e) {
				return e.preventDefault();
			});

			if (this._pointerEvent) {
				$(this._element).on(EVENT_POINTERDOWN, function (event) {
					return start(event);
				});
				$(this._element).on(EVENT_POINTERUP, function (event) {
					return end(event);
				});

				this._element.classList.add(CLASS_NAME_POINTER_EVENT);
			} else {
				$(this._element).on(EVENT_TOUCHSTART, function (event) {
					return start(event);
				});
				$(this._element).on(EVENT_TOUCHMOVE, function (event) {
					return move(event);
				});
				$(this._element).on(EVENT_TOUCHEND, function (event) {
					return end(event);
				});
			}
		};

		_proto._keydown = function _keydown(event) {
			if (/input|textarea/i.test(event.target.tagName)) {
				return;
			}

			switch (event.which) {
				case ARROW_LEFT_KEYCODE:
					event.preventDefault();
					this.prev();
					break;

				case ARROW_RIGHT_KEYCODE:
					event.preventDefault();
					this.next();
					break;
			}
		};

		_proto._getItemIndex = function _getItemIndex(element) {
			this._items = element?.parentNode ? [].slice.call(element.parentNode.querySelectorAll(SELECTOR_ITEM)) : [];
			return this._items.indexOf(element);
		};

		_proto._getItemByDirection = function _getItemByDirection(direction, activeElement) {
			let isNextDirection = direction === DIRECTION_NEXT;
			let isPrevDirection = direction === DIRECTION_PREV;

			let activeIndex = this._getItemIndex(activeElement);

			let lastItemIndex = this._items.length - 1;
			let isGoingToWrap = isPrevDirection && activeIndex === 0 || isNextDirection && activeIndex === lastItemIndex;

			if (isGoingToWrap && !this._config.wrap) {
				return activeElement;
			}

			let delta = direction === DIRECTION_PREV ? -1 : 1;
			let itemIndex = (activeIndex + delta) % this._items.length;
			return itemIndex === -1 ? this._items[this._items.length - 1] : this._items[itemIndex];
		};

		_proto._triggerSlideEvent = function _triggerSlideEvent(relatedTarget, eventDirectionName) {
			let targetIndex = this._getItemIndex(relatedTarget);

			let fromIndex = this._getItemIndex(this._element.querySelector(SELECTOR_ACTIVE_ITEM));

			let slideEvent = $.Event(EVENT_SLIDE, {
				relatedTarget: relatedTarget,
				direction: eventDirectionName,
				from: fromIndex,
				to: targetIndex
			});
			$(this._element).trigger(slideEvent);
			return slideEvent;
		};

		_proto._setActiveIndicatorElement = function _setActiveIndicatorElement(element) {
			if (this._indicatorsElement) {
				let indicators = [].slice.call(this._indicatorsElement.querySelectorAll(SELECTOR_ACTIVE$1));
				$(indicators).removeClass(CLASS_NAME_ACTIVE$1);

				let nextIndicator = this._indicatorsElement.children[this._getItemIndex(element)];

				if (nextIndicator) {
					$(nextIndicator).addClass(CLASS_NAME_ACTIVE$1);
				}
			}
		};

		_proto._slide = function _slide(direction, element) {
			let _this4 = this;

			let activeElement = this._element.querySelector(SELECTOR_ACTIVE_ITEM);

			let activeElementIndex = this._getItemIndex(activeElement);

			let nextElement = element || activeElement && this._getItemByDirection(direction, activeElement);

			let nextElementIndex = this._getItemIndex(nextElement);

			let isCycling = Boolean(this._interval);
			let directionalClassName;
			let orderClassName;
			let eventDirectionName;

			if (direction === DIRECTION_NEXT) {
				directionalClassName = CLASS_NAME_LEFT;
				orderClassName = CLASS_NAME_NEXT;
				eventDirectionName = DIRECTION_LEFT;
			} else {
				directionalClassName = CLASS_NAME_RIGHT;
				orderClassName = CLASS_NAME_PREV;
				eventDirectionName = DIRECTION_RIGHT;
			}

			if (nextElement && $(nextElement).hasClass(CLASS_NAME_ACTIVE$1)) {
				this._isSliding = false;
				return;
			}

			let slideEvent = this._triggerSlideEvent(nextElement, eventDirectionName);

			if (slideEvent.isDefaultPrevented()) {
				return;
			}

			if (!activeElement || !nextElement) {
				return;
			}

			this._isSliding = true;

			if (isCycling) {
				this.pause();
			}

			this._setActiveIndicatorElement(nextElement);

			let slidEvent = $.Event(EVENT_SLID, {
				relatedTarget: nextElement,
				direction: eventDirectionName,
				from: activeElementIndex,
				to: nextElementIndex
			});

			if ($(this._element).hasClass(CLASS_NAME_SLIDE)) {
				$(nextElement).addClass(orderClassName);
				Util.reflow(nextElement);
				$(activeElement).addClass(directionalClassName);
				$(nextElement).addClass(directionalClassName);
				let nextElementInterval = parseInt(nextElement.getAttribute('data-interval'), 10);

				if (nextElementInterval) {
					this._config.defaultInterval = this._config.defaultInterval || this._config.interval;
					this._config.interval = nextElementInterval;
				} else {
					this._config.interval = this._config.defaultInterval || this._config.interval;
				}

				let transitionDuration = Util.getTransitionDurationFromElement(activeElement);
				$(activeElement).one(Util.TRANSITION_END, function () {
					$(nextElement).removeClass(directionalClassName + ' ' + orderClassName).addClass(CLASS_NAME_ACTIVE$1);
					$(activeElement).removeClass(CLASS_NAME_ACTIVE$1 + ' ' + orderClassName + ' ' + directionalClassName);
					_this4._isSliding = false;
					setTimeout(function () {
						return $(_this4._element).trigger(slidEvent);
					}, 0);
				}).emulateTransitionEnd(transitionDuration);
			} else {
				$(activeElement).removeClass(CLASS_NAME_ACTIVE$1);
				$(nextElement).addClass(CLASS_NAME_ACTIVE$1);
				this._isSliding = false;
				$(this._element).trigger(slidEvent);
			}

			if (isCycling) {
				this.cycle();
			}
		}
			;

		Carousel._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$2);

				let _config = _objectSpread2(_objectSpread2({}, Default), $(this).data());

				if (typeof config === 'object') {
					_config = _objectSpread2(_objectSpread2({}, _config), config);
				}

				let action = typeof config === 'string' ? config : _config.slide;

				if (!data) {
					data = new Carousel(this, _config);
					$(this).data(DATA_KEY$2, data);
				}

				if (typeof config === 'number') {
					data.to(config);
				} else if (typeof action === 'string') {
					if (typeof data[action] === 'undefined') {
						throw new TypeError('No method named "' + action + '"');
					}

					data[action]();
				} else if (_config.interval && _config.ride) {
					data.pause();
					data.cycle();
				}
			});
		};

		Carousel._dataApiClickHandler = function _dataApiClickHandler(event) {
			let selector = Util.getSelectorFromElement(this);

			if (!selector) {
				return;
			}

			let target = $(selector)[0];

			if (!target || !$(target).hasClass(CLASS_NAME_CAROUSEL)) {
				return;
			}

			let config = _objectSpread2(_objectSpread2({}, $(target).data()), $(this).data());

			let slideIndex = this.getAttribute('data-slide-to');

			if (slideIndex) {
				config.interval = false;
			}

			Carousel._jQueryInterface.call($(target), config);

			if (slideIndex) {
				$(target).data(DATA_KEY$2).to(slideIndex);
			}

			event.preventDefault();
		};

		_createClass(Carousel, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$2;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default;
			}
		}]);

		return Carousel;
	}();

	$(document).on(EVENT_CLICK_DATA_API$2, SELECTOR_DATA_SLIDE, Carousel._dataApiClickHandler);
	$(window).on(EVENT_LOAD_DATA_API$1, function () {
		let carousels = [].slice.call(document.querySelectorAll(SELECTOR_DATA_RIDE));

		for (let i = 0, len = carousels.length; i < len; i++) {
			let $carousel = $(carousels[i]);

			Carousel._jQueryInterface.call($carousel, $carousel.data());
		}
	});

	$.fn[NAME$2] = Carousel._jQueryInterface;
	$.fn[NAME$2].Constructor = Carousel;

	$.fn[NAME$2].noConflict = function () {
		$.fn[NAME$2] = JQUERY_NO_CONFLICT$2;
		return Carousel._jQueryInterface;
	};

	let NAME$3 = 'collapse';
	let VERSION$3 = '4.5.0';
	let DATA_KEY$3 = 'bs.collapse';
	let EVENT_KEY$3 = '.' + DATA_KEY$3;
	let DATA_API_KEY$3 = '.data-api';
	let JQUERY_NO_CONFLICT$3 = $.fn[NAME$3];
	let Default$1 = {
		toggle: true,
		parent: ''
	};
	let DefaultType$1 = {
		toggle: 'boolean',
		parent: '(string|element)'
	};
	let EVENT_SHOW = 'show' + EVENT_KEY$3;
	let EVENT_SHOWN = 'shown' + EVENT_KEY$3;
	let EVENT_HIDE = 'hide' + EVENT_KEY$3;
	let EVENT_HIDDEN = 'hidden' + EVENT_KEY$3;
	let EVENT_CLICK_DATA_API$3 = 'click' + EVENT_KEY$3 + DATA_API_KEY$3;
	let CLASS_NAME_SHOW$1 = 'show';
	let CLASS_NAME_COLLAPSE = 'collapse';
	let CLASS_NAME_COLLAPSING = 'collapsing';
	let CLASS_NAME_COLLAPSED = 'collapsed';
	let DIMENSION_WIDTH = 'width';
	let DIMENSION_HEIGHT = 'height';
	let SELECTOR_ACTIVES = '.show, .collapsing';
	let SELECTOR_DATA_TOGGLE$1 = '[data-toggle="collapse"]';

	let Collapse = function () {
		function Collapse(element, config) {
			this._isTransitioning = false;
			this._element = element;
			this._config = this._getConfig(config);
			this._triggerArray = [].slice.call(document.querySelectorAll('[data-toggle="collapse"][href="#' + element.id + '"],' + ('[data-toggle="collapse"][data-target="#' + element.id + '"]')));
			let toggleList = [].slice.call(document.querySelectorAll(SELECTOR_DATA_TOGGLE$1));

			for (let i = 0, len = toggleList.length; i < len; i++) {
				let elem = toggleList[i];
				let selector = Util.getSelectorFromElement(elem);
				let filterElement = [].slice.call(document.querySelectorAll(selector)).filter(function (foundElem) {
					return foundElem === element;
				});

				if (selector !== null && filterElement.length > 0) {
					this._selector = selector;

					this._triggerArray.push(elem);
				}
			}

			this._parent = this._config.parent ? this._getParent() : null;

			if (!this._config.parent) {
				this._addAriaAndCollapsedClass(this._element, this._triggerArray);
			}

			if (this._config.toggle) {
				this.toggle();
			}
		}

		let _proto = Collapse.prototype;

		_proto.toggle = function toggle() {
			if ($(this._element).hasClass(CLASS_NAME_SHOW$1)) {
				this.hide();
			} else {
				this.show();
			}
		};

		_proto.show = function show() {
			let _this = this;

			if (this._isTransitioning || $(this._element).hasClass(CLASS_NAME_SHOW$1)) {
				return;
			}

			let actives;
			let activesData;

			if (this._parent) {
				actives = [].slice.call(this._parent.querySelectorAll(SELECTOR_ACTIVES)).filter(function (elem) {
					if (typeof _this._config.parent === 'string') {
						return elem.getAttribute('data-parent') === _this._config.parent;
					}

					return elem.classList.contains(CLASS_NAME_COLLAPSE);
				});

				if (actives.length === 0) {
					actives = null;
				}
			}

			if (actives) {
				activesData = $(actives).not(this._selector).data(DATA_KEY$3);

				if (activesData?._isTransitioning) {
					return;
				}
			}

			let startEvent = $.Event(EVENT_SHOW);
			$(this._element).trigger(startEvent);

			if (startEvent.isDefaultPrevented()) {
				return;
			}

			if (actives) {
				Collapse._jQueryInterface.call($(actives).not(this._selector), 'hide');

				if (!activesData) {
					$(actives).data(DATA_KEY$3, null);
				}
			}

			let dimension = this._getDimension();

			$(this._element).removeClass(CLASS_NAME_COLLAPSE).addClass(CLASS_NAME_COLLAPSING);
			this._element.style[dimension] = 0;

			if (this._triggerArray.length) {
				$(this._triggerArray).removeClass(CLASS_NAME_COLLAPSED).attr('aria-expanded', true);
			}

			this.setTransitioning(true);

			let complete = function complete() {
				$(_this._element).removeClass(CLASS_NAME_COLLAPSING).addClass(CLASS_NAME_COLLAPSE + ' ' + CLASS_NAME_SHOW$1);
				_this._element.style[dimension] = '';

				_this.setTransitioning(false);

				$(_this._element).trigger(EVENT_SHOWN);
			};

			let capitalizedDimension = dimension[0].toUpperCase() + dimension.slice(1);
			let scrollSize = 'scroll' + capitalizedDimension;
			let transitionDuration = Util.getTransitionDurationFromElement(this._element);
			$(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
			this._element.style[dimension] = this._element[scrollSize] + 'px';
		};

		_proto.hide = function hide() {
			let _this2 = this;

			if (this._isTransitioning || !$(this._element).hasClass(CLASS_NAME_SHOW$1)) {
				return;
			}

			let startEvent = $.Event(EVENT_HIDE);
			$(this._element).trigger(startEvent);

			if (startEvent.isDefaultPrevented()) {
				return;
			}

			let dimension = this._getDimension();

			this._element.style[dimension] = this._element.getBoundingClientRect()[dimension] + 'px';
			Util.reflow(this._element);
			$(this._element).addClass(CLASS_NAME_COLLAPSING).removeClass(CLASS_NAME_COLLAPSE + ' ' + CLASS_NAME_SHOW$1);
			let triggerArrayLength = this._triggerArray.length;

			if (triggerArrayLength > 0) {
				for (let i = 0; i < triggerArrayLength; i++) {
					let trigger = this._triggerArray[i];
					let selector = Util.getSelectorFromElement(trigger);

					if (selector !== null) {
						let $elem = $([].slice.call(document.querySelectorAll(selector)));

						if (!$elem.hasClass(CLASS_NAME_SHOW$1)) {
							$(trigger).addClass(CLASS_NAME_COLLAPSED).attr('aria-expanded', false);
						}
					}
				}
			}

			this.setTransitioning(true);

			let complete = function complete() {
				_this2.setTransitioning(false);

				$(_this2._element).removeClass(CLASS_NAME_COLLAPSING).addClass(CLASS_NAME_COLLAPSE).trigger(EVENT_HIDDEN);
			};

			this._element.style[dimension] = '';
			let transitionDuration = Util.getTransitionDurationFromElement(this._element);
			$(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
		};

		_proto.setTransitioning = function setTransitioning(isTransitioning) {
			this._isTransitioning = isTransitioning;
		};

		_proto.dispose = function dispose() {
			$.removeData(this._element, DATA_KEY$3);
			this._config = null;
			this._parent = null;
			this._element = null;
			this._triggerArray = null;
			this._isTransitioning = null;
		}
			;

		_proto._getConfig = function _getConfig(config) {
			config = _objectSpread2(_objectSpread2({}, Default$1), config);
			config.toggle = Boolean(config.toggle);

			Util.typeCheckConfig(NAME$3, config, DefaultType$1);
			return config;
		};

		_proto._getDimension = function _getDimension() {
			let hasWidth = $(this._element).hasClass(DIMENSION_WIDTH);
			return hasWidth ? DIMENSION_WIDTH : DIMENSION_HEIGHT;
		};

		_proto._getParent = function _getParent() {
			let _this3 = this;

			let parent;

			if (Util.isElement(this._config.parent)) {
				parent = this._config.parent;

				if (typeof this._config.parent.jquery !== 'undefined') {
					parent = this._config.parent[0];
				}
			} else {
				parent = document.querySelector(this._config.parent);
			}

			let selector = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]';
			let children = [].slice.call(parent.querySelectorAll(selector));
			$(children).each(function (i, element) {
				_this3._addAriaAndCollapsedClass(Collapse._getTargetFromElement(element), [element]);
			});
			return parent;
		};

		_proto._addAriaAndCollapsedClass = function _addAriaAndCollapsedClass(element, triggerArray) {
			let isOpen = $(element).hasClass(CLASS_NAME_SHOW$1);

			if (triggerArray.length) {
				$(triggerArray).toggleClass(CLASS_NAME_COLLAPSED, !isOpen).attr('aria-expanded', isOpen);
			}
		}
			;

		Collapse._getTargetFromElement = function _getTargetFromElement(element) {
			let selector = Util.getSelectorFromElement(element);
			return selector ? document.querySelector(selector) : null;
		};

		Collapse._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let $this = $(this);
				let data = $this.data(DATA_KEY$3);

				let _config = _objectSpread2(_objectSpread2(_objectSpread2({}, Default$1), $this.data()), typeof config === 'object' && config ? config : {});

				if (!data && _config.toggle && typeof config === 'string' && /show|hide/.test(config)) {
					_config.toggle = false;
				}

				if (!data) {
					data = new Collapse(this, _config);
					$this.data(DATA_KEY$3, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config]();
				}
			});
		};

		_createClass(Collapse, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$3;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$1;
			}
		}]);

		return Collapse;
	}();

	$(document).on(EVENT_CLICK_DATA_API$3, SELECTOR_DATA_TOGGLE$1, function (event) {
		if (event.currentTarget.tagName === 'A') {
			event.preventDefault();
		}

		let $trigger = $(this);
		let selector = Util.getSelectorFromElement(this);
		let selectors = [].slice.call(document.querySelectorAll(selector));
		$(selectors).each(function () {
			let $target = $(this);
			let data = $target.data(DATA_KEY$3);
			let config = data ? 'toggle' : $trigger.data();

			Collapse._jQueryInterface.call($target, config);
		});
	});

	$.fn[NAME$3] = Collapse._jQueryInterface;
	$.fn[NAME$3].Constructor = Collapse;

	$.fn[NAME$3].noConflict = function () {
		$.fn[NAME$3] = JQUERY_NO_CONFLICT$3;
		return Collapse._jQueryInterface;
	};

	let NAME$4 = 'dropdown';
	let VERSION$4 = '4.5.0';
	let DATA_KEY$4 = 'bs.dropdown';
	let EVENT_KEY$4 = '.' + DATA_KEY$4;
	let DATA_API_KEY$4 = '.data-api';
	let JQUERY_NO_CONFLICT$4 = $.fn[NAME$4];
	let ESCAPE_KEYCODE = 27;

	let SPACE_KEYCODE = 32;

	let TAB_KEYCODE = 9;

	let ARROW_UP_KEYCODE = 38;

	let ARROW_DOWN_KEYCODE = 40;

	let RIGHT_MOUSE_BUTTON_WHICH = 3;

	let REGEXP_KEYDOWN = new RegExp(ARROW_UP_KEYCODE + '|' + ARROW_DOWN_KEYCODE + '|' + ESCAPE_KEYCODE);
	let EVENT_HIDE$1 = 'hide' + EVENT_KEY$4;
	let EVENT_HIDDEN$1 = 'hidden' + EVENT_KEY$4;
	let EVENT_SHOW$1 = 'show' + EVENT_KEY$4;
	let EVENT_SHOWN$1 = 'shown' + EVENT_KEY$4;
	let EVENT_CLICK = 'click' + EVENT_KEY$4;
	let EVENT_CLICK_DATA_API$4 = 'click' + EVENT_KEY$4 + DATA_API_KEY$4;
	let EVENT_KEYDOWN_DATA_API = 'keydown' + EVENT_KEY$4 + DATA_API_KEY$4;
	let EVENT_KEYUP_DATA_API = 'keyup' + EVENT_KEY$4 + DATA_API_KEY$4;
	let CLASS_NAME_DISABLED = 'disabled';
	let CLASS_NAME_SHOW$2 = 'show';
	let CLASS_NAME_DROPUP = 'dropup';
	let CLASS_NAME_DROPRIGHT = 'dropright';
	let CLASS_NAME_DROPLEFT = 'dropleft';
	let CLASS_NAME_MENURIGHT = 'dropdown-menu-right';
	let CLASS_NAME_POSITION_STATIC = 'position-static';
	let SELECTOR_DATA_TOGGLE$2 = '[data-toggle="dropdown"]';
	let SELECTOR_FORM_CHILD = '.dropdown form';
	let SELECTOR_MENU = '.dropdown-menu';
	let SELECTOR_NAVBAR_NAV = '.navbar-nav';
	let SELECTOR_VISIBLE_ITEMS = '.dropdown-menu .dropdown-item:not(.disabled):not(:disabled)';
	let PLACEMENT_TOP = 'top-start';
	let PLACEMENT_TOPEND = 'top-end';
	let PLACEMENT_BOTTOM = 'bottom-start';
	let PLACEMENT_BOTTOMEND = 'bottom-end';
	let PLACEMENT_RIGHT = 'right-start';
	let PLACEMENT_LEFT = 'left-start';
	let Default$2 = {
		offset: 0,
		flip: true,
		boundary: 'scrollParent',
		reference: 'toggle',
		display: 'dynamic',
		popperConfig: null
	};
	let DefaultType$2 = {
		offset: '(number|string|function)',
		flip: 'boolean',
		boundary: '(string|element)',
		reference: '(string|element)',
		display: 'string',
		popperConfig: '(null|object)'
	};

	let Dropdown = function () {
		function Dropdown(element, config) {
			this._element = element;
			this._popper = null;
			this._config = this._getConfig(config);
			this._menu = this._getMenuElement();
			this._inNavbar = this._detectNavbar();

			this._addEventListeners();
		}

		let _proto = Dropdown.prototype;

		_proto.toggle = function toggle() {
			if (this._element.disabled || $(this._element).hasClass(CLASS_NAME_DISABLED)) {
				return;
			}

			let isActive = $(this._menu).hasClass(CLASS_NAME_SHOW$2);

			Dropdown._clearMenus();

			if (isActive) {
				return;
			}

			this.show(true);
		};

		_proto.show = function show(usePopper) {
			if (usePopper === void 0) {
				usePopper = false;
			}

			if (this._element.disabled || $(this._element).hasClass(CLASS_NAME_DISABLED) || $(this._menu).hasClass(CLASS_NAME_SHOW$2)) {
				return;
			}

			let relatedTarget = {
				relatedTarget: this._element
			};
			let showEvent = $.Event(EVENT_SHOW$1, relatedTarget);

			let parent = Dropdown._getParentFromElement(this._element);

			$(parent).trigger(showEvent);

			if (showEvent.isDefaultPrevented()) {
				return;
			}

			if (!this._inNavbar && usePopper) {
				if (typeof Popper === 'undefined') {
					throw new TypeError('Bootstrap\'s dropdowns require Popper.js (https://popper.js.org/)');
				}

				let referenceElement = this._element;

				if (this._config.reference === 'parent') {
					referenceElement = parent;
				} else if (Util.isElement(this._config.reference)) {
					referenceElement = this._config.reference;

					if (typeof this._config.reference.jquery !== 'undefined') {
						referenceElement = this._config.reference[0];
					}
				}

				if (this._config.boundary !== 'scrollParent') {
					$(parent).addClass(CLASS_NAME_POSITION_STATIC);
				}

				this._popper = new Popper(referenceElement, this._menu, this._getPopperConfig());
			}

			if ('ontouchstart' in document.documentElement && $(parent).closest(SELECTOR_NAVBAR_NAV).length === 0) {
				$(document.body).children().on('mouseover', null, $.noop);
			}

			this._element.focus();

			this._element.setAttribute('aria-expanded', true);

			$(this._menu).toggleClass(CLASS_NAME_SHOW$2);
			$(parent).toggleClass(CLASS_NAME_SHOW$2).trigger($.Event(EVENT_SHOWN$1, relatedTarget));
		};

		_proto.hide = function hide() {
			if (this._element.disabled || $(this._element).hasClass(CLASS_NAME_DISABLED) || !$(this._menu).hasClass(CLASS_NAME_SHOW$2)) {
				return;
			}

			let relatedTarget = {
				relatedTarget: this._element
			};
			let hideEvent = $.Event(EVENT_HIDE$1, relatedTarget);

			let parent = Dropdown._getParentFromElement(this._element);

			$(parent).trigger(hideEvent);

			if (hideEvent.isDefaultPrevented()) {
				return;
			}

			if (this._popper) {
				this._popper.destroy();
			}

			$(this._menu).toggleClass(CLASS_NAME_SHOW$2);
			$(parent).toggleClass(CLASS_NAME_SHOW$2).trigger($.Event(EVENT_HIDDEN$1, relatedTarget));
		};

		_proto.dispose = function dispose() {
			$.removeData(this._element, DATA_KEY$4);
			$(this._element).off(EVENT_KEY$4);
			this._element = null;
			this._menu = null;

			if (this._popper !== null) {
				this._popper.destroy();

				this._popper = null;
			}
		};

		_proto.update = function update() {
			this._inNavbar = this._detectNavbar();

			if (this._popper !== null) {
				this._popper.scheduleUpdate();
			}
		}
			;

		_proto._addEventListeners = function _addEventListeners() {
			let _this = this;

			$(this._element).on(EVENT_CLICK, function (event) {
				event.preventDefault();
				event.stopPropagation();

				_this.toggle();
			});
		};

		_proto._getConfig = function _getConfig(config) {
			config = _objectSpread2(_objectSpread2(_objectSpread2({}, this.constructor.Default), $(this._element).data()), config);
			Util.typeCheckConfig(NAME$4, config, this.constructor.DefaultType);
			return config;
		};

		_proto._getMenuElement = function _getMenuElement() {
			if (!this._menu) {
				let parent = Dropdown._getParentFromElement(this._element);

				if (parent) {
					this._menu = parent.querySelector(SELECTOR_MENU);
				}
			}

			return this._menu;
		};

		_proto._getPlacement = function _getPlacement() {
			let $parentDropdown = $(this._element.parentNode);
			let placement = PLACEMENT_BOTTOM;

			if ($parentDropdown.hasClass(CLASS_NAME_DROPUP)) {
				placement = $(this._menu).hasClass(CLASS_NAME_MENURIGHT) ? PLACEMENT_TOPEND : PLACEMENT_TOP;
			} else if ($parentDropdown.hasClass(CLASS_NAME_DROPRIGHT)) {
				placement = PLACEMENT_RIGHT;
			} else if ($parentDropdown.hasClass(CLASS_NAME_DROPLEFT)) {
				placement = PLACEMENT_LEFT;
			} else if ($(this._menu).hasClass(CLASS_NAME_MENURIGHT)) {
				placement = PLACEMENT_BOTTOMEND;
			}

			return placement;
		};

		_proto._detectNavbar = function _detectNavbar() {
			return $(this._element).closest('.navbar').length > 0;
		};

		_proto._getOffset = function _getOffset() {
			let _this2 = this;

			let offset = {};

			if (typeof this._config.offset === 'function') {
				offset.fn = function (data) {
					data.offsets = _objectSpread2(_objectSpread2({}, data.offsets), _this2._config.offset(data.offsets, _this2._element) || {});
					return data;
				};
			} else {
				offset.offset = this._config.offset;
			}

			return offset;
		};

		_proto._getPopperConfig = function _getPopperConfig() {
			let popperConfig = {
				placement: this._getPlacement(),
				modifiers: {
					offset: this._getOffset(),
					flip: {
						enabled: this._config.flip
					},
					preventOverflow: {
						boundariesElement: this._config.boundary
					}
				}
			};

			if (this._config.display === 'static') {
				popperConfig.modifiers.applyStyle = {
					enabled: false
				};
			}

			return _objectSpread2(_objectSpread2({}, popperConfig), this._config.popperConfig);
		}
			;

		Dropdown._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$4);

				let _config = typeof config === 'object' ? config : null;

				if (!data) {
					data = new Dropdown(this, _config);
					$(this).data(DATA_KEY$4, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config]();
				}
			});
		};

		Dropdown._clearMenus = function _clearMenus(event) {
			if (event && (event.which === RIGHT_MOUSE_BUTTON_WHICH || event.type === 'keyup' && event.which !== TAB_KEYCODE)) {
				return;
			}

			let toggles = [].slice.call(document.querySelectorAll(SELECTOR_DATA_TOGGLE$2));

			for (let i = 0, len = toggles.length; i < len; i++) {
				let parent = Dropdown._getParentFromElement(toggles[i]);

				let context = $(toggles[i]).data(DATA_KEY$4);
				let relatedTarget = {
					relatedTarget: toggles[i]
				};

				if (event && event.type === 'click') {
					relatedTarget.clickEvent = event;
				}

				if (!context) {
					continue;
				}

				let dropdownMenu = context._menu;

				if (!$(parent).hasClass(CLASS_NAME_SHOW$2)) {
					continue;
				}

				if (event && (event.type === 'click' && /input|textarea/i.test(event.target.tagName) || event.type === 'keyup' && event.which === TAB_KEYCODE) && $.contains(parent, event.target)) {
					continue;
				}

				let hideEvent = $.Event(EVENT_HIDE$1, relatedTarget);
				$(parent).trigger(hideEvent);

				if (hideEvent.isDefaultPrevented()) {
					continue;
				}

				if ('ontouchstart' in document.documentElement) {
					$(document.body).children().off('mouseover', null, $.noop);
				}

				toggles[i].setAttribute('aria-expanded', 'false');

				if (context._popper) {
					context._popper.destroy();
				}

				$(dropdownMenu).removeClass(CLASS_NAME_SHOW$2);
				$(parent).removeClass(CLASS_NAME_SHOW$2).trigger($.Event(EVENT_HIDDEN$1, relatedTarget));
			}
		};

		Dropdown._getParentFromElement = function _getParentFromElement(element) {
			let parent;
			let selector = Util.getSelectorFromElement(element);

			if (selector) {
				parent = document.querySelector(selector);
			}

			return parent || element.parentNode;
		}
			;

		Dropdown._dataApiKeydownHandler = function _dataApiKeydownHandler(event) {
			if (/input|textarea/i.test(event.target.tagName) ? event.which === SPACE_KEYCODE || event.which !== ESCAPE_KEYCODE && (event.which !== ARROW_DOWN_KEYCODE && event.which !== ARROW_UP_KEYCODE || $(event.target).closest(SELECTOR_MENU).length) : !REGEXP_KEYDOWN.test(event.which)) {
				return;
			}

			if (this.disabled || $(this).hasClass(CLASS_NAME_DISABLED)) {
				return;
			}

			let parent = Dropdown._getParentFromElement(this);

			let isActive = $(parent).hasClass(CLASS_NAME_SHOW$2);

			if (!isActive && event.which === ESCAPE_KEYCODE) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();

			if (!isActive || isActive && (event.which === ESCAPE_KEYCODE || event.which === SPACE_KEYCODE)) {
				if (event.which === ESCAPE_KEYCODE) {
					$(parent.querySelector(SELECTOR_DATA_TOGGLE$2)).trigger('focus');
				}

				$(this).trigger('click');
				return;
			}

			let items = [].slice.call(parent.querySelectorAll(SELECTOR_VISIBLE_ITEMS)).filter(function (item) {
				return $(item).is(':visible');
			});

			if (items.length === 0) {
				return;
			}

			let index = items.indexOf(event.target);

			if (event.which === ARROW_UP_KEYCODE && index > 0) {
				index--;
			}

			if (event.which === ARROW_DOWN_KEYCODE && index < items.length - 1) {
				index++;
			}

			if (index < 0) {
				index = 0;
			}

			items[index].focus();
		};

		_createClass(Dropdown, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$4;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$2;
			}
		}, {
			key: 'DefaultType',
			get: function get() {
				return DefaultType$2;
			}
		}]);

		return Dropdown;
	}();

	$(document).on(EVENT_KEYDOWN_DATA_API, SELECTOR_DATA_TOGGLE$2, Dropdown._dataApiKeydownHandler).on(EVENT_KEYDOWN_DATA_API, SELECTOR_MENU, Dropdown._dataApiKeydownHandler).on(EVENT_CLICK_DATA_API$4 + ' ' + EVENT_KEYUP_DATA_API, Dropdown._clearMenus).on(EVENT_CLICK_DATA_API$4, SELECTOR_DATA_TOGGLE$2, function (event) {
		event.preventDefault();
		event.stopPropagation();

		Dropdown._jQueryInterface.call($(this), 'toggle');
	}).on(EVENT_CLICK_DATA_API$4, SELECTOR_FORM_CHILD, function (e) {
		e.stopPropagation();
	});

	$.fn[NAME$4] = Dropdown._jQueryInterface;
	$.fn[NAME$4].Constructor = Dropdown;

	$.fn[NAME$4].noConflict = function () {
		$.fn[NAME$4] = JQUERY_NO_CONFLICT$4;
		return Dropdown._jQueryInterface;
	};

	let NAME$5 = 'modal';
	let VERSION$5 = '4.5.0';
	let DATA_KEY$5 = 'bs.modal';
	let EVENT_KEY$5 = '.' + DATA_KEY$5;
	let DATA_API_KEY$5 = '.data-api';
	let JQUERY_NO_CONFLICT$5 = $.fn[NAME$5];
	let ESCAPE_KEYCODE$1 = 27;

	let Default$3 = {
		backdrop: true,
		keyboard: true,
		focus: true,
		show: true
	};
	let DefaultType$3 = {
		backdrop: '(boolean|string)',
		keyboard: 'boolean',
		focus: 'boolean',
		show: 'boolean'
	};
	let EVENT_HIDE$2 = 'hide' + EVENT_KEY$5;
	let EVENT_HIDE_PREVENTED = 'hidePrevented' + EVENT_KEY$5;
	let EVENT_HIDDEN$2 = 'hidden' + EVENT_KEY$5;
	let EVENT_SHOW$2 = 'show' + EVENT_KEY$5;
	let EVENT_SHOWN$2 = 'shown' + EVENT_KEY$5;
	let EVENT_FOCUSIN = 'focusin' + EVENT_KEY$5;
	let EVENT_RESIZE = 'resize' + EVENT_KEY$5;
	let EVENT_CLICK_DISMISS = 'click.dismiss' + EVENT_KEY$5;
	let EVENT_KEYDOWN_DISMISS = 'keydown.dismiss' + EVENT_KEY$5;
	let EVENT_MOUSEUP_DISMISS = 'mouseup.dismiss' + EVENT_KEY$5;
	let EVENT_MOUSEDOWN_DISMISS = 'mousedown.dismiss' + EVENT_KEY$5;
	let EVENT_CLICK_DATA_API$5 = 'click' + EVENT_KEY$5 + DATA_API_KEY$5;
	let CLASS_NAME_SCROLLABLE = 'modal-dialog-scrollable';
	let CLASS_NAME_SCROLLBAR_MEASURER = 'modal-scrollbar-measure';
	let CLASS_NAME_BACKDROP = 'modal-backdrop';
	let CLASS_NAME_OPEN = 'modal-open';
	let CLASS_NAME_FADE$1 = 'fade';
	let CLASS_NAME_SHOW$3 = 'show';
	let CLASS_NAME_STATIC = 'modal-static';
	let SELECTOR_DIALOG = '.modal-dialog';
	let SELECTOR_MODAL_BODY = '.modal-body';
	let SELECTOR_DATA_TOGGLE$3 = '[data-toggle="modal"]';
	let SELECTOR_DATA_DISMISS = '[data-dismiss="modal"]';
	let SELECTOR_FIXED_CONTENT = '.fixed-top, .fixed-bottom, .is-fixed, .sticky-top';
	let SELECTOR_STICKY_CONTENT = '.sticky-top';

	let Modal = function () {
		function Modal(element, config) {
			this._config = this._getConfig(config);
			this._element = element;
			this._dialog = element.querySelector(SELECTOR_DIALOG);
			this._backdrop = null;
			this._isShown = false;
			this._isBodyOverflowing = false;
			this._ignoreBackdropClick = false;
			this._isTransitioning = false;
			this._scrollbarWidth = 0;
		}

		let _proto = Modal.prototype;

		_proto.toggle = function toggle(relatedTarget) {
			return this._isShown ? this.hide() : this.show(relatedTarget);
		};

		_proto.show = function show(relatedTarget) {
			let _this = this;

			if (this._isShown || this._isTransitioning) {
				return;
			}

			if ($(this._element).hasClass(CLASS_NAME_FADE$1)) {
				this._isTransitioning = true;
			}

			let showEvent = $.Event(EVENT_SHOW$2, {
				relatedTarget: relatedTarget
			});
			$(this._element).trigger(showEvent);

			if (this._isShown || showEvent.isDefaultPrevented()) {
				return;
			}

			this._isShown = true;

			this._checkScrollbar();

			this._setScrollbar();

			this._adjustDialog();

			this._setEscapeEvent();

			this._setResizeEvent();

			$(this._element).on(EVENT_CLICK_DISMISS, SELECTOR_DATA_DISMISS, function (event) {
				return _this.hide(event);
			});
			$(this._dialog).on(EVENT_MOUSEDOWN_DISMISS, function () {
				$(_this._element).one(EVENT_MOUSEUP_DISMISS, function (event) {
					if ($(event.target).is(_this._element)) {
						_this._ignoreBackdropClick = true;
					}
				});
			});

			this._showBackdrop(function () {
				return _this._showElement(relatedTarget);
			});
		};

		_proto.hide = function hide(event) {
			let _this2 = this;

			if (event) {
				event.preventDefault();
			}

			if (!this._isShown || this._isTransitioning) {
				return;
			}

			let hideEvent = $.Event(EVENT_HIDE$2);
			$(this._element).trigger(hideEvent);

			if (!this._isShown || hideEvent.isDefaultPrevented()) {
				return;
			}

			this._isShown = false;
			let transition = $(this._element).hasClass(CLASS_NAME_FADE$1);

			if (transition) {
				this._isTransitioning = true;
			}

			this._setEscapeEvent();

			this._setResizeEvent();

			$(document).off(EVENT_FOCUSIN);
			$(this._element).removeClass(CLASS_NAME_SHOW$3);
			$(this._element).off(EVENT_CLICK_DISMISS);
			$(this._dialog).off(EVENT_MOUSEDOWN_DISMISS);

			if (transition) {
				let transitionDuration = Util.getTransitionDurationFromElement(this._element);
				$(this._element).one(Util.TRANSITION_END, function (event) {
					return _this2._hideModal(event);
				}).emulateTransitionEnd(transitionDuration);
			} else {
				this._hideModal();
			}
		};

		_proto.dispose = function dispose() {
			[window, this._element, this._dialog].forEach(function (htmlElement) {
				return $(htmlElement).off(EVENT_KEY$5);
			});

			$(document).off(EVENT_FOCUSIN);
			$.removeData(this._element, DATA_KEY$5);
			this._config = null;
			this._element = null;
			this._dialog = null;
			this._backdrop = null;
			this._isShown = null;
			this._isBodyOverflowing = null;
			this._ignoreBackdropClick = null;
			this._isTransitioning = null;
			this._scrollbarWidth = null;
		};

		_proto.handleUpdate = function handleUpdate() {
			this._adjustDialog();
		}
			;

		_proto._getConfig = function _getConfig(config) {
			config = _objectSpread2(_objectSpread2({}, Default$3), config);
			Util.typeCheckConfig(NAME$5, config, DefaultType$3);
			return config;
		};

		_proto._triggerBackdropTransition = function _triggerBackdropTransition() {
			let _this3 = this;

			if (this._config.backdrop === 'static') {
				let hideEventPrevented = $.Event(EVENT_HIDE_PREVENTED);
				$(this._element).trigger(hideEventPrevented);

				if (hideEventPrevented.defaultPrevented) {
					return;
				}

				this._element.classList.add(CLASS_NAME_STATIC);

				let modalTransitionDuration = Util.getTransitionDurationFromElement(this._element);
				$(this._element).one(Util.TRANSITION_END, function () {
					_this3._element.classList.remove(CLASS_NAME_STATIC);
				}).emulateTransitionEnd(modalTransitionDuration);

				this._element.focus();
			} else {
				this.hide();
			}
		};

		_proto._showElement = function _showElement(relatedTarget) {
			let _this4 = this;

			let transition = $(this._element).hasClass(CLASS_NAME_FADE$1);
			let modalBody = this._dialog ? this._dialog.querySelector(SELECTOR_MODAL_BODY) : null;

			if (!this._element.parentNode || this._element.parentNode.nodeType !== Node.ELEMENT_NODE) {
				document.body.appendChild(this._element);
			}

			this._element.style.display = 'block';

			this._element.removeAttribute('aria-hidden');

			this._element.setAttribute('aria-modal', true);

			if ($(this._dialog).hasClass(CLASS_NAME_SCROLLABLE) && modalBody) {
				modalBody.scrollTop = 0;
			} else {
				this._element.scrollTop = 0;
			}

			if (transition) {
				Util.reflow(this._element);
			}

			$(this._element).addClass(CLASS_NAME_SHOW$3);

			if (this._config.focus) {
				this._enforceFocus();
			}

			let shownEvent = $.Event(EVENT_SHOWN$2, {
				relatedTarget: relatedTarget
			});

			let transitionComplete = function transitionComplete() {
				if (_this4._config.focus) {
					_this4._element.focus();
				}

				_this4._isTransitioning = false;
				$(_this4._element).trigger(shownEvent);
			};

			if (transition) {
				let transitionDuration = Util.getTransitionDurationFromElement(this._dialog);
				$(this._dialog).one(Util.TRANSITION_END, transitionComplete).emulateTransitionEnd(transitionDuration);
			} else {
				transitionComplete();
			}
		};

		_proto._enforceFocus = function _enforceFocus() {
			let _this5 = this;

			$(document).off(EVENT_FOCUSIN)
				.on(EVENT_FOCUSIN, function (event) {
					if (document !== event.target && _this5._element !== event.target && $(_this5._element).has(event.target).length === 0) {
						_this5._element.focus();
					}
				});
		};

		_proto._setEscapeEvent = function _setEscapeEvent() {
			let _this6 = this;

			if (this._isShown) {
				$(this._element).on(EVENT_KEYDOWN_DISMISS, function (event) {
					if (_this6._config.keyboard && event.which === ESCAPE_KEYCODE$1) {
						event.preventDefault();

						_this6.hide();
					} else if (!_this6._config.keyboard && event.which === ESCAPE_KEYCODE$1) {
						_this6._triggerBackdropTransition();
					}
				});
			} else if (!this._isShown) {
				$(this._element).off(EVENT_KEYDOWN_DISMISS);
			}
		};

		_proto._setResizeEvent = function _setResizeEvent() {
			let _this7 = this;

			if (this._isShown) {
				$(window).on(EVENT_RESIZE, function (event) {
					return _this7.handleUpdate(event);
				});
			} else {
				$(window).off(EVENT_RESIZE);
			}
		};

		_proto._hideModal = function _hideModal() {
			let _this8 = this;

			this._element.style.display = 'none';

			this._element.setAttribute('aria-hidden', true);

			this._element.removeAttribute('aria-modal');

			this._isTransitioning = false;

			this._showBackdrop(function () {
				$(document.body).removeClass(CLASS_NAME_OPEN);

				_this8._resetAdjustments();

				_this8._resetScrollbar();

				$(_this8._element).trigger(EVENT_HIDDEN$2);
			});
		};

		_proto._removeBackdrop = function _removeBackdrop() {
			if (this._backdrop) {
				$(this._backdrop).remove();
				this._backdrop = null;
			}
		};

		_proto._showBackdrop = function _showBackdrop(callback) {
			let _this9 = this;

			let animate = $(this._element).hasClass(CLASS_NAME_FADE$1) ? CLASS_NAME_FADE$1 : '';

			if (this._isShown && this._config.backdrop) {
				this._backdrop = document.createElement('div');
				this._backdrop.className = CLASS_NAME_BACKDROP;

				if (animate) {
					this._backdrop.classList.add(animate);
				}

				$(this._backdrop).appendTo(document.body);
				$(this._element).on(EVENT_CLICK_DISMISS, function (event) {
					if (_this9._ignoreBackdropClick) {
						_this9._ignoreBackdropClick = false;
						return;
					}

					if (event.target !== event.currentTarget) {
						return;
					}

					_this9._triggerBackdropTransition();
				});

				if (animate) {
					Util.reflow(this._backdrop);
				}

				$(this._backdrop).addClass(CLASS_NAME_SHOW$3);

				if (!callback) {
					return;
				}

				if (!animate) {
					callback();
					return;
				}

				let backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop);
				$(this._backdrop).one(Util.TRANSITION_END, callback).emulateTransitionEnd(backdropTransitionDuration);
			} else if (!this._isShown && this._backdrop) {
				$(this._backdrop).removeClass(CLASS_NAME_SHOW$3);

				let callbackRemove = function callbackRemove() {
					_this9._removeBackdrop();

					if (callback) {
						callback();
					}
				};

				if ($(this._element).hasClass(CLASS_NAME_FADE$1)) {
					let _backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop);

					$(this._backdrop).one(Util.TRANSITION_END, callbackRemove).emulateTransitionEnd(_backdropTransitionDuration);
				} else {
					callbackRemove();
				}
			} else if (callback) {
				callback();
			}
		}

			;

		_proto._adjustDialog = function _adjustDialog() {
			let isModalOverflowing = this._element.scrollHeight > document.documentElement.clientHeight;

			if (!this._isBodyOverflowing && isModalOverflowing) {
				this._element.style.paddingLeft = this._scrollbarWidth + 'px';
			}

			if (this._isBodyOverflowing && !isModalOverflowing) {
				this._element.style.paddingRight = this._scrollbarWidth + 'px';
			}
		};

		_proto._resetAdjustments = function _resetAdjustments() {
			this._element.style.paddingLeft = '';
			this._element.style.paddingRight = '';
		};

		_proto._checkScrollbar = function _checkScrollbar() {
			let rect = document.body.getBoundingClientRect();
			this._isBodyOverflowing = Math.round(rect.left + rect.right) < window.innerWidth;
			this._scrollbarWidth = this._getScrollbarWidth();
		};

		_proto._setScrollbar = function _setScrollbar() {
			let _this10 = this;

			if (this._isBodyOverflowing) {
				let fixedContent = [].slice.call(document.querySelectorAll(SELECTOR_FIXED_CONTENT));
				let stickyContent = [].slice.call(document.querySelectorAll(SELECTOR_STICKY_CONTENT));

				$(fixedContent).each(function (index, element) {
					let actualPadding = element.style.paddingRight;
					let calculatedPadding = $(element).css('padding-right');
					$(element).data('padding-right', actualPadding).css('padding-right', parseFloat(calculatedPadding) + _this10._scrollbarWidth + 'px');
				});

				$(stickyContent).each(function (index, element) {
					let actualMargin = element.style.marginRight;
					let calculatedMargin = $(element).css('margin-right');
					$(element).data('margin-right', actualMargin).css('margin-right', parseFloat(calculatedMargin) - _this10._scrollbarWidth + 'px');
				});

				let actualPadding = document.body.style.paddingRight;
				let calculatedPadding = $(document.body).css('padding-right');
				$(document.body).data('padding-right', actualPadding).css('padding-right', parseFloat(calculatedPadding) + this._scrollbarWidth + 'px');
			}

			$(document.body).addClass(CLASS_NAME_OPEN);
		};

		_proto._resetScrollbar = function _resetScrollbar() {
			let fixedContent = [].slice.call(document.querySelectorAll(SELECTOR_FIXED_CONTENT));
			$(fixedContent).each(function (index, element) {
				let padding = $(element).data('padding-right');
				$(element).removeData('padding-right');
				element.style.paddingRight = padding || '';
			});

			let elements = [].slice.call(document.querySelectorAll('' + SELECTOR_STICKY_CONTENT));
			$(elements).each(function (index, element) {
				let margin = $(element).data('margin-right');

				if (typeof margin !== 'undefined') {
					$(element).css('margin-right', margin).removeData('margin-right');
				}
			});

			let padding = $(document.body).data('padding-right');
			$(document.body).removeData('padding-right');
			document.body.style.paddingRight = padding || '';
		};

		_proto._getScrollbarWidth = function _getScrollbarWidth() {
			let scrollDiv = document.createElement('div');
			scrollDiv.className = CLASS_NAME_SCROLLBAR_MEASURER;
			document.body.appendChild(scrollDiv);
			let scrollbarWidth = scrollDiv.getBoundingClientRect().width - scrollDiv.clientWidth;
			document.body.removeChild(scrollDiv);
			return scrollbarWidth;
		}
			;

		Modal._jQueryInterface = function _jQueryInterface(config, relatedTarget) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$5);

				let _config = _objectSpread2(_objectSpread2(_objectSpread2({}, Default$3), $(this).data()), typeof config === 'object' && config ? config : {});

				if (!data) {
					data = new Modal(this, _config);
					$(this).data(DATA_KEY$5, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config](relatedTarget);
				} else if (_config.show) {
					data.show(relatedTarget);
				}
			});
		};

		_createClass(Modal, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$5;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$3;
			}
		}]);

		return Modal;
	}();

	$(document).on(EVENT_CLICK_DATA_API$5, SELECTOR_DATA_TOGGLE$3, function (event) {
		let _this11 = this;

		let target;
		let selector = Util.getSelectorFromElement(this);

		if (selector) {
			target = document.querySelector(selector);
		}

		let config = $(target).data(DATA_KEY$5) ? 'toggle' : _objectSpread2(_objectSpread2({}, $(target).data()), $(this).data());

		if (this.tagName === 'A' || this.tagName === 'AREA') {
			event.preventDefault();
		}

		let $target = $(target).one(EVENT_SHOW$2, function (showEvent) {
			if (showEvent.isDefaultPrevented()) {
				return;
			}

			$target.one(EVENT_HIDDEN$2, function () {
				if ($(_this11).is(':visible')) {
					_this11.focus();
				}
			});
		});

		Modal._jQueryInterface.call($(target), config, this);
	});

	$.fn[NAME$5] = Modal._jQueryInterface;
	$.fn[NAME$5].Constructor = Modal;

	$.fn[NAME$5].noConflict = function () {
		$.fn[NAME$5] = JQUERY_NO_CONFLICT$5;
		return Modal._jQueryInterface;
	};

	let uriAttrs = ['background', 'cite', 'href', 'itemtype', 'longdesc', 'poster', 'src', 'xlink:href'];
	let ARIA_ATTRIBUTE_PATTERN = /^aria-[\w-]*$/i;
	let DefaultWhitelist = {
		'*': ['class', 'dir', 'id', 'lang', 'role', ARIA_ATTRIBUTE_PATTERN],
		a: ['target', 'href', 'title', 'rel'],
		area: [],
		b: [],
		br: [],
		col: [],
		code: [],
		div: [],
		em: [],
		hr: [],
		h1: [],
		h2: [],
		h3: [],
		h4: [],
		h5: [],
		h6: [],
		i: [],
		img: ['src', 'srcset', 'alt', 'title', 'width', 'height'],
		li: [],
		ol: [],
		p: [],
		pre: [],
		s: [],
		small: [],
		span: [],
		sub: [],
		sup: [],
		strong: [],
		u: [],
		ul: []
	};

	let SAFE_URL_PATTERN = /^(?:(?:https?|mailto|ftp|tel|file):|[^#&/:?]*(?:[#/?]|$))/gi;

	let DATA_URL_PATTERN = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[\d+/a-z]+=*$/i;

	function allowedAttribute(attr, allowedAttributeList) {
		let attrName = attr.nodeName.toLowerCase();

		if (allowedAttributeList.indexOf(attrName) !== -1) {
			if (uriAttrs.indexOf(attrName) !== -1) {
				return Boolean(attr.nodeValue.match(SAFE_URL_PATTERN) || attr.nodeValue.match(DATA_URL_PATTERN));
			}

			return true;
		}

		let regExp = allowedAttributeList.filter(function (attrRegex) {
			return attrRegex instanceof RegExp;
		});

		for (let i = 0, len = regExp.length; i < len; i++) {
			if (attrName.match(regExp[i])) {
				return true;
			}
		}

		return false;
	}

	function sanitizeHtml(unsafeHtml, whiteList, sanitizeFn) {
		if (unsafeHtml.length === 0) {
			return unsafeHtml;
		}

		if (sanitizeFn && typeof sanitizeFn === 'function') {
			return sanitizeFn(unsafeHtml);
		}

		let domParser = new window.DOMParser();
		let createdDocument = domParser.parseFromString(unsafeHtml, 'text/html');
		let whitelistKeys = Object.keys(whiteList);
		let elements = [].slice.call(createdDocument.body.querySelectorAll('*'));

		let _loop = function _loop(i, len) {
			let el = elements[i];
			let elName = el.nodeName.toLowerCase();

			if (whitelistKeys.indexOf(el.nodeName.toLowerCase()) === -1) {
				el.parentNode.removeChild(el);
				return 'continue';
			}

			let attributeList = [].slice.call(el.attributes);
			let whitelistedAttributes = [].concat(whiteList['*'] || [], whiteList[elName] || []);
			attributeList.forEach(function (attr) {
				if (!allowedAttribute(attr, whitelistedAttributes)) {
					el.removeAttribute(attr.nodeName);
				}
			});
		};

		for (let i = 0, len = elements.length; i < len; i++) {
			let _ret = _loop(i);

			if (_ret === 'continue') continue;
		}

		return createdDocument.body.innerHTML;
	}

	let NAME$6 = 'tooltip';
	let VERSION$6 = '4.5.0';
	let DATA_KEY$6 = 'bs.tooltip';
	let EVENT_KEY$6 = '.' + DATA_KEY$6;
	let JQUERY_NO_CONFLICT$6 = $.fn[NAME$6];
	let CLASS_PREFIX = 'bs-tooltip';
	let BSCLS_PREFIX_REGEX = new RegExp("(^|\\s)" + CLASS_PREFIX + "\\S+", 'g');
	let DISALLOWED_ATTRIBUTES = ['sanitize', 'whiteList', 'sanitizeFn'];
	let DefaultType$4 = {
		animation: 'boolean',
		template: 'string',
		title: '(string|element|function)',
		trigger: 'string',
		delay: '(number|object)',
		html: 'boolean',
		selector: '(string|boolean)',
		placement: '(string|function)',
		offset: '(number|string|function)',
		container: '(string|element|boolean)',
		fallbackPlacement: '(string|array)',
		boundary: '(string|element)',
		sanitize: 'boolean',
		sanitizeFn: '(null|function)',
		whiteList: 'object',
		popperConfig: '(null|object)'
	};
	let AttachmentMap = {
		AUTO: 'auto',
		TOP: 'top',
		RIGHT: 'right',
		BOTTOM: 'bottom',
		LEFT: 'left'
	};
	let Default$4 = {
		animation: true,
		template: '<div class="tooltip" role="tooltip">' + '<div class="arrow"></div>' + '<div class="tooltip-inner"></div></div>',
		trigger: 'hover focus',
		title: '',
		delay: 0,
		html: false,
		selector: false,
		placement: 'top',
		offset: 0,
		container: false,
		fallbackPlacement: 'flip',
		boundary: 'scrollParent',
		sanitize: true,
		sanitizeFn: null,
		whiteList: DefaultWhitelist,
		popperConfig: null
	};
	let HOVER_STATE_SHOW = 'show';
	let HOVER_STATE_OUT = 'out';
	let Event = {
		HIDE: 'hide' + EVENT_KEY$6,
		HIDDEN: 'hidden' + EVENT_KEY$6,
		SHOW: 'show' + EVENT_KEY$6,
		SHOWN: 'shown' + EVENT_KEY$6,
		INSERTED: 'inserted' + EVENT_KEY$6,
		CLICK: 'click' + EVENT_KEY$6,
		FOCUSIN: 'focusin' + EVENT_KEY$6,
		FOCUSOUT: 'focusout' + EVENT_KEY$6,
		MOUSEENTER: 'mouseenter' + EVENT_KEY$6,
		MOUSELEAVE: 'mouseleave' + EVENT_KEY$6
	};
	let CLASS_NAME_FADE$2 = 'fade';
	let CLASS_NAME_SHOW$4 = 'show';
	let SELECTOR_TOOLTIP_INNER = '.tooltip-inner';
	let SELECTOR_ARROW = '.arrow';
	let TRIGGER_HOVER = 'hover';
	let TRIGGER_FOCUS = 'focus';
	let TRIGGER_CLICK = 'click';
	let TRIGGER_MANUAL = 'manual';

	let Tooltip = function () {
		function Tooltip(element, config) {
			if (typeof Popper === 'undefined') {
				throw new TypeError('Bootstrap\'s tooltips require Popper.js (https://popper.js.org/)');
			}

			this._isEnabled = true;
			this._timeout = 0;
			this._hoverState = '';
			this._activeTrigger = {};
			this._popper = null;

			this.element = element;
			this.config = this._getConfig(config);
			this.tip = null;

			this._setListeners();
		}

		let _proto = Tooltip.prototype;

		_proto.enable = function enable() {
			this._isEnabled = true;
		};

		_proto.disable = function disable() {
			this._isEnabled = false;
		};

		_proto.toggleEnabled = function toggleEnabled() {
			this._isEnabled = !this._isEnabled;
		};

		_proto.toggle = function toggle(event) {
			if (!this._isEnabled) {
				return;
			}

			if (event) {
				let dataKey = this.constructor.DATA_KEY;
				let context = $(event.currentTarget).data(dataKey);

				if (!context) {
					context = new this.constructor(event.currentTarget, this._getDelegateConfig());
					$(event.currentTarget).data(dataKey, context);
				}

				context._activeTrigger.click = !context._activeTrigger.click;

				if (context._isWithActiveTrigger()) {
					context._enter(null, context);
				} else {
					context._leave(null, context);
				}
			} else {
				if ($(this.getTipElement()).hasClass(CLASS_NAME_SHOW$4)) {
					this._leave(null, this);

					return;
				}

				this._enter(null, this);
			}
		};

		_proto.dispose = function dispose() {
			clearTimeout(this._timeout);
			$.removeData(this.element, this.constructor.DATA_KEY);
			$(this.element).off(this.constructor.EVENT_KEY);
			$(this.element).closest('.modal').off('hide.bs.modal', this._hideModalHandler);

			if (this.tip) {
				$(this.tip).remove();
			}

			this._isEnabled = null;
			this._timeout = null;
			this._hoverState = null;
			this._activeTrigger = null;

			if (this._popper) {
				this._popper.destroy();
			}

			this._popper = null;
			this.element = null;
			this.config = null;
			this.tip = null;
		};

		_proto.show = function show() {
			let _this = this;

			if ($(this.element).css('display') === 'none') {
				throw new Error('Please use show on visible elements');
			}

			let showEvent = $.Event(this.constructor.Event.SHOW);

			if (this.isWithContent() && this._isEnabled) {
				$(this.element).trigger(showEvent);
				let shadowRoot = Util.findShadowRoot(this.element);
				let isInTheDom = $.contains(shadowRoot !== null ? shadowRoot : this.element.ownerDocument.documentElement, this.element);

				if (showEvent.isDefaultPrevented() || !isInTheDom) {
					return;
				}

				let tip = this.getTipElement();
				let tipId = Util.getUID(this.constructor.NAME);
				tip.setAttribute('id', tipId);
				this.element.setAttribute('aria-describedby', tipId);
				this.setContent();

				if (this.config.animation) {
					$(tip).addClass(CLASS_NAME_FADE$2);
				}

				let placement = typeof this.config.placement === 'function' ? this.config.placement.call(this, tip, this.element) : this.config.placement;

				let attachment = this._getAttachment(placement);

				this.addAttachmentClass(attachment);

				let container = this._getContainer();

				$(tip).data(this.constructor.DATA_KEY, this);

				if (!$.contains(this.element.ownerDocument.documentElement, this.tip)) {
					$(tip).appendTo(container);
				}

				$(this.element).trigger(this.constructor.Event.INSERTED);
				this._popper = new Popper(this.element, tip, this._getPopperConfig(attachment));
				$(tip).addClass(CLASS_NAME_SHOW$4);

				if ('ontouchstart' in document.documentElement) {
					$(document.body).children().on('mouseover', null, $.noop);
				}

				let complete = function complete() {
					if (_this.config.animation) {
						_this._fixTransition();
					}

					let prevHoverState = _this._hoverState;
					_this._hoverState = null;
					$(_this.element).trigger(_this.constructor.Event.SHOWN);

					if (prevHoverState === HOVER_STATE_OUT) {
						_this._leave(null, _this);
					}
				};

				if ($(this.tip).hasClass(CLASS_NAME_FADE$2)) {
					let transitionDuration = Util.getTransitionDurationFromElement(this.tip);
					$(this.tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
				} else {
					complete();
				}
			}
		};

		_proto.hide = function hide(callback) {
			let _this2 = this;

			let tip = this.getTipElement();
			let hideEvent = $.Event(this.constructor.Event.HIDE);

			let complete = function complete() {
				if (_this2._hoverState !== HOVER_STATE_SHOW && tip.parentNode) {
					tip.parentNode.removeChild(tip);
				}

				_this2._cleanTipClass();

				_this2.element.removeAttribute('aria-describedby');

				$(_this2.element).trigger(_this2.constructor.Event.HIDDEN);

				if (_this2._popper !== null) {
					_this2._popper.destroy();
				}

				if (callback) {
					callback();
				}
			};

			$(this.element).trigger(hideEvent);

			if (hideEvent.isDefaultPrevented()) {
				return;
			}

			$(tip).removeClass(CLASS_NAME_SHOW$4);

			if ('ontouchstart' in document.documentElement) {
				$(document.body).children().off('mouseover', null, $.noop);
			}

			this._activeTrigger[TRIGGER_CLICK] = false;
			this._activeTrigger[TRIGGER_FOCUS] = false;
			this._activeTrigger[TRIGGER_HOVER] = false;

			if ($(this.tip).hasClass(CLASS_NAME_FADE$2)) {
				let transitionDuration = Util.getTransitionDurationFromElement(tip);
				$(tip).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
			} else {
				complete();
			}

			this._hoverState = '';
		};

		_proto.update = function update() {
			if (this._popper !== null) {
				this._popper.scheduleUpdate();
			}
		}
			;

		_proto.isWithContent = function isWithContent() {
			return Boolean(this.getTitle());
		};

		_proto.addAttachmentClass = function addAttachmentClass(attachment) {
			$(this.getTipElement()).addClass(CLASS_PREFIX + '-' + attachment);
		};

		_proto.getTipElement = function getTipElement() {
			this.tip = this.tip || $(this.config.template)[0];
			return this.tip;
		};

		_proto.setContent = function setContent() {
			let tip = this.getTipElement();
			this.setElementContent($(tip.querySelectorAll(SELECTOR_TOOLTIP_INNER)), this.getTitle());
			$(tip).removeClass(CLASS_NAME_FADE$2 + ' ' + CLASS_NAME_SHOW$4);
		};

		_proto.setElementContent = function setElementContent($element, content) {
			if (typeof content === 'object' && (content.nodeType || content.jquery)) {
				if (this.config.html) {
					if (!$(content).parent().is($element)) {
						$element.empty().append(content);
					}
				} else {
					$element.text($(content).text());
				}

				return;
			}

			if (this.config.html) {
				if (this.config.sanitize) {
					content = sanitizeHtml(content, this.config.whiteList, this.config.sanitizeFn);
				}

				$element.html(content);
			} else {
				$element.text(content);
			}
		};

		_proto.getTitle = function getTitle() {
			let title = this.element.getAttribute('data-original-title');

			if (!title) {
				title = typeof this.config.title === 'function' ? this.config.title.call(this.element) : this.config.title;
			}

			return title;
		}
			;

		_proto._getPopperConfig = function _getPopperConfig(attachment) {
			let _this3 = this;

			let defaultBsConfig = {
				placement: attachment,
				modifiers: {
					offset: this._getOffset(),
					flip: {
						behavior: this.config.fallbackPlacement
					},
					arrow: {
						element: SELECTOR_ARROW
					},
					preventOverflow: {
						boundariesElement: this.config.boundary
					}
				},
				onCreate: function onCreate(data) {
					if (data.originalPlacement !== data.placement) {
						_this3._handlePopperPlacementChange(data);
					}
				},
				onUpdate: function onUpdate(data) {
					return _this3._handlePopperPlacementChange(data);
				}
			};
			return _objectSpread2(_objectSpread2({}, defaultBsConfig), this.config.popperConfig);
		};

		_proto._getOffset = function _getOffset() {
			let _this4 = this;

			let offset = {};

			if (typeof this.config.offset === 'function') {
				offset.fn = function (data) {
					data.offsets = _objectSpread2(_objectSpread2({}, data.offsets), _this4.config.offset(data.offsets, _this4.element) || {});
					return data;
				};
			} else {
				offset.offset = this.config.offset;
			}

			return offset;
		};

		_proto._getContainer = function _getContainer() {
			if (this.config.container === false) {
				return document.body;
			}

			if (Util.isElement(this.config.container)) {
				return $(this.config.container);
			}

			return $(document).find(this.config.container);
		};

		_proto._getAttachment = function _getAttachment(placement) {
			return AttachmentMap[placement.toUpperCase()];
		};

		_proto._setListeners = function _setListeners() {
			let _this5 = this;

			let triggers = this.config.trigger.split(' ');
			triggers.forEach(function (trigger) {
				if (trigger === 'click') {
					$(_this5.element).on(_this5.constructor.Event.CLICK, _this5.config.selector, function (event) {
						return _this5.toggle(event);
					});
				} else if (trigger !== TRIGGER_MANUAL) {
					let eventIn = trigger === TRIGGER_HOVER ? _this5.constructor.Event.MOUSEENTER : _this5.constructor.Event.FOCUSIN;
					let eventOut = trigger === TRIGGER_HOVER ? _this5.constructor.Event.MOUSELEAVE : _this5.constructor.Event.FOCUSOUT;
					$(_this5.element).on(eventIn, _this5.config.selector, function (event) {
						return _this5._enter(event);
					}).on(eventOut, _this5.config.selector, function (event) {
						return _this5._leave(event);
					});
				}
			});

			this._hideModalHandler = function () {
				if (_this5.element) {
					_this5.hide();
				}
			};

			$(this.element).closest('.modal').on('hide.bs.modal', this._hideModalHandler);

			if (this.config.selector) {
				this.config = _objectSpread2(_objectSpread2({}, this.config), {}, {
					trigger: 'manual',
					selector: ''
				});
			} else {
				this._fixTitle();
			}
		};

		_proto._fixTitle = function _fixTitle() {
			let titleType = typeof this.element.getAttribute('data-original-title');

			if (this.element.getAttribute('title') || titleType !== 'string') {
				this.element.setAttribute('data-original-title', this.element.getAttribute('title') || '');
				this.element.setAttribute('title', '');
			}
		};

		_proto._enter = function _enter(event, context) {
			let dataKey = this.constructor.DATA_KEY;
			context = context || $(event.currentTarget).data(dataKey);

			if (!context) {
				context = new this.constructor(event.currentTarget, this._getDelegateConfig());
				$(event.currentTarget).data(dataKey, context);
			}

			if (event) {
				context._activeTrigger[event.type === 'focusin' ? TRIGGER_FOCUS : TRIGGER_HOVER] = true;
			}

			if ($(context.getTipElement()).hasClass(CLASS_NAME_SHOW$4) || context._hoverState === HOVER_STATE_SHOW) {
				context._hoverState = HOVER_STATE_SHOW;
				return;
			}

			clearTimeout(context._timeout);
			context._hoverState = HOVER_STATE_SHOW;

			if (!context.config.delay?.show) {
				context.show();
				return;
			}

			context._timeout = setTimeout(function () {
				if (context._hoverState === HOVER_STATE_SHOW) {
					context.show();
				}
			}, context.config.delay.show);
		};

		_proto._leave = function _leave(event, context) {
			let dataKey = this.constructor.DATA_KEY;
			context = context || $(event.currentTarget).data(dataKey);

			if (!context) {
				context = new this.constructor(event.currentTarget, this._getDelegateConfig());
				$(event.currentTarget).data(dataKey, context);
			}

			if (event) {
				context._activeTrigger[event.type === 'focusout' ? TRIGGER_FOCUS : TRIGGER_HOVER] = false;
			}

			if (context._isWithActiveTrigger()) {
				return;
			}

			clearTimeout(context._timeout);
			context._hoverState = HOVER_STATE_OUT;

			if (!context.config.delay?.hide) {
				context.hide();
				return;
			}

			context._timeout = setTimeout(function () {
				if (context._hoverState === HOVER_STATE_OUT) {
					context.hide();
				}
			}, context.config.delay.hide);
		};

		_proto._isWithActiveTrigger = function _isWithActiveTrigger() {
			for (let trigger in this._activeTrigger) {
				if (this._activeTrigger[trigger]) {
					return true;
				}
			}

			return false;
		};

		_proto._getConfig = function _getConfig(config) {
			let dataAttributes = $(this.element).data();
			Object.keys(dataAttributes).forEach(function (dataAttr) {
				if (DISALLOWED_ATTRIBUTES.indexOf(dataAttr) !== -1) {
					delete dataAttributes[dataAttr];
				}
			});
			config = _objectSpread2(_objectSpread2(_objectSpread2({}, this.constructor.Default), dataAttributes), typeof config === 'object' && config ? config : {});

			if (typeof config.delay === 'number') {
				config.delay = {
					show: config.delay,
					hide: config.delay
				};
			}

			if (typeof config.title === 'number') {
				config.title = config.title.toString();
			}

			if (typeof config.content === 'number') {
				config.content = config.content.toString();
			}

			Util.typeCheckConfig(NAME$6, config, this.constructor.DefaultType);

			if (config.sanitize) {
				config.template = sanitizeHtml(config.template, config.whiteList, config.sanitizeFn);
			}

			return config;
		};

		_proto._getDelegateConfig = function _getDelegateConfig() {
			let config = {};

			if (this.config) {
				for (let key in this.config) {
					if (this.constructor.Default[key] !== this.config[key]) {
						config[key] = this.config[key];
					}
				}
			}

			return config;
		};

		_proto._cleanTipClass = function _cleanTipClass() {
			let $tip = $(this.getTipElement());
			let tabClass = $tip.attr('class').match(BSCLS_PREFIX_REGEX);

			if (tabClass?.length) {
				$tip.removeClass(tabClass.join(''));
			}
		};

		_proto._handlePopperPlacementChange = function _handlePopperPlacementChange(popperData) {
			this.tip = popperData.instance.popper;

			this._cleanTipClass();

			this.addAttachmentClass(this._getAttachment(popperData.placement));
		};

		_proto._fixTransition = function _fixTransition() {
			let tip = this.getTipElement();
			let initConfigAnimation = this.config.animation;

			if (tip.getAttribute('x-placement') !== null) {
				return;
			}

			$(tip).removeClass(CLASS_NAME_FADE$2);
			this.config.animation = false;
			this.hide();
			this.show();
			this.config.animation = initConfigAnimation;
		}
			;

		Tooltip._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$6);

				let _config = typeof config === 'object' && config;

				if (!data && /dispose|hide/.test(config)) {
					return;
				}

				if (!data) {
					data = new Tooltip(this, _config);
					$(this).data(DATA_KEY$6, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config]();
				}
			});
		};

		_createClass(Tooltip, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$6;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$4;
			}
		}, {
			key: 'NAME',
			get: function get() {
				return NAME$6;
			}
		}, {
			key: 'DATA_KEY',
			get: function get() {
				return DATA_KEY$6;
			}
		}, {
			key: 'Event',
			get: function get() {
				return Event;
			}
		}, {
			key: 'EVENT_KEY',
			get: function get() {
				return EVENT_KEY$6;
			}
		}, {
			key: 'DefaultType',
			get: function get() {
				return DefaultType$4;
			}
		}]);

		return Tooltip;
	}();

	$.fn[NAME$6] = Tooltip._jQueryInterface;
	$.fn[NAME$6].Constructor = Tooltip;

	$.fn[NAME$6].noConflict = function () {
		$.fn[NAME$6] = JQUERY_NO_CONFLICT$6;
		return Tooltip._jQueryInterface;
	};

	let NAME$7 = 'popover';
	let VERSION$7 = '4.5.0';
	let DATA_KEY$7 = 'bs.popover';
	let EVENT_KEY$7 = '.' + DATA_KEY$7;
	let JQUERY_NO_CONFLICT$7 = $.fn[NAME$7];
	let CLASS_PREFIX$1 = 'bs-popover';
	let BSCLS_PREFIX_REGEX$1 = new RegExp("(^|\\s)" + CLASS_PREFIX$1 + "\\S+", 'g');

	let Default$5 = _objectSpread2(_objectSpread2({}, Tooltip.Default), {}, {
		placement: 'right',
		trigger: 'click',
		content: '',
		template: '<div class="popover" role="tooltip">' + '<div class="arrow"></div>' + '<h3 class="popover-header"></h3>' + '<div class="popover-body"></div></div>'
	});

	let DefaultType$5 = _objectSpread2(_objectSpread2({}, Tooltip.DefaultType), {}, {
		content: '(string|element|function)'
	});

	let CLASS_NAME_FADE$3 = 'fade';
	let CLASS_NAME_SHOW$5 = 'show';
	let SELECTOR_TITLE = '.popover-header';
	let SELECTOR_CONTENT = '.popover-body';
	let Event$1 = {
		HIDE: 'hide' + EVENT_KEY$7,
		HIDDEN: 'hidden' + EVENT_KEY$7,
		SHOW: 'show' + EVENT_KEY$7,
		SHOWN: 'shown' + EVENT_KEY$7,
		INSERTED: 'inserted' + EVENT_KEY$7,
		CLICK: 'click' + EVENT_KEY$7,
		FOCUSIN: 'focusin' + EVENT_KEY$7,
		FOCUSOUT: 'focusout' + EVENT_KEY$7,
		MOUSEENTER: 'mouseenter' + EVENT_KEY$7,
		MOUSELEAVE: 'mouseleave' + EVENT_KEY$7
	};

	let Popover = function (_Tooltip) {
		_inheritsLoose(Popover, _Tooltip);

		function Popover() {
			return _Tooltip.apply(this, arguments) || this;
		}

		let _proto = Popover.prototype;

		_proto.isWithContent = function isWithContent() {
			return this.getTitle() || this._getContent();
		};

		_proto.addAttachmentClass = function addAttachmentClass(attachment) {
			$(this.getTipElement()).addClass(CLASS_PREFIX$1 + '-' + attachment);
		};

		_proto.getTipElement = function getTipElement() {
			this.tip = this.tip || $(this.config.template)[0];
			return this.tip;
		};

		_proto.setContent = function setContent() {
			let $tip = $(this.getTipElement());

			this.setElementContent($tip.find(SELECTOR_TITLE), this.getTitle());

			let content = this._getContent();

			if (typeof content === 'function') {
				content = content.call(this.element);
			}

			this.setElementContent($tip.find(SELECTOR_CONTENT), content);
			$tip.removeClass(CLASS_NAME_FADE$3 + ' ' + CLASS_NAME_SHOW$5);
		}
			;

		_proto._getContent = function _getContent() {
			return this.element.getAttribute('data-content') || this.config.content;
		};

		_proto._cleanTipClass = function _cleanTipClass() {
			let $tip = $(this.getTipElement());
			let tabClass = $tip.attr('class').match(BSCLS_PREFIX_REGEX$1);

			if (tabClass !== null && tabClass.length > 0) {
				$tip.removeClass(tabClass.join(''));
			}
		}
			;

		Popover._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$7);

				let _config = typeof config === 'object' ? config : null;

				if (!data && /dispose|hide/.test(config)) {
					return;
				}

				if (!data) {
					data = new Popover(this, _config);
					$(this).data(DATA_KEY$7, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config]();
				}
			});
		};

		_createClass(Popover, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$7;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$5;
			}
		}, {
			key: 'NAME',
			get: function get() {
				return NAME$7;
			}
		}, {
			key: 'DATA_KEY',
			get: function get() {
				return DATA_KEY$7;
			}
		}, {
			key: 'Event',
			get: function get() {
				return Event$1;
			}
		}, {
			key: 'EVENT_KEY',
			get: function get() {
				return EVENT_KEY$7;
			}
		}, {
			key: 'DefaultType',
			get: function get() {
				return DefaultType$5;
			}
		}]);

		return Popover;
	}(Tooltip);

	$.fn[NAME$7] = Popover._jQueryInterface;
	$.fn[NAME$7].Constructor = Popover;

	$.fn[NAME$7].noConflict = function () {
		$.fn[NAME$7] = JQUERY_NO_CONFLICT$7;
		return Popover._jQueryInterface;
	};

	let NAME$8 = 'scrollspy';
	let VERSION$8 = '4.5.0';
	let DATA_KEY$8 = 'bs.scrollspy';
	let EVENT_KEY$8 = '.' + DATA_KEY$8;
	let DATA_API_KEY$6 = '.data-api';
	let JQUERY_NO_CONFLICT$8 = $.fn[NAME$8];
	let Default$6 = {
		offset: 10,
		method: 'auto',
		target: ''
	};
	let DefaultType$6 = {
		offset: 'number',
		method: 'string',
		target: '(string|element)'
	};
	let EVENT_ACTIVATE = 'activate' + EVENT_KEY$8;
	let EVENT_SCROLL = 'scroll' + EVENT_KEY$8;
	let EVENT_LOAD_DATA_API$2 = 'load' + EVENT_KEY$8 + DATA_API_KEY$6;
	let CLASS_NAME_DROPDOWN_ITEM = 'dropdown-item';
	let CLASS_NAME_ACTIVE$2 = 'active';
	let SELECTOR_DATA_SPY = '[data-spy="scroll"]';
	let SELECTOR_NAV_LIST_GROUP = '.nav, .list-group';
	let SELECTOR_NAV_LINKS = '.nav-link';
	let SELECTOR_NAV_ITEMS = '.nav-item';
	let SELECTOR_LIST_ITEMS = '.list-group-item';
	let SELECTOR_DROPDOWN = '.dropdown';
	let SELECTOR_DROPDOWN_ITEMS = '.dropdown-item';
	let SELECTOR_DROPDOWN_TOGGLE = '.dropdown-toggle';
	let METHOD_OFFSET = 'offset';
	let METHOD_POSITION = 'position';

	let ScrollSpy = function () {
		function ScrollSpy(element, config) {
			let _this = this;

			this._element = element;
			this._scrollElement = element.tagName === 'BODY' ? window : element;
			this._config = this._getConfig(config);
			this._selector = this._config.target + ' ' + SELECTOR_NAV_LINKS + ',' + (this._config.target + ' ' + SELECTOR_LIST_ITEMS + ',') + (this._config.target + ' ' + SELECTOR_DROPDOWN_ITEMS);
			this._offsets = [];
			this._targets = [];
			this._activeTarget = null;
			this._scrollHeight = 0;
			$(this._scrollElement).on(EVENT_SCROLL, function (event) {
				return _this._process(event);
			});
			this.refresh();

			this._process();
		}

		let _proto = ScrollSpy.prototype;

		_proto.refresh = function refresh() {
			let _this2 = this;

			let autoMethod = this._scrollElement === this._scrollElement.window ? METHOD_OFFSET : METHOD_POSITION;
			let offsetMethod = this._config.method === 'auto' ? autoMethod : this._config.method;
			let offsetBase = offsetMethod === METHOD_POSITION ? this._getScrollTop() : 0;
			this._offsets = [];
			this._targets = [];
			this._scrollHeight = this._getScrollHeight();
			let targets = [].slice.call(document.querySelectorAll(this._selector));
			targets.map(function (element) {
				let target;
				let targetSelector = Util.getSelectorFromElement(element);

				if (targetSelector) {
					target = document.querySelector(targetSelector);
				}

				if (target) {
					let targetBCR = target.getBoundingClientRect();

					if (targetBCR.width || targetBCR.height) {
						return [$(target)[offsetMethod]().top + offsetBase, targetSelector];
					}
				}

				return null;
			}).filter(function (item) {
				return item;
			}).sort(function (a, b) {
				return a[0] - b[0];
			}).forEach(function (item) {
				_this2._offsets.push(item[0]);

				_this2._targets.push(item[1]);
			});
		};

		_proto.dispose = function dispose() {
			$.removeData(this._element, DATA_KEY$8);
			$(this._scrollElement).off(EVENT_KEY$8);
			this._element = null;
			this._scrollElement = null;
			this._config = null;
			this._selector = null;
			this._offsets = null;
			this._targets = null;
			this._activeTarget = null;
			this._scrollHeight = null;
		}
			;

		_proto._getConfig = function _getConfig(config) {
			config = _objectSpread2(_objectSpread2({}, Default$6), typeof config === 'object' && config ? config : {});

			if (typeof config.target !== 'string' && Util.isElement(config.target)) {
				let id = $(config.target).attr('id');

				if (!id) {
					id = Util.getUID(NAME$8);
					$(config.target).attr('id', id);
				}

				config.target = '#' + id;
			}

			Util.typeCheckConfig(NAME$8, config, DefaultType$6);
			return config;
		};

		_proto._getScrollTop = function _getScrollTop() {
			return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop;
		};

		_proto._getScrollHeight = function _getScrollHeight() {
			return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
		};

		_proto._getOffsetHeight = function _getOffsetHeight() {
			return this._scrollElement === window ? window.innerHeight : this._scrollElement.getBoundingClientRect().height;
		};

		_proto._process = function _process() {
			let scrollTop = this._getScrollTop() + this._config.offset;

			let scrollHeight = this._getScrollHeight();

			let maxScroll = this._config.offset + scrollHeight - this._getOffsetHeight();

			if (this._scrollHeight !== scrollHeight) {
				this.refresh();
			}

			if (scrollTop >= maxScroll) {
				let target = this._targets[this._targets.length - 1];

				if (this._activeTarget !== target) {
					this._activate(target);
				}

				return;
			}

			if (this._activeTarget && scrollTop < this._offsets[0] && this._offsets[0] > 0) {
				this._activeTarget = null;

				this._clear();

				return;
			}

			for (let i = this._offsets.length; i--;) {
				let isActiveTarget = this._activeTarget !== this._targets[i] && scrollTop >= this._offsets[i] && (typeof this._offsets[i + 1] === 'undefined' || scrollTop < this._offsets[i + 1]);

				if (isActiveTarget) {
					this._activate(this._targets[i]);
				}
			}
		};

		_proto._activate = function _activate(target) {
			this._activeTarget = target;

			this._clear();

			let queries = this._selector.split(',').map(function (selector) {
				return selector + '[data-target="' + target + '"],' + selector + '[href="' + target + '"]';
			});

			let $link = $([].slice.call(document.querySelectorAll(queries.join(','))));

			if ($link.hasClass(CLASS_NAME_DROPDOWN_ITEM)) {
				$link.closest(SELECTOR_DROPDOWN).find(SELECTOR_DROPDOWN_TOGGLE).addClass(CLASS_NAME_ACTIVE$2);
				$link.addClass(CLASS_NAME_ACTIVE$2);
			} else {
				$link.addClass(CLASS_NAME_ACTIVE$2);

				$link.parents(SELECTOR_NAV_LIST_GROUP).prev(SELECTOR_NAV_LINKS + ', ' + SELECTOR_LIST_ITEMS).addClass(CLASS_NAME_ACTIVE$2);

				$link.parents(SELECTOR_NAV_LIST_GROUP).prev(SELECTOR_NAV_ITEMS).children(SELECTOR_NAV_LINKS).addClass(CLASS_NAME_ACTIVE$2);
			}

			$(this._scrollElement).trigger(EVENT_ACTIVATE, {
				relatedTarget: target
			});
		};

		_proto._clear = function _clear() {
			[].slice.call(document.querySelectorAll(this._selector)).filter(function (node) {
				return node.classList.contains(CLASS_NAME_ACTIVE$2);
			}).forEach(function (node) {
				return node.classList.remove(CLASS_NAME_ACTIVE$2);
			});
		}
			;

		ScrollSpy._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let data = $(this).data(DATA_KEY$8);

				let _config = typeof config === 'object' && config;

				if (!data) {
					data = new ScrollSpy(this, _config);
					$(this).data(DATA_KEY$8, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config]();
				}
			});
		};

		_createClass(ScrollSpy, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$8;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$6;
			}
		}]);

		return ScrollSpy;
	}();

	$(window).on(EVENT_LOAD_DATA_API$2, function () {
		let scrollSpys = [].slice.call(document.querySelectorAll(SELECTOR_DATA_SPY));
		let scrollSpysLength = scrollSpys.length;

		for (let i = scrollSpysLength; i--;) {
			let $spy = $(scrollSpys[i]);

			ScrollSpy._jQueryInterface.call($spy, $spy.data());
		}
	});

	$.fn[NAME$8] = ScrollSpy._jQueryInterface;
	$.fn[NAME$8].Constructor = ScrollSpy;

	$.fn[NAME$8].noConflict = function () {
		$.fn[NAME$8] = JQUERY_NO_CONFLICT$8;
		return ScrollSpy._jQueryInterface;
	};

	let NAME$9 = 'tab';
	let VERSION$9 = '4.5.0';
	let DATA_KEY$9 = 'bs.tab';
	let EVENT_KEY$9 = '.' + DATA_KEY$9;
	let DATA_API_KEY$7 = '.data-api';
	let JQUERY_NO_CONFLICT$9 = $.fn[NAME$9];
	let EVENT_HIDE$3 = 'hide' + EVENT_KEY$9;
	let EVENT_HIDDEN$3 = 'hidden' + EVENT_KEY$9;
	let EVENT_SHOW$3 = 'show' + EVENT_KEY$9;
	let EVENT_SHOWN$3 = 'shown' + EVENT_KEY$9;
	let EVENT_CLICK_DATA_API$6 = 'click' + EVENT_KEY$9 + DATA_API_KEY$7;
	let CLASS_NAME_DROPDOWN_MENU = 'dropdown-menu';
	let CLASS_NAME_ACTIVE$3 = 'active';
	let CLASS_NAME_DISABLED$1 = 'disabled';
	let CLASS_NAME_FADE$4 = 'fade';
	let CLASS_NAME_SHOW$6 = 'show';
	let SELECTOR_DROPDOWN$1 = '.dropdown';
	let SELECTOR_NAV_LIST_GROUP$1 = '.nav, .list-group';
	let SELECTOR_ACTIVE$2 = '.active';
	let SELECTOR_ACTIVE_UL = '> li > .active';
	let SELECTOR_DATA_TOGGLE$4 = '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]';
	let SELECTOR_DROPDOWN_TOGGLE$1 = '.dropdown-toggle';
	let SELECTOR_DROPDOWN_ACTIVE_CHILD = '> .dropdown-menu .active';

	let Tab = function () {
		function Tab(element) {
			this._element = element;
		}

		let _proto = Tab.prototype;

		_proto.show = function show() {
			let _this = this;

			if (this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && $(this._element).hasClass(CLASS_NAME_ACTIVE$3) || $(this._element).hasClass(CLASS_NAME_DISABLED$1)) {
				return;
			}

			let target;
			let previous;
			let listElement = $(this._element).closest(SELECTOR_NAV_LIST_GROUP$1)[0];
			let selector = Util.getSelectorFromElement(this._element);

			if (listElement) {
				let itemSelector = listElement.nodeName === 'UL' || listElement.nodeName === 'OL' ? SELECTOR_ACTIVE_UL : SELECTOR_ACTIVE$2;
				previous = $.makeArray($(listElement).find(itemSelector));
				previous = previous[previous.length - 1];
			}

			let hideEvent = $.Event(EVENT_HIDE$3, {
				relatedTarget: this._element
			});
			let showEvent = $.Event(EVENT_SHOW$3, {
				relatedTarget: previous
			});

			if (previous) {
				$(previous).trigger(hideEvent);
			}

			$(this._element).trigger(showEvent);

			if (showEvent.isDefaultPrevented() || hideEvent.isDefaultPrevented()) {
				return;
			}

			if (selector) {
				target = document.querySelector(selector);
			}

			this._activate(this._element, listElement);

			let complete = function complete() {
				let hiddenEvent = $.Event(EVENT_HIDDEN$3, {
					relatedTarget: _this._element
				});
				let shownEvent = $.Event(EVENT_SHOWN$3, {
					relatedTarget: previous
				});
				$(previous).trigger(hiddenEvent);
				$(_this._element).trigger(shownEvent);
			};

			if (target) {
				this._activate(target, target.parentNode, complete);
			} else {
				complete();
			}
		};

		_proto.dispose = function dispose() {
			$.removeData(this._element, DATA_KEY$9);
			this._element = null;
		}
			;

		_proto._activate = function _activate(element, container, callback) {
			let _this2 = this;

			let activeElements = container && (container.nodeName === 'UL' || container.nodeName === 'OL') ? $(container).find(SELECTOR_ACTIVE_UL) : $(container).children(SELECTOR_ACTIVE$2);
			let active = activeElements[0];
			let isTransitioning = callback && active && $(active).hasClass(CLASS_NAME_FADE$4);

			let complete = function complete() {
				return _this2._transitionComplete(element, active, callback);
			};

			if (active && isTransitioning) {
				let transitionDuration = Util.getTransitionDurationFromElement(active);
				$(active).removeClass(CLASS_NAME_SHOW$6).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
			} else {
				complete();
			}
		};

		_proto._transitionComplete = function _transitionComplete(element, active, callback) {
			if (active) {
				$(active).removeClass(CLASS_NAME_ACTIVE$3);
				let dropdownChild = $(active.parentNode).find(SELECTOR_DROPDOWN_ACTIVE_CHILD)[0];

				if (dropdownChild) {
					$(dropdownChild).removeClass(CLASS_NAME_ACTIVE$3);
				}

				if (active.getAttribute('role') === 'tab') {
					active.setAttribute('aria-selected', false);
				}
			}

			$(element).addClass(CLASS_NAME_ACTIVE$3);

			if (element.getAttribute('role') === 'tab') {
				element.setAttribute('aria-selected', true);
			}

			Util.reflow(element);

			if (element.classList.contains(CLASS_NAME_FADE$4)) {
				element.classList.add(CLASS_NAME_SHOW$6);
			}

			if (element.parentNode && $(element.parentNode).hasClass(CLASS_NAME_DROPDOWN_MENU)) {
				let dropdownElement = $(element).closest(SELECTOR_DROPDOWN$1)[0];

				if (dropdownElement) {
					let dropdownToggleList = [].slice.call(dropdownElement.querySelectorAll(SELECTOR_DROPDOWN_TOGGLE$1));
					$(dropdownToggleList).addClass(CLASS_NAME_ACTIVE$3);
				}

				element.setAttribute('aria-expanded', true);
			}

			if (callback) {
				callback();
			}
		}
			;

		Tab._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let $this = $(this);
				let data = $this.data(DATA_KEY$9);

				if (!data) {
					data = new Tab(this);
					$this.data(DATA_KEY$9, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config]();
				}
			});
		};

		_createClass(Tab, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$9;
			}
		}]);

		return Tab;
	}();

	$(document).on(EVENT_CLICK_DATA_API$6, SELECTOR_DATA_TOGGLE$4, function (event) {
		event.preventDefault();

		Tab._jQueryInterface.call($(this), 'show');
	});

	$.fn[NAME$9] = Tab._jQueryInterface;
	$.fn[NAME$9].Constructor = Tab;

	$.fn[NAME$9].noConflict = function () {
		$.fn[NAME$9] = JQUERY_NO_CONFLICT$9;
		return Tab._jQueryInterface;
	};

	let NAME$a = 'toast';
	let VERSION$a = '4.5.0';
	let DATA_KEY$a = 'bs.toast';
	let EVENT_KEY$a = '.' + DATA_KEY$a;
	let JQUERY_NO_CONFLICT$a = $.fn[NAME$a];
	let EVENT_CLICK_DISMISS$1 = 'click.dismiss' + EVENT_KEY$a;
	let EVENT_HIDE$4 = 'hide' + EVENT_KEY$a;
	let EVENT_HIDDEN$4 = 'hidden' + EVENT_KEY$a;
	let EVENT_SHOW$4 = 'show' + EVENT_KEY$a;
	let EVENT_SHOWN$4 = 'shown' + EVENT_KEY$a;
	let CLASS_NAME_FADE$5 = 'fade';
	let CLASS_NAME_HIDE = 'hide';
	let CLASS_NAME_SHOW$7 = 'show';
	let CLASS_NAME_SHOWING = 'showing';
	let DefaultType$7 = {
		animation: 'boolean',
		autohide: 'boolean',
		delay: 'number'
	};
	let Default$7 = {
		animation: true,
		autohide: true,
		delay: 500
	};
	let SELECTOR_DATA_DISMISS$1 = '[data-dismiss="toast"]';

	let Toast = function () {
		function Toast(element, config) {
			this._element = element;
			this._config = this._getConfig(config);
			this._timeout = null;

			this._setListeners();
		}

		let _proto = Toast.prototype;

		_proto.show = function show() {
			let _this = this;

			let showEvent = $.Event(EVENT_SHOW$4);
			$(this._element).trigger(showEvent);

			if (showEvent.isDefaultPrevented()) {
				return;
			}

			if (this._config.animation) {
				this._element.classList.add(CLASS_NAME_FADE$5);
			}

			let complete = function complete() {
				_this._element.classList.remove(CLASS_NAME_SHOWING);

				_this._element.classList.add(CLASS_NAME_SHOW$7);

				$(_this._element).trigger(EVENT_SHOWN$4);

				if (_this._config.autohide) {
					_this._timeout = setTimeout(function () {
						_this.hide();
					}, _this._config.delay);
				}
			};

			this._element.classList.remove(CLASS_NAME_HIDE);

			Util.reflow(this._element);

			this._element.classList.add(CLASS_NAME_SHOWING);

			if (this._config.animation) {
				let transitionDuration = Util.getTransitionDurationFromElement(this._element);
				$(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
			} else {
				complete();
			}
		};

		_proto.hide = function hide() {
			if (!this._element.classList.contains(CLASS_NAME_SHOW$7)) {
				return;
			}

			let hideEvent = $.Event(EVENT_HIDE$4);
			$(this._element).trigger(hideEvent);

			if (hideEvent.isDefaultPrevented()) {
				return;
			}

			this._close();
		};

		_proto.dispose = function dispose() {
			clearTimeout(this._timeout);
			this._timeout = null;

			if (this._element.classList.contains(CLASS_NAME_SHOW$7)) {
				this._element.classList.remove(CLASS_NAME_SHOW$7);
			}

			$(this._element).off(EVENT_CLICK_DISMISS$1);
			$.removeData(this._element, DATA_KEY$a);
			this._element = null;
			this._config = null;
		}
			;

		_proto._getConfig = function _getConfig(config) {
			config = _objectSpread2(_objectSpread2(_objectSpread2({}, Default$7), $(this._element).data()), typeof config === 'object' && config ? config : {});
			Util.typeCheckConfig(NAME$a, config, this.constructor.DefaultType);
			return config;
		};

		_proto._setListeners = function _setListeners() {
			let _this2 = this;

			$(this._element).on(EVENT_CLICK_DISMISS$1, SELECTOR_DATA_DISMISS$1, function () {
				return _this2.hide();
			});
		};

		_proto._close = function _close() {
			let _this3 = this;

			let complete = function complete() {
				_this3._element.classList.add(CLASS_NAME_HIDE);

				$(_this3._element).trigger(EVENT_HIDDEN$4);
			};

			this._element.classList.remove(CLASS_NAME_SHOW$7);

			if (this._config.animation) {
				let transitionDuration = Util.getTransitionDurationFromElement(this._element);
				$(this._element).one(Util.TRANSITION_END, complete).emulateTransitionEnd(transitionDuration);
			} else {
				complete();
			}
		}
			;

		Toast._jQueryInterface = function _jQueryInterface(config) {
			return this.each(function () {
				let $element = $(this);
				let data = $element.data(DATA_KEY$a);

				let _config = typeof config === 'object' && config;

				if (!data) {
					data = new Toast(this, _config);
					$element.data(DATA_KEY$a, data);
				}

				if (typeof config === 'string') {
					if (typeof data[config] === 'undefined') {
						throw new TypeError('No method named "' + config + '"');
					}

					data[config](this);
				}
			});
		};

		_createClass(Toast, null, [{
			key: 'VERSION',
			get: function get() {
				return VERSION$a;
			}
		}, {
			key: 'DefaultType',
			get: function get() {
				return DefaultType$7;
			}
		}, {
			key: 'Default',
			get: function get() {
				return Default$7;
			}
		}]);

		return Toast;
	}();

	$.fn[NAME$a] = Toast._jQueryInterface;
	$.fn[NAME$a].Constructor = Toast;

	$.fn[NAME$a].noConflict = function () {
		$.fn[NAME$a] = JQUERY_NO_CONFLICT$a;
		return Toast._jQueryInterface;
	};

	exports.Alert = Alert;
	exports.Button = Button;
	exports.Carousel = Carousel;
	exports.Collapse = Collapse;
	exports.Dropdown = Dropdown;
	exports.Modal = Modal;
	exports.Popover = Popover;
	exports.Scrollspy = ScrollSpy;
	exports.Tab = Tab;
	exports.Toast = Toast;
	exports.Tooltip = Tooltip;
	exports.Util = Util;

	Object.defineProperty(exports, '__esModule', { value: true });

})));
