'use strict';

/**
 * Helper / Wrapper Object for XMLHttpRequests
 */
/* globals XMLHttpRequest */

function Xhr() {
    this.xhr = new XMLHttpRequest();
}

Xhr.prototype.setCSRF = function(token) {
    token = token || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    this.xhr.setRequestHeader('X-CSRF-TOKEN', token);
};

/**
 * Calls the open method of the XMLHttpRequest Object and also sets request headers common to this app.
 * @param  {String} method - method to use (GET, POST, PATCH, DELETE)
 * @param  {String} path - URL to send the request to
 * @param  {boolean} setCSRF - if true, set the CRSF token request header (default: true) [optional]
 */
Xhr.prototype.open = function(method, path, jsonPayload, setCSRF) {
    setCSRF = setCSRF || true;
    jsonPayload = jsonPayload || false;

    this.xhr.open(method, path);
    this.xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    if (jsonPayload) {
        this.xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    } else {
        this.xhr.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
        this.xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    }

    if (setCSRF) {
        this.setCSRF();
    }
};

/**
 * Function to handle callbacks after xhr load.
 * @param  {Function} success - callback function on load success
 * @param  {Function} failure - callback function on load failure
 * @param  {Function} always - callback function to always trigger [optional]
 */
Xhr.prototype.onLoad = function(success, failure, always) {
    var xhr = this.xhr;
    xhr.addEventListener('load', function(evt){
        if (xhr.readyState === 4 && (xhr.status >= 200 && xhr.status < 300)) {
            success.call(this, xhr.responseText);
        } else {
            failure.call(this, xhr.responseText);
        }

        if (typeof always !== 'undefined') {
            always.call();
        }
    });
};

Xhr.prototype.send = function(data) {
    if (data) {
        this.xhr.send(data);
    } else {
        this.xhr.send();
    }
};

/**
 * Get native XMLHttpRequest object
 * @return {XMLHttpRequest}
 */
Xhr.prototype.getXMLHttpRequest = function() {
    return this.xhr;
};

module.exports = Xhr;