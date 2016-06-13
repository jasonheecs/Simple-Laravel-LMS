'use strict';

var Editor = require('./editor');
var helper = require('./helper');

var userPanelEl;
var nameEditor;
var emailEditor;

function init() {
    userPanelEl = document.getElementById('user-panel');
    attachEventListener();
}

function attachEventListener() {
    userPanelEl.addEventListener('click', function(evt) {
        if (evt.target) {
            if (evt.target.id === 'edit-profile-btn') {
                initEditors();
                nameEditor.setFocus();
            } else if(evt.target.id === 'delete-profile-btn') {
                console.log('delete');
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

module.exports = {
    init: init
};