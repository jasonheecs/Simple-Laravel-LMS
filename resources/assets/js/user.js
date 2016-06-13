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
                console.log('delete');
            } else if(evt.target.id === 'save-changes-btn') {
                destroyEditors();
            } else if(evt.target.id === 'cancel-changes-btn') {
                revertChanges();
                switchButtonGroup();
                destroyEditors();
            }
        }
    });
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