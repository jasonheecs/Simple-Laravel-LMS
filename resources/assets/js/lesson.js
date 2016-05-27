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
    adminActionsEl = document.getElementById('lesson-admin-actions');
    contentActionsEl = document.getElementById('lesson-content-actions');
    lessonPanelEl = document.getElementById('lesson-panel');

    if (lessonPanelEl) {
        titleEl = lessonPanelEl.querySelector('#lesson-title-content');
        initialTitle = titleEl.innerHTML;

        articleEl = lessonPanelEl.querySelector('#lesson-body-content');
        initialBody = articleEl.innerHTML;

        attachEventListeners();

        if (document.getElementById('create-lesson')) {
            // if we are creating a lesson (i.e.: not in editing page), start the editors.
            initEditors();
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
    titleEditor.init(document.querySelector('.title-editable'), {toolbar:false});
    bodyEditor.init(document.querySelector('.body-editable'), {}, true);

    bodyEditor.setFocus();
}

function attachEventListeners() {
    lessonPanelEl.addEventListener('click', function(evt){
        if (evt.target && (evt.target.id === 'edit-lesson-btn' || evt.target.id === 'cancel-changes-btn' || evt.target.id === 'save-changes-btn')) {
            changeButtons();

            if (evt.target.id === 'edit-lesson-btn') {
                editLessonListener();
            } else if(evt.target.id === 'cancel-changes-btn') {
                cancelChangesListener();
            } else if(evt.target.id === 'save-changes-btn') {
                saveChangesListener(evt.target);
            }
        }
    });
}

function editLessonListener() {
    initEditors();
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
    var xhr = new XMLHttpRequest();
    xhr.open('PATCH', '/lessons/'+ document.getElementById('lesson-id').value);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.addEventListener('load', function(evt){
        if (xhr.readyState === 4 && xhr.status === 200) {
            initialTitle = newTitle;
            initialBody = newBody;

            setAlert(JSON.parse(xhr.responseText).response, 'alert--success');
        } else {
            revertChanges();

            //display errors to alert element
            var errors = JSON.parse(xhr.responseText);
            var errorMsg = '';

            for (var error in errors) {
                errorMsg = errors[error].reduce(function(previousMsg, currentMsg) {
                    return previousMsg + currentMsg;
                });
            }
            setAlert(errorMsg, 'alert--failure');
        }

        helper.enableButton(saveBtnEl);
    });

    xhr.send(JSON.stringify(updateData));

    titleEditor.destroy();
    bodyEditor.destroy();

    function setAlert(message, classList) {
        var alertEl = document.getElementById('alert');
        alertEl.textContent = message;
        alertEl.classList.add(classList);
    }
}

module.exports = {
    init: init
};