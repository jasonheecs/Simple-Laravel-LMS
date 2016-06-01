'use strict';

/* globals hljs */

var $ = require('jquery');
require('highlight');

function init() {
    hljs.configure({useBR: true});

    $('pre').each(function(i, block) {
        hljs.highlightBlock(block);
  });
}

module.exports = {
    init: init
};