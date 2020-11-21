/**
 * Copied from Github
 * jQuery Extensions
 * @param {object} $
 * @returns {mixed}

 */

(function ($) {
    var GEXT = {
        clearForm: function () {
            return this.each(function () {
                var type = this.type;
                var tag = this.tagName.toLowerCase();
                if (tag === 'form') {
                    return $(':input', this)
                        .clearForm();
                }
                if (type === 'text' || type === 'password' || tag === 'textarea') {
                    this.value = '';
                } else if (type === 'checkbox' || type === 'radio') {
                    this.checked = false;
                } else if (tag === 'select') {
                    this.selectedIndex = -1;
                }
            });
        },
        getPath: function () {
            var paths = [];

            this.each(function (index, element) {
                var path;
                var $node = $(element);

                while ($node.length) {
                    var realNode = $node.get(0);
                    var name = realNode.localName;
                    if (!name) {
                        break;
                    }

                    name = name.toLowerCase();
                    var parent = $node.parent();
                    var sameTagSiblings = parent.children(name);

                    if (sameTagSiblings.length > 1) {
                        var allSiblings = parent.children();
                        var i = allSiblings.index(realNode) + 1;
                        if (i > 0) {
                            name += ':nth-child(' + i + ')';
                        }
                    }

                    path = name + (path ? ' > ' + path : '');
                    $node = parent;
                }

                paths.push(path);
            });

            return paths.join(',');
        },
        scrollSpyX: function (a, b, c) {
            var jQuery = $;
            var f;
            var s = $(this);
            if (arguments.length === 0) {
                s.scrollSpy();
            }
            if (arguments.length === 1 && (typeof a === 'string' || a instanceof jQuery)) {
                s = a = typeof a === 'string' ? $(a) : a;
                $.scrollSpy(a);
            } else if (arguments.length === 1 && typeof a === 'function') {
                f = a;
                s.scrollSpy();
            } else if (arguments.length === 2 && (typeof a === 'string' || a instanceof jQuery) && typeof b === 'object') {
                s = a = typeof a === 'string' ? $(a) : a;
                $.scrollSpy(a, b);
            } else if (arguments.length === 2 && (typeof a === 'string' || a instanceof jQuery) && typeof b === 'function') {
                f = b;
                s = a = typeof a === 'string' ? $(a) : a;
                $.scrollSpy(a);
            } else if (arguments.length === 2 && typeof a === 'object' && typeof b === 'function') {
                f = b;
                s.scrollSpy(a);
            } else if (arguments.length === 3 && (typeof a === 'string' || a instanceof jQuery) && typeof b === 'object' && typeof c === 'function') {
                f = c;
                s = a = typeof a === 'string' ? $(a) : a;
                $.scrollSpy(a, b);
            } else {
                console.error('Invalid argument set');
            }

            window.ScrollSpyX = {};
            var visible = [];
            if (typeof f !== 'undefined') {
                s.on('scrollSpy:enter', function () {
                    visible = $.grep(visible, function (value) {
                        return value.is(':visible');
                    });
                    visible = visible.sort(function (a, b) {
                        return b.offset().top - a.offset().top;
                    });

                    var $this = $(this);
                    if (visible[0]) {
                        if ($this.data('scrollSpy:id') < visible[0].data('scrollSpy:id')) {
                            visible.unshift($(this));
                        } else {
                            visible.push($(this));
                        }
                    } else {
                        visible.push($(this));
                    }

                    window.ScrollSpyX.visible = visible;
                    f(visible[0], 'enter');
                });
                s.on('scrollSpy:exit', function () {
                    visible = $.grep(visible, function (value) {
                        return value.is(':visible');
                    });
                    visible = visible.sort(function (a, b) {
                        return b.offset().top - a.offset().top;
                    });

                    if (visible[0]) {
                        var $this = $(this);
                        visible = $.grep(visible, function (value) {
                            return value.attr('id') !== $this.attr('id');
                        });
                        visible = visible.sort(function (a, b) {
                            return b.offset().top - a.offset().top;
                        });
                        if (visible[0]) { // Check if empty
                            f(visible[0], 'exit');
                        }
                    }
                });
            }
        }
    };

    var BEXT = {
        isNumber: function (n) {
            return !isNaN(n);
        },
        isInt: function (n) {
            return $.isNumber(n) && n % 1 === 0;
        },
        isFloat: function (n) {
            return $.isNumber(n) && n % 1 !== 0;
        },
        isOdd: function (n) {
            return n % 2 !== 0;
        },
        isEven: function (n) {
            return n % 2 === 0;
        },
        isInArray: function (value, array) {
            return (array.indexOf(value) > -1);
        },
        isJsonString: function (str) {
            try {
                $.parseJSON(str);
                return true;
            } catch (e) {
                return false;
            }
        },
        safeParseJSON: function (str) {
            var $object;
            if (typeof str === 'string') {
                try {
                    $object = $.parseJSON(str);
                    return $object;
                } catch (e) {
                    return null;
                }
            } else if (typeof str === 'object') {
                return str;
            }
        },
        jsonDecode: function (jsonString) {
            return $.safeParseJSON(jsonString);
        },
        isInPageAnchor: function (baseUrl, link) {
            return (new RegExp(baseUrl)).test(link) && (new RegExp('#')).test(link);
        },

        getAnchor: function (link) {
            var urlParts = link.toString().split('#');
            return urlParts[1];
        },
        getUrlBase: function (link) {
            link = link.split('?')[0];
            var a = link.split('/');
            a = a.splice(0, a.length - 1);
            return a.join('/');
        },
        scrollTo: function (name, semaphore, duration) {
            var $this = this;
            $this.scroll = function (animateOptions) {
                if (target.length) {
                    var defaults = {
                        duration: duration,
                        queue: true,
                        easing: 'easeOutCubic'
                    };
                    if (typeof animateOptions === 'object') {
                        defaults = $.extend({}, defaults, animateOptions);
                    }

                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, defaults);
                }
            };

            var target = $('#' + name);
            target = target.length ? target : $('[name=' + name + ']');
            duration = typeof duration === 'undefined' ? 400 : duration;
            if (semaphore instanceof Semaphore) {
                semaphore.lock('func.scrollTo');
                $this.scroll({
                    complete: function () {
                        window.location.href = '#' + name;
                        semaphore.unlock('func.scrollTo');
                    }
                });
            } else {
                $this.scroll();
            }
        },
        range: function (min, max) {
            if (arguments.length === 1) {
                max = min;
                min = 0;
            }

            var a = [];
            for (var i = min; i < max + 1; i++) {
                a.push(i);
            }
            return a;
        },
        getKeys: function (obj) {
            var keys = [];
            for (var key in obj) {
                keys.push(key);
            }
            return keys;
        },
        uniqueArray: function (a) {
            var seen = {};
            var out = [];
            var len = a.length;
            var j = 0;
            for (var i = 0; i < len; i++) {
                var item = a[i];
                if (seen[item] !== 1) {
                    seen[item] = 1;
                    out[j++] = item;
                }
            }
            return out;
        }
    };

    var FNEXT = {
        /**
         * Input field autofill
         * @param {function} callable Expects parameters id, text, img, extra<br/>
         * id: ID of clicked item.<br/>
         * text: Display text of clicked item<br/>
         * img: Display image of clicked item<br/>
         * extra: An object containing extra data passed<br/>
         *
         * @param {string} scope Scope to search
         * @param {boolean} allowEnterKey Allow enter key to submit
         * @returns {object}
         */
        autoComplete: function (callable, scope, allowEnterKey, ignore) {
            window.is_timer = undefined;
            if (typeof allowEnterKey === 'undefined') {
                allowEnterKey = false;
            }
            if ($(this).is('input')) {
                //  No browser autocomplete, we have it covered
                $(this).attr('autocomplete', 'off');

                if (!allowEnterKey) { //Prevent enter key submit
                    $(this).bind('keypress keydown keyup', function (e) {
                        if (e.keyCode === 13) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }

                //  Check key pressed
                $(this).on('keyup', function (e) {
                    if (e.keyCode === 13 || e.keyCode === 27) {
                        //  Enter or esc key pressed
                        var select = $('div#is-poplist');
                        if (select.length) {
                            select.hide();
                        }
                        return false;
                    } else {
                        if (!isNaN(window.is_timer)) {
                            clearTimeout(window.is_timer);
                        }
                        var input = $(this).val();
                        if (input !== '') {
                            //  Save input element scope
                            var element = this;
                            window.is_timer = setTimeout(function () {
                                var url = window.HomeUrl + '/autocomplete';
                                if (typeof (ignore) !== 'object') {
                                    ignore = [];
                                }

                                iAjax({
                                    url: url,
                                    data: {'terms': input, 'scope': scope, 'ignore': ignore},
                                    onSuccess: function (data) {
                                        var select = $('div#is-poplist');
                                        //  Create popup if not exist
                                        if (!select.length) {
                                            select = $('body').append('<div id="is-poplist"></div>').find('div#is-poplist');
                                        }
                                        //  Position popup
                                        var window_width = $(window).width();
                                        var pop_width = $(select).width();
                                        if (window_width > pop_width) {
                                            var left = $(element).offset().left;
                                            var area = left + pop_width;
                                            //Place at the begining of input element
                                            //If offscreen, allow default (right = 0)
                                            select.css('left', area < window_width ? left : 'inherit');
                                        }
                                        //Push below input element
                                        var top = $(element).offset().top + $(element).height();
                                        select.css('top', top);

                                        //  Populate
                                        var items = '';
                                        for (var key in data) {
                                            if (data.hasOwnProperty(key)) {
                                                items += '<span class="pop-item" extra="'
                                                    + JSON.stringify(data[key]['extra'])
                                                    + '" val="' + key + '">';
                                                if (typeof (data[key]['image']) === 'string'
                                                    && data[key]['image'] !== '') {
                                                    items += '<img src="' + data[key]['image'] + '"/>';
                                                }
                                                items += data[key]['text'];
                                                items += '</span>';
                                            }
                                        }
                                        select.html(items);
                                        $(select.children()).on('click', function () {
                                            if (typeof callable === 'function') {
                                                callable($(this).attr('val'), $(this).text(), $('img', this).attr('src'), JSON.parse($(this).attr('extra')));
                                            }
                                            select.hide();
                                        });

                                        $('body').on('click', function () {
                                            select.hide();
                                        });

                                        select.show();
                                    }
                                });
                            }, 500);
                        } else {
                            $('div#is-poplist').hide();
                        }
                    }
                });
            }
            return this;
        }
    };

    $.extend(BEXT);
    $.extend(GEXT);
    $.fn.extend(GEXT);
    $.fn.extend(FNEXT);
}(jQuery));

function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

function ajaxUsingBtnURL(btn, callable, data) {
    $(btn).prop('disabled', true);
    showPageLoader();
    if (typeof data === 'undefined') {
        data = {};
    }

    iAjax({
        url: $(btn).data('url') ? $(btn).data('url') : $(btn).attr('href'),
        data: data,
        onSuccess: function (data) {
            if (data.status) {
                callable(data);
            } else {
                showAlertModal({title: 'Oops!', text: data.message, type: 'error'});
            }
        },
        onFailure: function () {
            showAlertModal({
                title: 'Oops! Something went wrong',
                text: 'We could not process this request at the moment, please try again.'
                , type: 'error'
            });
        },
        onComplete: function () {
            hidePageLoader();
            $(btn).prop('disabled', false);
        }
    });
}


function br2nl(str) {
    return str.replace(/<br\s*\/?>/mg, "\n");
}

function nl2br(str) {
    return str.replace(/\n/g, "<br/>");
}


function minimize(ch_count) {
    var parent = $('.minimize');
    var max = typeof (ch_count) === 'undefined' ? 450 : ch_count;
    parent.each(function () {
        var t = $(this).html();
        var dmax = $(this).attr('data-minimize');
        max = dmax ? dmax : max;
        if (t.length < max)
            return;

        //If it truncates any of the br markups, adjust max
        var firstCut = t.slice(0, max);
        var offset = 0;
        for (var i = firstCut.length - 1; i >= 0; i--) {
            if (firstCut[i] !== '<' && firstCut[i] !== 'b' &&
                firstCut[i] !== 'r' && firstCut[i] !== '/') {
                break;
            }
            ++offset;
        }
        max -= offset;

        $(this).html(
            t.slice(0, max) + '<span>... </span><a href="#" class="more">read more</a>' +
            '<span style="display:none;">' + t.slice(max, t.length) + ' <a href="#" class="less">minimize</a></span>'
        );
    });

    $('a.more', parent).click(function (event) {
        event.preventDefault();
        $(this).hide().prev().hide();
        $(this).next().show();
    });

    $('a.less', parent).click(function (event) {
        event.preventDefault();
        $(this).parent().hide().prev().show().prev().show();
    });
}

function isSmallScreen() {
    return $(window).width() <= 600;
}

function isMediumScreen() {
    return $(window).width() > 600 && $(window).width() < 1024;
}

function isLargeScreen() {
    return $(window).width() >= 1024;
}


function notify(pane, response, style) {
    //Process message
    if (typeof (response['message']) !== 'undefined') {
        var message = response['message'];
        if (message instanceof Array) {
            message = message.join('<br/>');
        }
    } else {
        message = toString(response);
    }

    if (!pane || !pane.length) {
        //If no pane is set
        toast(message, 4000);
    } else {
        var handle = pane.prop('data-timer');
        if ($.isInt(handle)) {
            clearTimeout(handle);
        }

        //Remove *-text classes
        pane.removeClass(function (index, css) {
            return (css.match(/(^|\s)\w+-text/g) || []).join(' ');
        });
        pane.html(message);
        if (typeof (response['message']) !== 'undefined') {
            pane.addClass((typeof (response['mode']) !== 'undefined') ?
                response.mode + '-text' :
                (response['status'] === true ? 'green-text' : 'red-text'));
        } else {
            pane.addClass((typeof style === 'undefined') ? 'orange-text' : style);
        }
        pane.show();
        pane.prop('data-timer', setTimeout(function () {
            pane.hide();
        }, 15000));
    }
}

/**
 * Handle display of http errors and response
 * @param {XHR} xhr
 * @param {type} form
 * @param {type} notifyElement
 * @returns {undefined}
 */
function handleHttpErrors(xhr, form, notifyElement) {
    notifyElement = vd(notifyElement, '.notify');
    //Wrap in JQuery
    notifyElement = form ? $(notifyElement, form) : $(notifyElement);

    if (xhr.status === 422) {
        if (typeof (xhr.responseJSON) !== 'object') {
            notify(notifyElement, {'status': false, 'message': xhr.responseJSON});
        } else {
            handle422ErrorObject(form, xhr.responseJSON, notifyElement);
        }
    } else if (xhr.status >= 400 && xhr.status < 500) {
        notify(notifyElement, {'status': false, 'message': xhr.responseJSON});
    } else if (xhr.status >= 500 && xhr.status < 600) {
        notify(notifyElement, {'status': false, 'message': 'Something snapped, please try again shortly.'});
    } else if ('status' in xhr) {
        notify(notifyElement, xhr);
    } else {
        //Fallback
        notify(notifyElement, {'status': false, 'message': 'Request failed'});
    }

}

function handle422ErrorObject(form, response, element) {
    $(form).find('.invalid').removeClass('invalid');
    var textArr = [];
    for (var field in response['errors']) {
        if (field in response['errors']) {
            $(form).find('[name="' + field + '"]').addClass('invalid');
            var data = $(response['errors']).prop(field);
            if (!!data && data.constructor === Array) {
                textArr.push(data.join("<br/>"));
            } else {
                textArr.push(data);
            }
        }
    }
    var notification = {
        'message': textArr.join('<br/>'),
        'status': false
    };
    notify(element, notification);
}

/**
 * Value or default
 * @param {type} value
 * @param {type} defaultValue
 * @returns {unresolved}
 */
function vd(value, defaultValue) {
    return typeof (value) !== 'undefined' ? value : defaultValue;
}


function initFormTableLists() {
    var manageListForm = $('form.manageList');
    var NP = $('#notify');
    var callbefore;
    var callafter;

    $(":submit", manageListForm).click(function () {
        $("form").data("submit-action", this.value);
        callbefore = $(this).attr('callbefore');
        callafter = $(this).attr('callafter');
    });

    $(".toggle-btn", manageListForm).click(function () {
        var checkBoxes = $($(this).attr('data-toggle'));
        checkBoxes.prop("checked", $(this).prop("checked"));
    });

    manageListForm.submit(function (e) {
        e.preventDefault();
        $('input[name=action]', manageListForm).val($(this).data("submit-action"));
        var next = function () {
            $('button[type=submit]', manageListForm).attr('disabled', true);
            showPageLoader();
            iAjax({
                url: manageListForm.attr('action'),
                method: "POST",
                data: manageListForm.serialize(),
                onSuccess: function (response) {
                    notify(NP, response);
                    if (response.status) {
                        if (typeof (window[callafter]) === 'function') {
                            window[callafter](response);
                        } else {
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                },
                onFailure: function (xhr) {
                    handleHttpErrors(xhr, manageListForm);
                },
                onComplete: function () {
                    hidePageLoader();
                    $('button[type=submit]', manageListForm).removeAttr('disabled');
                }
            });
        };
        var proceed;
        if (typeof (window[callbefore]) === 'function') {
            proceed = window[callbefore](this, next);
        } else {
            proceed = confirm('Are you sure?');
            if (proceed) {
                next();
            }
        }
    });
}


/**
 * Make ajax request
 * @param {object} settings This should contain the regular JQuery.ajax settings
 * and optionally <code>onSuccess</code>, <code>onFailure</code> and <code>onComplete</code>
 * closures which are aliases for jqXHR's <code>done</code>, <code>fail</code> and
 * <code>always</code> functions respectively.<br/><br/>
 * <b>Extra configurations</b><br/>
 * iAjax takes extra configurations. Add <code>extraConfig</code> object to the settings.<br/>
 * <code>extraConfig</code> properties include:<br/>
 * <b>retry</b> - Set true to resend request if fails due to connectivity, else false.<br/>
 * Note: By default, onComplete will be called after the last trial, to change this,
 * set <code>extraConfig.completeAfterRetry</code> to false.<br/>
 * <b>trials</b> - Maximum number of trials. Set value to 0 for infinite trials<br/>
 * <b>retryInterval</b> - Delay before each retry<br/><br/>
 * <b>Default Values for iAjax</b><br/>
 * <code>dataType: 'json'</code><br/>
 * <code>extraConfig: {retry: true,trials: 1,retryInterval: 0,completeAfterRetry: true}</code><br/>
 * @example <code>
 * var jqXHR = iAjax({</span><br/>
 * &nbsp;url: 'http://example.com',<br/>
 * &nbsp;data: {p: 'param'},<br/>
 * &nbsp;extraConfig:{<br/>
 * &nbsp;&nbsp;retry: true,<br/>
 * &nbsp;&nbsp;trials: 2<br/>
 * &nbsp;},<br/>
 * &nbsp;onSuccess: function (data) {<br/>
 * &nbsp;&nbsp;//success<br/>
 * &nbsp;},<br/>
 * &nbsp;onComplete: function () {<br/>
 * &nbsp;&nbsp;//Request complete<br/>
 * &nbsp;},<br/>
 * &nbsp;onFailure: function(){<br/>
 * &nbsp;&nbsp;//Request failed<br/>
 * &nbsp;}<br/>
 *  });
 *</code>
 * @returns {jqXHR} Returns first jqXHR object created
 */
function iAjax(settings) {
    var ajaxSettings = {
        dataType: 'json',
        cache: true,
        headers: {
            'Cache-Control': 'max-age=200',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    };
    var extraConfig = {
        retry: true,
        trials: 1,
        retryInterval: 5000,
        completeAfterRetry: true,
        trialCount: 0
    };
    extraConfig = $.extend(extraConfig, settings['extraConfig']);

    //Merge settings
    Object.keys(settings).forEach(function (key) {
        if (key !== 'onSuccess'
            && key !== 'onFailure'
            && key !== 'onComplete'
            && key !== 'extraConfig') {
            var s = [];
            s[key] = settings[key];
            ajaxSettings = $.extend(ajaxSettings, s);
        }
    });

    var r = $.ajax(ajaxSettings);
    if (typeof (settings['onSuccess']) === 'function') {
        r.done(settings['onSuccess']);
    }
    if (typeof (settings['onComplete']) === 'function') {
        (function (completeAfterRetry) {
            r.always(function (jqXHR, status, statusText) {
                if (jqXHR['readyState'] !== 0 || !completeAfterRetry) {
                    settings['onComplete'](jqXHR, status, statusText);
                }
            });
        }(extraConfig.completeAfterRetry));
    }
    r.fail(function (response, status, statusText) {
        if (response['status'] === 401) {
            login(settings);
        } else if (response['status'] === 419) {
            toast('Page has expired or token error occurred, refreshing page...');
            //Reload after 3 secs
            setTimeout(function () {
                window.location.reload();
            }, 3000);
        } else {
            if (response['readyState'] == 0) {
                if (extraConfig['retry']) {
                    extraConfig['trialCount']++;
                    if (extraConfig['trialCount'] === extraConfig['trials']) {
                        extraConfig['retry'] = false;
                        extraConfig['completeAfterRetry'] = false;
                    }
                    //Repeat request
                    setTimeout(function () {
                        iAjax($.extend(settings, {extraConfig: extraConfig}));
                    }, extraConfig['retryInterval']);
                    return;
                } else {
                    toast('Connection error', 4000);
                }
            }

            if (typeof (settings['onFailure']) === 'function') {
                settings['onFailure'](response, status, statusText);
            }
        }

    });
    return r;
}

var lastToast;
var toastTimer;

function toast(message, time) {
    if (typeof (lastToast) !== 'undefined') {
        clearTimeout(toastTimer);
        lastToast.remove();
    }

    var toastContainer = $('#__toast');
    if (!toastContainer.length) {
        toastContainer = $('<div id="__toast">')
            .css({
                position: 'fixed',
                'text-align': isSmallScreen() ? 'center' : 'left',
                top: isSmallScreen() ? 'auto' : '100px',
                bottom: isSmallScreen() ? '0' : 'auto',
                'z-index': '2000',
                right: isSmallScreen() ? '0' : '50px'
            });
        $('body').append(toastContainer);
    }

    var lastToast = $('<div>')
        .addClass('black white-text animated slideInUp toast')
        .css({
            padding: '10px',
            'min-width': '100px',
            'margin-top': '5px',
            width: isSmallScreen() ? '100%' : 'auto',
            'border-radius': isSmallScreen() ? '0px' : '5px'
        })
        .html(message);
    toastContainer.append(lastToast);

    toastTimer = setTimeout(function () {
        lastToast.remove();
        lastToast = undefined;
    }, time ? time : 4000);
}

/**
 * Copied from http://stackoverflow.com/a/33928558/2836233
 *
 * Copies a string to the clipboard. Must be called from within an
 * event handler such as click. May return false if it failed, but
 * this is not always possible. Browser support for Chrome 43+,
 * Firefox 42+, Safari 10+, Edge and IE 10+.
 * IE: The clipboard feature may be disabled by an administrator. By
 * default a prompt is shown the first time the clipboard is
 * used (per session).
 * @param {type} text
 * @returns {undefined|Boolean}
 */

function copyToClipboard(text, notify) {
    var copy;
    if (window.clipboardData && window.clipboardData.setData) {
        // IE specific code path to prevent textarea being shown while dialog is visible.
        copy = clipboardData.setData("Text", text);

    } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        var textarea = document.createElement("textarea");
        textarea.textContent = text;
        textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
        document.body.appendChild(textarea);
        textarea.select();
        try {
            copy = document.execCommand("copy");  // Security exception may be thrown by some browsers.
        } catch (ex) {
            console.warn("Copy to clipboard failed.", ex);
            return false;
        } finally {
            document.body.removeChild(textarea);
        }
    }

    if (vd(notify, true)) {
        toast('copied', 3000);
    }
    return copy;
}

function fetchReceipts(btn, event) {
    event.preventDefault();
    ajaxUsingBtnURL(btn, function (data) {
        var html = '<div>';
        for (var img in data.images) {
            html += '<img width="100%" style="margin-top:5px" src="' + data.images[img] + '"/>';
        }
        html += '</div>';

        showAlertModal({text: html, type: ''});
    });
}

function ajaxUsingBtnURL(btn, callable, data) {
    $(btn).prop('disabled', true);
    showPageLoader();
    if (typeof data === 'undefined') {
        data = {};
    }

    iAjax({
        url: $(btn).attr('href'),
        data: data,
        onSuccess: function (data) {
            if (data.status) {
                callable(data);
            } else {
                showAlertModal({title: 'Oops!', text: data.message, type: 'error'});
            }
        },
        onFailure: function () {
            showAlertModal({
                title: 'Oops! Something went wrong',
                text: 'We could not process this request at the moment, please try again.'
                , type: 'error'
            });
        },
        onComplete: function () {
            hidePageLoader();
            $(btn).prop('disabled', false);
        }
    });
}

function ajaxUsingFormURL(form, callable, formData, options) {
    $('[type=submit]', form).prop('disabled', true);
    showPageLoader();

    //    Update data
    let data = null;
    if (typeof formData !== 'undefined') {
        data = $(form).serializeArray();
        for (var item in formData) {
            if (formData.hasOwnProperty(item)) {
                data.push({name: item, value: formData[item]});
            }
        }
    } else {
        data = $(form).serialize();
    }

    var method = $(form).attr('method');
    var setting = {
        url: $(form).attr('action'),
        data: data,
        method: method ? method : 'POST',
        onSuccess: function (data) {
            if (data.status) {
                if (typeof callable === 'function') {
                    callable(data);
                } else {
                    notify($('.notify'), data);
                    // showAlertModal({'title': 'Success', 'text': data.message, 'type': 'success'});
                }
            } else {
                notify($('.notify'), data);
                // showAlertModal({title: 'Oops!', text: data.message, type: 'error'});
            }
        },
        onFailure: function (xhr, status, statusText) {
            handleHttpErrors(xhr, form);
            // showAlertModal({
            //     title: 'Oops! Something went wrong',
            //     text: 'We could not process this request at the moment, please try again.'
            //     , type: 'error'
            // });
        },
        onComplete: function () {
            hidePageLoader();
            $('[type=submit]', form).prop('disabled', false);
        }
    };

    //Update settings
    if (typeof options !== 'undefined') {
        setting = $.extend(setting, options);
    }
    iAjax(setting);
}

function showAlertModal(value, text) {
    var modal = $('#alertModal');

    if (typeof (value) === 'object') {
        var config = {
            title: '',
            text: '',
            type: 'info',
            okText: 'Ok',
            cancelText: 'Cancel',
            showCancel: false,
            okAction: null,
            cancelAction: null
        };
        config = $.extend(config, value);

        if (config['type'] === '' || config['type'] === null) {
            modal.find('img').hide();
        } else {
            modal.find('img').show().attr('src', homepage + '/images/alert/' + config['type'] + '.png');
        }

        if (config['title'] === '') {
            modal.find('.modal-title').hide();
        } else {
            modal.find('.modal-title').show().html(config['title']);
        }

        modal.find('.modal-text').html(config['text']);

        if (config['showCancel']) {
            modal.find('.modal-footer [data-dismiss]')
                .text(config['cancelText']).show()
                .click(function (e) {
                    $(this).off('click');
                    modal.modal('hide');
                    if (typeof (config['cancelAction']) === 'function') {
                        setTimeout(config['cancelAction'], 1000);
                    }
                });
        } else {
            modal.find('.modal-footer [data-dismiss]').hide();
        }

        modal.find('.modal-footer .ok')
            .text(config['okText'])
            .click(function (e) {
                $(this).off('click');
                modal.modal('hide');
                if (typeof (config['okAction']) === 'function') {
                    setTimeout(config['okAction'], 1000);
                }
            });
    } else {
        if (value === '') {
            modal.find('.modal-header').hide();
        } else {
            modal.find('.modal-header').show();
            modal.find('.modal-title').text(value);
        }

        modal.find('.modal-text').text(text);
        modal.find('.modal-footer [data-dismiss]').hide();
        modal.find('.modal-footer .ok').text('Ok')
            .click(function (e) {
                modal.modal('hide');
            });
    }

    //Show modal
    modal.modal({backdrop: 'static', show: true, keyboard: false});
}

function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}

$('form.auto-handle').submit(function (e) {
    e.preventDefault();
    var form = $(this);
    var ajaxSettings = {
        url: form.attr('action'),
        method: form.attr('method') ? form.attr('method') : 'GET',
        onSuccess: function (xhr) {
            notify($('p.notify', form), xhr);
            if (xhr.status) {
                if (xhr.redirect) {
                    window.location = xhr.redirect;
                } else {
                    window.location.reload();
                }
            }
        },
        onFailure: function (xhr) {
            handleHttpErrors(xhr, $('p.notify', form));
        },
        onComplete: function () {
            hidePageLoader();
        }
    };

    if (form.attr('enctype') == 'multipart/form-data') {
        var data = new FormData(this);
        var xData = {
            data: data,
            contentType: false,
            processData: false
        };
    } else {
        var data = form.serialize();
        var xData = {data: data};
    }
    ajaxSettings = $.extend(ajaxSettings, xData);
    showPageLoader();
    iAjax(ajaxSettings);
});


function showPageLoader() {
    $('#page-loader, #page-loader #loader').show();
}

function hidePageLoader() {
    $('#page-loader, #page-loader #loader').hide();
}
