'use strict';

var MediumEditor = require('medium-editor');
var $ = require('jquery');
require('medium-editor-insert-plugin')($);


function Editor() {
    var editor;
    var editableElement;
}

Editor.prototype.init = function(editableElement, options, useImagePlugin) {
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

    useImagePlugin = useImagePlugin || false;

    if (this.editor) {
        this.editor.setup();
    } else {
        this.editor = new MediumEditor(editableElement, options);
    }

    if (useImagePlugin) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $(editableElement).mediumInsert({
            editor: this.editor,
            addons: {
                images: {
                    fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                        url: '/upload', // (string) A relative path to an upload script
                    }
                }
            }
        });
    }
};

Editor.prototype.setFocus = function() {
    if (this.editableElement.firstChild) {
        this.editor.selectElement(this.editableElement.firstChild);
    } else {
        this.editor.selectElement(this.editableElement);
    }
};

Editor.prototype.getContent = function() {
    return this.editor.serialize();
};

Editor.prototype.destroy = function() {
    return this.editor.destroy();
};

module.exports = Editor;