'use strict';

/* globals XMLHttpRequest */

var Editor = require('./editor');
var helper = require('./helper');
var titleEditor; //editor for the title
var bodyEditor; //editor for the body content

var adminActionsEl; //parent element containing the buttons of the admin actions
var contentActionsEl; //parent element containing the buttons of the content actions
var lessonPanelEl; //wrapper element for the lesson title and content
var titleEl; //lesson title element
var articleEl; //lesson body content element
var initialTitle; //variable used to store initial title (for reverting changes made)
var initialBody; //variable used to store initial body content (for reverting changes made)

function init() {
    lessonPanelEl = document.getElementById('lesson-panel');

    if (lessonPanelEl) {
        contentActionsEl = document.getElementById('lesson-content-actions');

        if (document.getElementById('create-lesson')) {
            // if we are creating a lesson (i.e.: not in editing page), start the editors.
            initEditors();
        } else {
            adminActionsEl = document.getElementById('lesson-admin-actions');
            titleEl = lessonPanelEl.querySelector('#lesson-title-content');
            initialTitle = titleEl.innerHTML;

            articleEl = lessonPanelEl.querySelector('#lesson-body-content');
            initialBody = articleEl.innerHTML;

            attachEventListeners();
        }
    }
}

/**
 * Toggle between showing either the admin actions or the lesson content actions
 */
function changeButtons() {
    if (contentActionsEl.classList.contains('hidden')) {
        contentActionsEl.classList.remove('hidden');
        adminActionsEl.classList.add('hidden');
    } else if (adminActionsEl.classList.contains('hidden')) {
        adminActionsEl.classList.remove('hidden');
        contentActionsEl.classList.add('hidden');
    }
}

function revertChanges() {
    titleEl.innerHTML = initialTitle;
    articleEl.innerHTML = initialBody;
}

function initEditors() {
    titleEditor = new Editor();
    bodyEditor = new Editor();
    titleEditor.init(document.querySelector('.title-editable'), {
        toolbar:false,
        disableReturn: true,
        disableExtraSpaces: true
    });
    bodyEditor.init(document.querySelector('.body-editable'), {}, true);
}

function attachEventListeners() {
    lessonPanelEl.addEventListener('click', function(evt){
        if (evt.target && (contentActionsEl.contains(evt.target) || adminActionsEl.contains(evt.target))) {
            if (evt.target.id === 'edit-lesson-btn') {
                changeButtons();
                editLessonListener();
            } else if(evt.target.id === 'delete-lesson-btn') {
                deleteLessonListener(evt);
            } else if(evt.target.id === 'cancel-changes-btn') {
                changeButtons();
                cancelChangesListener();
            } else if(evt.target.id === 'save-changes-btn') {
                changeButtons();
                saveChangesListener(evt.target);
            } else if(evt.target.id === 'add-lesson-file-btn') {
                addLessonFileListener();
            } else if(helper.matches(evt.target, '.btn-lesson-publish')) {
                addPublishListener(evt.target);
            }
        }
    });
}

function addLessonFileListener() {
    var htmlTemplate = document.getElementById('lesson-hidden-template');
    var addFileFormEl = document.getElementById('add-lesson-file-form');

    if (htmlTemplate && addFileFormEl) {
        var template = htmlTemplate.cloneNode(true).innerHTML;
        addFileFormEl.insertAdjacentHTML('beforeend', template);
        addFileFormEl.classList.remove('hidden');
    }
}

function addPublishListener(btnEl) {
    helper.disableButton(btnEl);

    // Send ajax request to update publishing state
    var success = function(response) {
        togglePublishButton();
        setAlert(JSON.parse(response).response, 'alert--success');
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
        setAlert(errorMsg, 'alert--danger');
    };
    var always = function() {
         helper.enableButton(btnEl);
    };

    helper.sendAjaxRequest('PATCH', '/lessons/'+ document.getElementById('lesson-id').value + '/publish', success, failure, always);

    /**
     * Toggle between 'Publish' and 'Unpublish' button
     */
    function togglePublishButton() {
        if (btnEl.id === 'publish-lesson-btn') {
            btnEl.id = 'unpublish-lesson-btn';
            btnEl.textContent = 'Unpublish';
            btnEl.classList.remove('btn--muted-inverse');
            btnEl.classList.add('btn--muted');
        } else if (btnEl.id === 'unpublish-lesson-btn') {
            btnEl.id = 'publish-lesson-btn';
            btnEl.textContent = 'Publish';
            btnEl.classList.remove('btn--muted');
            btnEl.classList.add('btn--muted-inverse');
        }
    }
}

function editLessonListener() {
    initEditors();
    bodyEditor.setFocus();
}

function deleteLessonListener(evt) {
    var check = window.confirm('Are you sure?');

    if (!check)
        evt.preventDefault();

    return check;
}

function cancelChangesListener() {
    revertChanges();
    titleEditor.destroy();
    bodyEditor.destroy();
}

function saveChangesListener(saveBtnEl) {
    helper.disableButton(saveBtnEl);

    var newTitle = titleEditor.getContent()[titleEl.id].value;
    var newBody = bodyEditor.getContent()[articleEl.id].value;
    var updateData = {title: newTitle, body: newBody};

    // Send ajax request to update lesson
    var success = function(response) {
        initialTitle = newTitle;
        initialBody = newBody;

        setAlert(JSON.parse(response).response, 'alert--success');
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
        setAlert(errorMsg, 'alert--danger');
    };
    var always = function() {
         helper.enableButton(saveBtnEl);
    };
    helper.sendAjaxRequest('PATCH', '/lessons/'+ document.getElementById('lesson-id').value, success, failure, always, JSON.stringify(updateData));

    titleEditor.destroy();
    bodyEditor.destroy();
}

//TODO: move into alert.js
function setAlert(message, classList) {
    var alertEl = document.getElementById('alert');
    alertEl.textContent = message;
    alertEl.classList.add(classList);

    if (alertEl.classList.contains('hidden')) {
        alertEl.classList.remove('hidden');
    }
}

module.exports = {
    init: init
};