'use strict';

/**
 * Module for handling image uploads. Interfaces with the jQuery File-Upload plugin.
 * Refer to https://github.com/blueimp/jQuery-File-Upload/wiki
 */

var $ = require('jquery');

var $progress;     // progress bar element
var $fileUpload;   // <input type="file"> element
var $imgUploadBtn; // button element that triggers the uploading of file(s)

function init(fileuploadEl, uploadBtnEl) {
    $progress = $('#progress');
    $fileUpload = $(fileuploadEl);
    $imgUploadBtn = $(uploadBtnEl);
}

/**
 * Uploads selected image files to the server, the uploaded image url is passed as an argument into the done function callback
 * @param  {string}   url    url for handling the image upload
 * @param  {[type]}   start  callback function that runs on start of upload
 * @param  {Function} done  callback function that runs on completion of image(s) upload
 */
function upload(url, start, done) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $fileUpload.fileupload({
        dataType: 'json',
        url: url,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        start: function(e, data) {
            $progress.removeClass('hidden');
            $imgUploadBtn.addClass('hidden');

            start.call(this, e, data);
        },
        done: function(e, data) {
            $progress.addClass('hidden');
            $imgUploadBtn.removeClass('hidden');
            var imgFile = data.result.files[0];
            //append current timestamp to background image filename to avoid browser caching
            var imgUrl = imgFile.url + '?' + (new Date()).toISOString().replace(/[^0-9]/g, '');

            done.call(this, e, data, imgUrl, imgFile);
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $progress.find('.progress-bar').css('width', progress + '%');
        }
    });

    // show error messages, if any
    $fileUpload.bind('fileuploadprocessfail', function (e, data) {
        window.alert(data.files[data.index].error);
    });
}

module.exports = {
    init: init,
    upload: upload
};