var Editor = require('./editor');
var helper = require('./helper');
var notifications = require('./notifications');
var helper = require('./helper');

var panelEl;
var initialBtnGrp;
var hiddenBtnGrp;

var editors;
var editorConfigs;
var editorInitialValues;

var editBtn;
var deleteBtn;
var saveChangesBtn;
var cancelChangesBtn;

var saveAjaxPath;
var saveSuccessCallback;
var saveFailureCallback;
var saveAlwaysCallback;

/**
 * Fluent interface for initialising EditableObjects
 */
var EditableObjectConstruct = function() {
    this.setInitialBtnGrp = function(initialBtnGrp) {
        this.initialBtnGrp = initialBtnGrp;
        return this;
    };

    this.setHiddenBtnGrp = function(hiddenBtnGrp) {
        this.hiddenBtnGrp = hiddenBtnGrp;
        return this;
    };

    this.setEditors = function(editorConfigs) {
        this.editorConfigs = editorConfigs;
        return this;
    };

    this.setEditBtn = function(editBtn) {
        this.editBtn = editBtn;
        return this;
    };

    this.setDeleteBtn = function(deleteBtn) {
        this.deleteBtn = deleteBtn;
        return this;
    };

    this.setSaveChangesBtn = function(saveChangesBtn) {
        this.saveChangesBtn = saveChangesBtn;
        return this;
    };

    this.setCancelChangesBtn = function(cancelChangesBtn) {
        this.cancelChangesBtn = cancelChangesBtn;
        return this;
    };

    this.setSaveAjaxPath = function(ajaxPath) {
        this.saveAjaxPath = ajaxPath;
        return this;
    };

    this.setSaveSuccessCallback = function(callback) {
        this.saveSuccessCallback = callback;
        return this;
    };

    this.setSaveFailureCallback = function(callback) {
        this.saveFailureCallback = callback;
        return this;
    };

    this.setSaveAlwaysCallback = function(callback) {
        this.saveAlwaysCallback = callback;
        return this;
    };
};

function init(_panelEl, _editableObjectConstruct) {
    panelEl = _panelEl;

    if (panelEl) {
        initialBtnGrp = _editableObjectConstruct.initialBtnGrp;
        hiddenBtnGrp = _editableObjectConstruct.hiddenBtnGrp;
        editBtn = _editableObjectConstruct.editBtn;
        deleteBtn = _editableObjectConstruct.deleteBtn;
        saveChangesBtn = _editableObjectConstruct.saveChangesBtn;
        cancelChangesBtn = _editableObjectConstruct.cancelChangesBtn;
        editorConfigs = _editableObjectConstruct.editorConfigs;

        saveAjaxPath = _editableObjectConstruct.saveAjaxPath;
        saveSuccessCallback = _editableObjectConstruct.saveSuccessCallback;
        saveFailureCallback = _editableObjectConstruct.saveFailureCallback;
        saveAlwaysCallback = _editableObjectConstruct.saveAlwaysCallback;

        editors = [];
        editorInitialValues = [];

        setInitialValues();
        attachEventListeners();
    }
}

function attachEventListeners() {
    panelEl.addEventListener('click', function(evt) {
        if (evt.target) {
            if (editBtn && evt.target === editBtn) {
                editListener();
            } else if (deleteBtn && evt.target === deleteBtn) {
                deleteListener();
            } else if (saveChangesBtn && evt.target === saveChangesBtn) {
                saveChangesListener();
            } else if (cancelChangesBtn && evt.target === cancelChangesBtn) {
                cancelChangesListener();
            }
        }
    });

    function editListener() {
        switchButtonGroup();
        initEditors();
        switchFocus();
    }

    function deleteListener() {
        var check = window.confirm('Are you sure?');

        if (!check)
            evt.preventDefault();

        return check;
    }

    function saveChangesListener() {
        if (valuesUpdated) {
            // helper.disableButton(saveChangesBtn);

            // // Send ajax request to update model
            // var success = function(response) {
            //     if (saveSuccessCallback) {
            //         saveSuccessCallback.call();
            //     }

            //     notifications.notify(JSON.parse(response).response, 'success');
            // };
            // var failure = function(response) {
            //     if (saveFailureCallback) {
            //         saveFailureCallback.call();
            //     }
            //     revertChanges();

            //     //display errors to alert element
            //     var errors = JSON.parse(response);
            //     var errorMsg = '';

            //     for (var error in errors) {
            //         errorMsg = errors[error].reduce(function(previousMsg, currentMsg) {
            //             return previousMsg + currentMsg;
            //         });
            //     }

            //     notifications.notify(errorMsg, 'danger');
            // };
            // var always = function() {
            //     if (saveAlwaysCallback) {
            //         saveAlwaysCallback.call();
            //     }
            //     helper.enableButton(saveChangesBtn);
            // };
            // helper.sendAjaxRequest('PATCH', saveAjaxPath, success, failure, always, JSON.stringify(saveData));
            console.log(getSaveData());
        }

        switchButtonGroup();
        destroyEditors();

        /**
         * Check if editor values have been updated by user
         * @return {Boolean}
         */
        function valuesUpdated() {
            return editors.some(function(editor) {
                return editor.getContent()[editor.editableElement.id].value !== 
                    editorInitialValues.find(function(initialValue) {
                        return initialValue.element === editor.editableElement;
                    }).value;
            });
        }

        function getSaveData() {
            var data = {};
            editorConfigs.forEach(function(editorConfig) {
                // find the corresponding editor in editors array for this editorConfig
                var editor = editors.find(function(editor) {
                    return editor.editableElement === editorConfig.element;
                });

                data[editorConfig.saveFieldName] = editor.getContent()[editorConfig.element.id].value;
            });

            return data;
        }
    }

    function cancelChangesListener() {
        switchButtonGroup();
        destroyEditors();
        revertChanges();
    }
}

function switchButtonGroup() {
    initialBtnGrp.classList.toggle('hidden');
    hiddenBtnGrp.classList.toggle('hidden');
}

function setInitialValues() {
    editorConfigs.forEach(function(editorConfig) {
        editorInitialValues.push({'element': editorConfig.element, 'value': editorConfig.element.innerHTML});
    });
}

function initEditors() {
    editorConfigs.forEach(function(editorConfig) {
        var editor = new Editor();
        editor.init(editorConfig.element, editorConfig.options);
        editors.push(editor);
    });
}

function destroyEditors() {
    editors.forEach(function(editor) {
        editor.destroy();
    });
    editors = [];
}

function switchFocus() {
    var focusedEditor = editors.find(function(editor) {
       return editor.editableElement === editorConfigs.find(function(editorConfig) {
            return editorConfig.initialFocus;
        }).element;
    });

    if (focusedEditor) {
        focusedEditor.setFocus();
    }
}

function revertChanges() {
    editorInitialValues.forEach(function(initialValue) {
        initialValue.element.innerHTML = initialValue.value;
    });
}

module.exports = {
    init: init,
    EditableObjectConstruct: new EditableObjectConstruct()
};