'use strict';

var MediumEditor = require('medium-editor');
var $ = require('jquery');
require('medium-editor-insert-plugin')($);
// var rangy = require('rangy');
// require('rangy/lib/rangy-classapplier');

// rangy.init();

function Editor() {
    var editor;
    var editableElement;
}

Editor.prototype.init = function(editableElement, options) {
    this.editableElement = editableElement;

    options = options || {};

    if (!options.hasOwnProperty('toolbar')) {
        options.toolbar = {
            buttons: [
                'bold',
                'italic',
                'underline',
                'anchor',
                'h2',
                'h3',
                'justifyLeft',
                'justifyRight',
                'pre'
            ]
        };
    }

    if (this.editor) {
        this.editor.setup();
    } else {
        this.editor = new MediumEditor(editableElement, options);
    }
};

Editor.prototype.enableImagePlugin = function(imageUploadUrl, removeUploadUrl) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $(this.editableElement).mediumInsert({
        editor: this.editor,
        addons: {
            images: {
                deleteScript: removeUploadUrl,
                fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                    url: imageUploadUrl, // (string) A relative path to an upload script
                }
            }
        }
    });
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

Editor.prototype.subscribe = function(event, callback) {
    this.editor.subscribe(event, callback);
};

// var CodeButton = MediumEditor.extensions.button.extend({
//     name: 'code',

//     tagNames: ['code'],
//     contentDefault: '<b>code</b>',
//     aria: 'Code',
//     action: 'code',

//     init: function() {
//         MediumEditor.extensions.button.prototype.init.call(this);

//         this.classApplier = rangy.createCssClassApplier('code', {
//             elementTagName: 'code',
//             normalize: true
//         });
//     },

//     handleClick: function(event) {
//         var sel = rangy.getSelection();
//         sel.parentElement = document.createElement('pre');
//         console.log(sel);
//         this.classApplier.toggleSelection();

//         // Ensure the editor knows about an html change so watchers are notified
//         // ie: <textarea> elements depend on the editableInput event to stay synchronized
//         this.base.checkContentChanged();
//     }
// });

module.exports = Editor;