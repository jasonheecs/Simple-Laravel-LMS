'use strict';

var helper = require('./helper');

var coursePanelEl;
var lecturersEl;

function init() {
    coursePanelEl = document.getElementById('course-panel');

    if (coursePanelEl) {
        attachEventListeners();
    }
}

function attachEventListeners() {
    lecturersEl = document.getElementById('lecturers-list');

    if (lecturersEl) {
        lecturersEl.addEventListener('change', function(evt) {
            if (evt.target && evt.target.matches('input[type="checkbox"]')) {
                var data = helper.serialize(lecturersEl.querySelector('#lecturers-form'));

                var success = function(response) {
                    // setAlert(JSON.parse(response).response, 'alert--success');
                };

                var failure = function(response) {
                    // revertChanges();

                    // //display errors to alert element
                    // var errors = JSON.parse(response);
                    // var errorMsg = '';

                    // for (var error in errors) {
                    //     errorMsg = errors[error].reduce(function(previousMsg, currentMsg) {
                    //         return previousMsg + currentMsg;
                    //     });
                    // }
                    // setAlert(errorMsg, 'alert--danger');
                };
                var always = function() {
                     // helper.enableButton(saveBtnEl);
                };

                helper.sendAjaxRequest('PATCH', '/courses/'+ document.getElementById('course-id').value + '/lecturers', success, failure, always, data);
            }
        });
    }
}

module.exports = {
    init: init
};