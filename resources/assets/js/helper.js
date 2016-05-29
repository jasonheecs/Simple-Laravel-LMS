'use strict';

var Xhr = require('./xhr');

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
    xhr.open(method, path);
    xhr.onLoad(success, failure, always);

    if (typeof data !== 'undefined') {
        xhr.send(data);
    } else {
        xhr.send();
    }
}

module.exports = {
    disableButton: disableButton,
    enableButton: enableButton,
    matches: matches,
    sendAjaxRequest: sendAjaxRequest
};