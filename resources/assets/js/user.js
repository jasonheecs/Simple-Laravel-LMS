'use strict';

var Editor = require('./editor');
var helper = require('./helper');
var imgUploader = require('./img-uploader');

var userPanelEl;
var nameEditor;
var emailEditor;
var avatarUploadEl;

var userActionsGrpEl;
var contentActionsGrpEl;

var nameEl;
var emailEl;
var initialName;
var initialEmail;

var Edit = {
    init: function() {
        userPanelEl = document.getElementById('user-panel');

        if (userPanelEl) {
            userActionsGrpEl = document.getElementById('user-actions-grp');
            contentActionsGrpEl = document.getElementById('content-actions-grp');
            nameEl = document.getElementById('name-editor');
            emailEl = document.getElementById('email-editor');
            avatarUploadEl = document.getElementById('img-upload-btn');
            initialName = nameEl.innerHTML;
            initialEmail = emailEl.innerHTML;

            initAvatarUpload();
            this.attachEventListener();
        }
    },

    switchButtonGroup: function() {
        userActionsGrpEl.classList.toggle('hidden');
        contentActionsGrpEl.classList.toggle('hidden');
    },

    attachEventListener: function() {
        var _this = this;
        userPanelEl.addEventListener('click', function(evt) {
            if (evt.target) {
                if (evt.target.id === 'edit-profile-btn') {
                    this.initEditors();
                    nameEditor.setFocus();
                    this.switchButtonGroup();
                    avatarUploadEl.classList.remove('hidden');
                } else if(evt.target.id === 'delete-profile-btn') {
                    deleteUserListener(evt);
                } else if(evt.target.id === 'save-changes-btn') {
                    saveChangesListener(evt.target);
                } else if(evt.target.id === 'cancel-changes-btn') {
                    this.revertChanges();
                    this.switchButtonGroup();
                    this.destroyEditors();
                }
            }
        }.bind(this));

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

                document.getElementById('hero-user-name').textContent = newName;

                helper.setAlert(JSON.parse(response).response, 'alert--success');
            };
            var failure = function(response) {
                this.revertChanges();

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

            _this.switchButtonGroup();
            _this.destroyEditors();
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
    },

    initEditors: function() {
        nameEditor = new Editor();
        emailEditor = new Editor();
        var editorOptions = {
            toolbar:false,
            disableReturn: true,
            disableExtraSpaces: true
        };
        
        nameEditor.init(document.getElementById('name-editor'), editorOptions);
        emailEditor.init(document.getElementById('email-editor'), editorOptions);
    },

    destroyEditors: function() {
        nameEditor.destroy();
        emailEditor.destroy();
    },

    /**
     * Revert changes made when 'Cancel' button is clicked
     */
    revertChanges: function() {
        nameEl.innerHTML = initialName;
        emailEl.innerHTML = initialEmail;
    }
};

function initAvatarUpload() {
    var avatarEl = document.getElementById('user-avatar');
    var heroEl = document.querySelector('.hero');
    var start = function() {
        heroEl.classList.add('uploading');
    };
    var done = function(e, data, imgUrl) {
        avatarEl.src = imgUrl;
        heroEl.classList.remove('uploading');
    };

    imgUploader.init(document.getElementById('user-img-upload'), avatarUploadEl);
    imgUploader.upload('/users/'+ document.getElementById('user-id').value +'/upload/', start, done);
}

module.exports = {
    edit: Edit
};