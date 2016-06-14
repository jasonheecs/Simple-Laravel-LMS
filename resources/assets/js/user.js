'use strict';

var Editor = require('./editor');
var helper = require('./helper');

var userPanelEl;
var nameEditor;
var emailEditor;

var userActionsGrpEl;
var contentActionsGrpEl;

var nameEl;
var emailEl;
var initialName;
var initialEmail;

function init() {
    userPanelEl = document.getElementById('user-panel');

    if (userPanelEl) {
        userActionsGrpEl = document.getElementById('user-actions-grp');
        contentActionsGrpEl = document.getElementById('content-actions-grp');
        nameEl = document.getElementById('name-editor');
        emailEl = document.getElementById('email-editor');
        initialName = nameEl.innerHTML;
        initialEmail = emailEl.innerHTML;

        attachEventListener();
    }
}

function switchButtonGroup() {
    userActionsGrpEl.classList.toggle('hidden');
    contentActionsGrpEl.classList.toggle('hidden');
}

function attachEventListener() {
    userPanelEl.addEventListener('click', function(evt) {
        if (evt.target) {
            if (evt.target.id === 'edit-profile-btn') {
                initEditors();
                nameEditor.setFocus();
                switchButtonGroup();
            } else if(evt.target.id === 'delete-profile-btn') {
                deleteUserListener(evt);
            } else if(evt.target.id === 'save-changes-btn') {
                saveChangesListener(evt.target);
            } else if(evt.target.id === 'cancel-changes-btn') {
                revertChanges();
                switchButtonGroup();
                destroyEditors();
            }
        }
    });

    userPanelEl.addEventListener('change', function(evt) {
        if (evt.target) {
            if (evt.target.id === 'admin-checkbox') {
                toggleAdminListener();
            } else if (evt.target.id === 'super-admin-checkbox') {
                toggleAdminListener(true);
            }
        }
    });

    function saveChangesListener(saveBtnEl) {
         helper.disableButton(saveBtnEl);

        var newName = nameEditor.getContent()[nameEl.id].value;
        var newEmail = emailEditor.getContent()[emailEl.id].value;
        var updateData = {name: newName, email: newEmail};

        // Send ajax request to update user
        var success = function(response) {
            initialName = newName;
            initialEmail = newEmail;

            helper.setAlert(JSON.parse(response).response, 'alert--success');
        };
        var failure = function(response) {
            revertChanges();

            //display errors to alert element
            var errors = JSON.parse(response);
            var errorMsg = '';

            for (var error in errors) {
                errorMsg = errors[error].reduce(function(previousMsg, currentMsg) {
                    return previousMsg + currentMsg;
                });
            }
            helper.setAlert(errorMsg, 'alert--danger');
        };
        var always = function() {
             helper.enableButton(saveBtnEl);
        };
        helper.sendAjaxRequest('PATCH', '/users/'+ document.getElementById('user-id').value, success, failure, always, JSON.stringify(updateData));

        destroyEditors();
    }

    function deleteUserListener(evt) {
        var check = window.confirm('Are you sure?');

        if (!check)
            evt.preventDefault();

        return check;
    }

    function toggleAdminListener(isSuperAdmin) {
        var updateData = {isSuperAdmin: false};

        if (isSuperAdmin) {
            updateData.isSuperAdmin = true;
        }

        // Send ajax request to update user admin status
        var success = function(response) {
            helper.setAlert(JSON.parse(response).response, 'alert--success');
        };
        var failure = function(response) {
            //display errors to alert element
            var errors = JSON.parse(response);
            var errorMsg = '';

            for (var error in errors) {
                errorMsg = errors[error].reduce(function(previousMsg, currentMsg) {
                    return previousMsg + currentMsg;
                });
            }
            helper.setAlert(errorMsg, 'alert--danger');
        };
        var always = function() {};

        helper.sendAjaxRequest('PATCH', '/users/'+ document.getElementById('user-id').value + '/setadmin', success, failure, always, JSON.stringify(updateData));
    }
}

function initEditors() {
    nameEditor = new Editor();
    emailEditor = new Editor();
    var editorOptions = {
        toolbar:false,
        disableReturn: true,
        disableExtraSpaces: true
    };
    
    nameEditor.init(document.getElementById('name-editor'), editorOptions);
    emailEditor.init(document.getElementById('email-editor'), editorOptions);
}

function destroyEditors() {
    nameEditor.destroy();
    emailEditor.destroy();
}

/**
 * Revert changes made when 'Cancel' button is clicked
 */
function revertChanges() {
    nameEl.innerHTML = initialName;
    emailEl.innerHTML = initialEmail;
}

module.exports = {
    init: init
};