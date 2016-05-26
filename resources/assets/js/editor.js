'use strict';

var MediumEditor = require('medium-editor');
var $ = require('jquery');
require('medium-editor-insert-plugin')($);


function Editor() {
    var editor;
    var editableElement;
}

Editor.prototype.init = function(editableElement, options) {
    this.editableElement = editableElement;

    options = options || {
        toolbar: {
            buttons: [
                'bold',
                'italic',
                'underline',
                'anchor',
                'h2',
                'h3',
                'justifyLeft',
                'justifyRight'
            ]
        }
    };

    if (this.editor) {
        this.editor.setup();
    } else {
        this.editor = new MediumEditor(editableElement, options);
    }

    $('#lesson-body-content').mediumInsert({
        editor: this.editor
    });
};

Editor.prototype.setFocus = function() {
    this.editor.selectElement(this.editableElement.firstChild);
};

Editor.prototype.getContent = function() {
    return this.editor.serialize();
};

Editor.prototype.destroy = function() {
    return this.editor.destroy();
};

module.exports = Editor;