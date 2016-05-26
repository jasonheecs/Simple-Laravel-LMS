'use strict';

/* globals XMLHttpRequest */

var Editor = require('./editor');
var titleEditor;
var bodyEditor;

var adminActionsEl;
var contentActionsEl;
var lessonPanelEl;
var titleEl;
var articleEl;
var initialTitle;
var initialBody;

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

function attachEventListeners() {
    lessonPanelEl.addEventListener('click', function(evt){
        if (evt.target && (evt.target.id === 'edit-lesson-btn' || evt.target.id === 'cancel-changes-btn' || evt.target.id === 'save-changes-btn')) {
            changeButtons();

            if (evt.target.id === 'edit-lesson-btn') {
                editLessonListener();
            } else if(evt.target.id === 'cancel-changes-btn') {
                cancelChangesListener();
            } else if(evt.target.id === 'save-changes-btn') {
                saveChangesListener();
            }
        }
    });

    function editLessonListener() {
        titleEditor = new Editor();
        bodyEditor = new Editor();
        titleEditor.init(document.querySelector('.title-editable'), {toolbar:false});
        bodyEditor.init(document.querySelector('.body-editable'));

        bodyEditor.setFocus();
    }

    function cancelChangesListener() {
        titleEl.innerHTML = initialTitle;
        articleEl.innerHTML = initialBody;
        titleEditor.destroy();
        bodyEditor.destroy();
    }

    function saveChangesListener() {
        var newTitle = titleEditor.getContent()[titleEl.id].value;
        var newContent = bodyEditor.getContent()[articleEl.id].value;

        console.log(newTitle);
        console.log(newContent);

        // var xhr = new XMLHttpRequest();
        // xhr.open('PATCH', '/lessons/'+ document.getElementById('lesson-id').value);
        // console.log('lessons/'+ document.getElementById('lesson-id').value);
        // xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        // xhr.addEventListener('load', function(evt){
        //     console.log(xhr.responseText);
        // });

        // xhr.send(newContent);

        // initialLessonContent = newContent;
        titleEditor.destroy();
        bodyEditor.destroy();
    }
}

module.exports = {
    init: init
};