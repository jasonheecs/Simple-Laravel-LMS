'use strict';

/**
 * Module used for user profile page, to edit user details or delete user record.
 * Also governs the changing of user statuses (i.e. admin, super admin, etc.)
 */

var Editor = require('./editor');
var helper = require('./helper');
var imgUploader = require('./img-uploader');
var notifications = require('./notifications');
var throttle = require('lodash/throttle');

var userPanelEl;  // user panel DOM element
var nameEditor; // editor for user's name
var emailEditor; // editor for user's email
var companyEditor; // editor for user's company
var avatarUploadEl; // DOM element / button that triggers user avatar upload

var userActionsGrpEl; // button group DOM element that contains buttons for editing / deleting user
var contentActionsGrpEl; // button group DOM element that contains buttons for saving / cancelling editors' changes

var nameEl; // user name DOM element
var emailEl; // user email DOM element
var companyEl; // user company DOM element
var initialName; // initial name (before any changes are saved)
var initialEmail; // initial email (before any changes are saved)
var initialCompany; // initial company (before any changes are saved)

var Edit = {
    init: function() {
        userPanelEl = document.getElementById('user-panel');

        if (userPanelEl) {
            userActionsGrpEl = document.getElementById('user-actions-grp');
            contentActionsGrpEl = document.getElementById('content-actions-grp');
            nameEl = document.getElementById('name-editor');
            emailEl = document.getElementById('email-editor');
            companyEl = document.getElementById('company-editor');
            avatarUploadEl = document.getElementById('img-upload-btn');
            initialName = nameEl.innerHTML;
            initialEmail = emailEl.innerHTML;
            initialCompany = companyEl.innerHTML;

            initAvatarUpload('/users/'+ document.getElementById('user-id').value +'/upload/');
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
                    avatarUploadEl.classList.add('hidden');
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
            var newName = nameEditor.getContent()[nameEl.id].value;
            var newEmail = emailEditor.getContent()[emailEl.id].value;
            var newCompany = companyEditor.getContent()[companyEl.id].value;

            console.log(profileIsUnchanged);

            // Only send AJAX request to update profile if there are changes made to the profile
            if (!profileIsUnchanged()) {
                helper.disableButton(saveBtnEl);
                var updateData = {name: newName, email: newEmail, company: newCompany};

                // Send ajax request to update user
                var success = function(response) {
                    initialName = newName;
                    initialEmail = newEmail;
                    initialCompany = newCompany;

                    document.getElementById('hero-user-name').textContent = newName;

                    notifications.notify(JSON.parse(response).response, 'success');
                };
                var failure = function(response) {
                    _this.revertChanges();

                    //display errors to alert element
                    var errors = JSON.parse(response);
                    var errorMsg = '';

                    for (var error in errors) {
                        errorMsg = errors[error].reduce(function(previousMsg, currentMsg) {
                            return previousMsg + currentMsg;
                        });
                    }

                    notifications.notify(errorMsg, 'danger');
                };
                var always = function() {
                    helper.enableButton(saveBtnEl);
                };
                helper.sendAjaxRequest('PATCH', '/users/'+ document.getElementById('user-id').value, success, failure, always, JSON.stringify(updateData));
            }

            _this.switchButtonGroup();
            _this.destroyEditors();
            avatarUploadEl.classList.add('hidden');

            /**
             * Check if profile data has been updated
             * @return {Boolean}
             */
            function profileIsUnchanged() {
                return (newName === initialName && newEmail === initialEmail && newCompany === initialCompany);
            }
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
                notifications.notify(JSON.parse(response).response, 'success');
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
                notifications.notify(errorMsg, 'danger');
            };
            var always = function() {};

            helper.sendAjaxRequest('PATCH', '/users/'+ document.getElementById('user-id').value + '/setadmin', success, failure, always, JSON.stringify(updateData));
        }
    },

    initEditors: function() {
        nameEditor = new Editor();
        emailEditor = new Editor();
        companyEditor = new Editor();
        var editorOptions = {
            toolbar:false,
            disableReturn: true,
            disableExtraSpaces: true
        };
        
        nameEditor.init(nameEl, editorOptions);
        emailEditor.init(emailEl, editorOptions);
        companyEditor.init(companyEl, editorOptions);
    },

    destroyEditors: function() {
        nameEditor.destroy();
        emailEditor.destroy();
        companyEditor.destroy();
    },

    /**
     * Revert changes made when 'Cancel' button is clicked
     */
    revertChanges: function() {
        nameEl.innerHTML = initialName;
        emailEl.innerHTML = initialEmail;
        companyEl.innerHTML = initialCompany;
    }
};

var Create = {
    init: function() {
        userPanelEl = document.getElementById('user-panel');

        if (userPanelEl) {
            contentActionsGrpEl = document.getElementById('content-actions-grp');
            avatarUploadEl = document.getElementById('img-upload-btn');
            avatarUploadEl.classList.remove('hidden');

            initAvatarUpload('/users/0/upload/');
            this.attachEventListener();
        }
    },

    attachEventListener: function() {
        var avatarApi = window.location.protocol + '//api.adorable.io/avatars/150/';
        var usernameInputEl = document.getElementById('user-name');

        userPanelEl.addEventListener('keyup', throttle(function(evt) {
            if (evt.target) {
                if(evt.target === usernameInputEl) {
                    document.getElementById('hero-user-name').textContent = usernameInputEl.value;
                }
            }
        }, 50).bind(this));

        userPanelEl.addEventListener('blur', function(evt) {
            if (evt.target && evt.target === usernameInputEl) {
                var hiddenAvatarField = document.getElementById('user-avatar');
                if ((hiddenAvatarField.value.length === 0 || hiddenAvatarField.value.substring(0, avatarApi.length) === avatarApi) && 
                    usernameInputEl.value.length > 0) {
                    var avatarUrl = avatarApi + encodeURIComponent(usernameInputEl.value);
                    hiddenAvatarField.value = avatarUrl;
                    document.getElementById('user-avatar-img').src = avatarUrl;
                }
            }
        }.bind(this), true);
    }
};

/**
 * Initialise the image uploader for the user avatar element
 * @param  {String} uploadUrl - Route for handling the avatar image upload
 */
function initAvatarUpload(uploadUrl) {
    var avatarEl = document.getElementById('user-avatar-img');
    var heroEl = document.querySelector('.hero');
    var start = function() {
        heroEl.classList.add('uploading');
    };
    var done = function(e, data, imgUrl, imgFile) {
        avatarEl.src = imgUrl;
        heroEl.classList.remove('uploading');

        // populate hidden avatar field value during user creation
        var hiddenField = document.getElementById('user-avatar');
        if (hiddenField) {
            hiddenField.value = imgFile.url;
        }

        notifications.notify('User avatar updated', 'success');

    };

    imgUploader.init(document.getElementById('user-img-upload'), avatarUploadEl);
    imgUploader.upload(uploadUrl, start, done);
}

module.exports = {
    create: Create,
    edit: Edit
};