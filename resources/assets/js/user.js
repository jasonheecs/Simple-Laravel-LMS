'use strict';

/**
 * Module used for user profile page, to edit user details or delete user record.
 * Also governs the changing of user statuses (i.e. admin, super admin, etc.)
 */

var helper = require('./helper');
var imgUploader = require('./img-uploader');
var notifications = require('./notifications');
var throttle = require('lodash/throttle');
var EditableObj = require('./editable-object'); // base class for Edit object to extend from

var userPanelEl;  // user panel DOM element
var avatarUploadEl; // DOM element / button that triggers user avatar upload

var userActionsGrpEl; // button group DOM element that contains buttons for editing / deleting user
var contentActionsGrpEl; // button group DOM element that contains buttons for saving / cancelling editors' changes


var Edit = Object.create(EditableObj, {
    init: {
        value: function() {
            userPanelEl = document.getElementById('user-panel');

            if (userPanelEl) {
                userActionsGrpEl = document.getElementById('user-actions-grp');
                contentActionsGrpEl = document.getElementById('content-actions-grp');

                this.nameEl = document.getElementById('name-editor'); // user name DOM element
                this.emailEl = document.getElementById('email-editor'); // user email DOM element
                this.companyEl = document.getElementById('company-editor'); // user company DOM element
                var editorOptions = {
                    toolbar:false,
                    disableReturn: true,
                    disableExtraSpaces: true
                };

                this.editBtnEl = document.getElementById('edit-profile-btn');
                this.deleteBtnEl = document.getElementById('delete-profile-btn');
                this.saveChangesBtnEl = document.getElementById('save-changes-btn');
                this.cancelChangesBtnEl = document.getElementById('cancel-changes-btn');

                avatarUploadEl = document.getElementById('img-upload-btn');
                initAvatarUpload('/users/'+ document.getElementById('user-id').value +'/upload/');

                // use fluent pattern construct to initialise base EditableObject class
                var EditableObjectConstruct = EditableObj.EditableObjectConstruct;
                EditableObjectConstruct
                    .setInitialBtnGrp(userActionsGrpEl)
                    .setHiddenBtnGrp(contentActionsGrpEl)
                    .setEditors([
                                {'element': this.nameEl, 'options': editorOptions, 'initialFocus': true, 'saveFieldName': 'name'},
                                {'element': this.emailEl, 'options': editorOptions, 'saveFieldName': 'email'},
                                {'element': this.companyEl, 'options': editorOptions, 'saveFieldName': 'company'}
                                ])
                    .setEditBtn(this.editBtnEl)
                    .setDeleteBtn(this.deleteBtnEl)
                    .setSaveChangesBtn(this.saveChangesBtnEl)
                    .setCancelChangesBtn(this.cancelChangesBtnEl)
                    .setSaveAjaxPath('/users/'+ document.getElementById('user-id').value)
                    .setSaveSuccessCallback(this.saveSuccess);

                EditableObj.init(userPanelEl, EditableObjectConstruct); // init parent class
                this.attachEventListeners();
            }
        }
    },
    saveSuccess: {
        configurable: false,
        get: function() {
            var callback = function() {
                document.getElementById('hero-user-name').textContent = this.nameEl.textContent;
            }.bind(this);
            return callback;
        }
    },
    attachEventListeners: {
        value: function() {
            userPanelEl.addEventListener('click', function(evt) {
                if (evt.target) {
                    if (evt.target === this.editBtnEl) {
                        avatarUploadEl.classList.remove('hidden');
                    } else if (evt.target === this.cancelChangesBtnEl || evt.target === this.saveChangesBtnEl) {
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
            }.bind(this));

            /**
             * Sends ajax request to toggle user to be admin / super admin
             * @param  {Boolean} isSuperAdmin - optional flag to determine if ajax request is to toggle for superadmin state. Default value is false.
             */
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
        }
    }
});

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