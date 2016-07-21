'use strict';

var Editor = require('./editor');
var helper = require('./helper');
var imgUploader = require('./img-uploader');
var notifications = require('./notifications');

var titleEditor; //editor for the title
var coursePanelEl; //wrapper element for the course title
var titleEl; //course title element
var lecturersEl; //element containing list of lecturers
var studentsEl; //element containing list of students
var adminActionsEl; //parent element containing the buttons of the admin actions
var contentActionsEl; //parent element containing the buttons of the content actions
var initialTitle; //variable used to store initial title (for reverting changes made)
var imgUploadBtn; //button element used to select image file to upload for course banner image

function init() {
    coursePanelEl = document.getElementById('course-panel');
    titleEl = document.getElementById('course-title-content');

    if (coursePanelEl) {
        initialTitle = titleEl.innerHTML;
        imgUploadBtn = document.getElementById('img-upload-btn');
        attachEventListeners();
        initCourseImgUpload('/courses/'+ document.getElementById('course-id').value +'/upload/');
    }
}

function attachEventListeners() {
    lecturersEl = document.getElementById('lecturers-list');
    studentsEl = document.getElementById('students-list');

    coursePanelEl.addEventListener('click', function(evt){
        adminActionsEl = document.getElementById('course-admin-actions');
        contentActionsEl = document.getElementById('course-content-actions');

        if (evt.target && (contentActionsEl.contains(evt.target) || adminActionsEl.contains(evt.target))) {
            if (evt.target.id === 'edit-course-btn') {
                editCourseListener();
            } else if (evt.target.id === 'cancel-changes-btn') {
                cancelChangesListener();
            } else if(evt.target.id === 'save-changes-btn') {
                saveChangesListener(evt.target);
            }
        }
    });

    if (lecturersEl) {
        lecturersEl.addEventListener('change', function(evt) {
            if (evt.target && helper.matches(evt.target, 'input[type="checkbox"]')) {
                setLecturersListener();
            }
        });
    }

    if (studentsEl) {
        studentsEl.addEventListener('change', function(evt) {
            if (evt.target && helper.matches(evt.target, 'input[type="checkbox"]')) {
                setStudentsListener();
            }
        });
    }

    function editCourseListener() {
        changeButtons();
        initEditors();
        titleEditor.setFocus();
        toggleCheckboxlists();
        imgUploadBtn.classList.remove('hidden');
    }

    function cancelChangesListener() {
        revertChanges();
        titleEditor.destroy();
        toggleCheckboxlists();
        changeButtons();
        imgUploadBtn.classList.add('hidden');
    }

    function saveChangesListener(saveBtnEl) {
         helper.disableButton(saveBtnEl);

         var newTitle = titleEditor.getContent()[titleEl.id].value;
         var updateData = {title: newTitle};

         // Send ajax request to update lesson
        var success = function(response) {
            initialTitle = newTitle;

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

        helper.sendAjaxRequest('PATCH', '/courses/'+ document.getElementById('course-id').value, success, failure, always, JSON.stringify(updateData));

        titleEditor.destroy();
        toggleCheckboxlists();
        changeButtons();
        imgUploadBtn.classList.add('hidden');
    }

    function setLecturersListener() {
        setCheckboxlistListener(helper.serialize(lecturersEl.querySelector('#lecturers-form')), 
            '/courses/'+ document.getElementById('course-id').value + '/lecturers');
    }

    function setStudentsListener() {
        setCheckboxlistListener(helper.serialize(studentsEl.querySelector('#students-form')), '/courses/'+ document.getElementById('course-id').value + '/students');
    }

    /**
     * Sends ajax PATCH request to a specified url when checkbox is checked / unchecked
     * @param {String} data - data to be sent via ajax
     * @param {String} url - ajax url path
     */
    function setCheckboxlistListener(data, url) {
        var success = function(response) {
            helper.setAlert(JSON.parse(response).response, 'alert--success');
        };
        var failure = function(response) {
            //display errors to alert element
            helper.setAlert(JSON.parse(response), 'alert--danger');
        };
        var always = function() {};

        helper.sendAjaxRequest('PATCH', url, success, failure, always, data);
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

/**
 * Revert changes made when 'Cancel' button is clicked
 */
function revertChanges() {
    titleEl.innerHTML = initialTitle;
}

/**
 * Initialize all Medium editors
 */
function initEditors() {
    titleEditor = new Editor();
    titleEditor.init(document.querySelector('.title-editable'), {
        toolbar:false,
        disableReturn: true,
        disableExtraSpaces: true
    });
}

function toggleCheckboxlists() {
    if (lecturersEl) {
        lecturersEl.classList.toggle('hidden');
    }
    if (studentsEl) {
        studentsEl.classList.toggle('hidden');
    }
}

function initCourseImgUpload(uploadUrl) {
    var heroEl = document.querySelector('.hero');
    var start = function() {
        heroEl.classList.add('uploading');
    };
    var done = function(e, data, imgUrl, imgFile) {
        heroEl.style.backgroundImage = 'url("'+ imgUrl + '")';
        heroEl.classList.remove('uploading');

        // populate hidden image field value during course creation
        var hiddenField = document.getElementById('course-img');
        if (hiddenField) {
            hiddenField.value = imgFile.url;
        }

        notifications.notify('Course image updated', 'success');
    };

    imgUploader.init(document.getElementById('course-img-upload'), imgUploadBtn);
    imgUploader.upload(uploadUrl, start, done);
}

var Create = {
    init: function() {
        imgUploadBtn = document.getElementById('img-upload-btn');

        if (imgUploadBtn) {
            imgUploadBtn.classList.remove('hidden');
            initCourseImgUpload('/courses/0/upload/');
        }
    }
};

module.exports = {
    init: init,
    create: Create
};