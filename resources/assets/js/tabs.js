'use strict';

var helper = require('./helper');
var tabsEls;

function init() {
    tabsEls = document.querySelectorAll('.tabs');
    Array.prototype.forEach.call(tabsEls, function(tabsEl) {
        initIndicator(tabsEl);
    });
}

function initIndicator(tabsEl) {
    var indicatorEl = tabsEl.querySelector('.tabs__indicator');
    var activeTabEl = tabsEl.querySelector('.tab--active');
    var tabWidth = activeTabEl.offsetWidth + 'px';

    indicatorEl.style.width = tabWidth;
    console.log(helper.getVendorPrefix());
}

module.exports = {
    init: init
};