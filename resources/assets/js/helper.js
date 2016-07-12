'use strict';

var Xhr = require('./xhr');

//TODO: move into alert.js
function setAlert(message, classList) {
    var alertEl = document.getElementById('alert');
    alertEl.textContent = message;
    alertEl.classList.add(classList);

    if (alertEl.classList.contains('hidden')) {
        alertEl.classList.remove('hidden');
    }
}

/**
 * Set a button/link to disabled state
 * @param  {DOM Node} btnEl
 */
function disableButton(btnEl) {
    btnEl.setAttribute('disabled', 'true');
    btnEl.classList.add('btn--disabled');
}

/**
 * Set a button/link to enabled state
 * @param  {DOM Node} btnEl
 */
function enableButton(btnEl) {
    btnEl.removeAttribute('disabled');
    btnEl.classList.remove('btn--disabled');
}

/**
 * Element.matches polyfill for older browsers
 * @param  {DOM Node} elm      
 * @param  {String} selector
 * @return {[boolean]}
 */
function matches(elm, selector) {
    var matches = (elm.document || elm.ownerDocument).querySelectorAll(selector),
        i = matches.length;
    while (--i >= 0 && matches.item(i) !== elm) {}
    return i > -1;
}

/**
 * Helper function to send an ajax request
 * @param  {String} method - method to use (GET, POST, PATCH, DELETE)
 * @param  {String} path - URL to send the request to
 * @param  {Function} success - callback function on load success
 * @param  {Function} failure - callback function on load failure
 * @param  {Function} always - callback function to always trigger [optional]
 * @param  {JSON} data - data to send via Ajax [optional]
 */
function sendAjaxRequest(method, path, success, failure, always, data) {
    always = always || function() {};
    var xhr = new Xhr();

    if (isJson(data)) {
        xhr.open(method, path, true);
    } else {
        xhr.open(method, path);
    }
    
    xhr.onLoad(success, failure, always);

    if (typeof data !== 'undefined') {
        xhr.send(data);
    } else {
        xhr.send();
    }

    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
}

/**
 * Serialize form values
 * @param  {Node} form - form element to be serialized
 * @return {String}  serialized values
 */
function serialize(form) {
    if (!form || form.nodeName !== "FORM") {
        return;
    }
    var i, j, q = [];
    for (i = form.elements.length - 1; i >= 0; i = i - 1) {
        if (form.elements[i].name === "") {
            continue;
        }
        switch (form.elements[i].nodeName) {
        case 'INPUT':
            switch (form.elements[i].type) {
            case 'text':
            case 'hidden':
            case 'password':
            case 'button':
            case 'reset':
            case 'submit':
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                break;
            case 'checkbox':
            case 'radio':
                if (form.elements[i].checked) {
                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                }                       
                break;
            case 'file':
                break;
            }
            break;           
        case 'TEXTAREA':
            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
            break;
        case 'SELECT':
            switch (form.elements[i].type) {
            case 'select-one':
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                break;
            case 'select-multiple':
                for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                    if (form.elements[i].options[j].selected) {
                        q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                    }
                }
                break;
            }
            break;
        case 'BUTTON':
            switch (form.elements[i].type) {
            case 'reset':
            case 'submit':
            case 'button':
                q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                break;
            }
            break;
        }
    }
    return q.join("&");
}

/**
 * Get vendor prefix of user's browser
 */
function getVendorPrefix(prop) {
    prop = prop || '';
    var styles = window.getComputedStyle(document.documentElement, prop);
    var pre = (Array.prototype.slice
        .call(styles)
        .join('') 
        .match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
      )[1];
    var dom = ('WebKit|Moz|MS|O').match(new RegExp('(' + pre + ')', 'i'))[1];

    return {
      dom: dom,
      lowercase: pre,
      css: '-' + pre + '-',
      js: pre[0].toUpperCase() + pre.substr(1)
    };
}

/**
 * Determines the right css property value to use. (i.e: transform vs -webkit-transform)
 * @return {String} the right CSS property based on the user's browser
 */
function getPropertyValue(property) {
    var style = window.getComputedStyle(document.documentElement);

    if (!style.getPropertyValue(property)) {
        return getVendorPrefix(property).css + property;
    }

    return property;
}

/**
 * Determines which transition event to use to listen for css transition end event (i.e: transitionend vs webkitTransitionEnd)
 * @return {String} The right transition end event (with or wihout vendor prefixes)
 */
function whichTransitionEvent(){
    var t;
    var el = document.createElement('fakeelement');
    var transitions = {
      'transition':'transitionend',
      'OTransition':'oTransitionEnd',
      'MozTransition':'transitionend',
      'WebkitTransition':'webkitTransitionEnd'
    };

    for(t in transitions){
        if( el.style[t] !== undefined ){
            return transitions[t];
        }
    }
}

/**
 * Get the transform values of an element.
 * Adapted from jQuery solution at http://stackoverflow.com/questions/7982053/get-translate3d-values-of-a-div
 * @param  {Node} el - HTML Element Node
 * @return {Array} - an array containing the [x,y,z,1] values of the transform
 */
function getTransform(el) {
    var transform = window.getComputedStyle(el, null).getPropertyValue('-webkit-transform');
    var results = transform.match(/matrix(?:(3d)\(-{0,1}\d+(?:, -{0,1}\d+)*(?:, (-{0,1}\d+))(?:, (-{0,1}\d+))(?:, (-{0,1}\d+)), -{0,1}\d+\)|\(-{0,1}\d+(?:, -{0,1}\d+)*(?:, (-{0,1}\d+))(?:, (-{0,1}\d+))\))/);

    if(!results) return [0, 0, 0];
    if(results[1] == '3d') return results.slice(2,5);

    results.push(0);
    return results.slice(5, 8); // returns the [X,Y,Z,1] values
}

module.exports = {
    setAlert: setAlert,
    disableButton: disableButton,
    enableButton: enableButton,
    matches: matches,
    sendAjaxRequest: sendAjaxRequest,
    serialize: serialize,
    getVendorPrefix: getVendorPrefix,
    getPropertyValue: getPropertyValue,
    whichTransitionEvent: whichTransitionEvent,
    getTransform: getTransform
};