'use strict';

var helper = require('./helper');
var throttle = require('lodash/throttle');
var tabsEls;

function Tabs(element) {
    this.element = element;
}

Tabs.prototype.init = function() {
    this.indicatorEl = this.element.querySelector('.tabs__indicator');
    this.initIndicator();

    this.activeTab.classList.remove('hidden');
    this.indicatorEl.style[helper.getPropertyValue('transition')] = helper.getPropertyValue('transform') + ' .25s';
    this.addEventListener();

    window.addEventListener('resize', throttle(this.initIndicator, 250).bind(this));
};

Tabs.prototype.initIndicator = function() {
    this.activeTabEl = this.element.querySelector('.tab--active');
    this.indicatorEl.style.width = this.activeTabEl.offsetWidth + 'px';
    this.activeTab = document.querySelector(this.activeTabEl.querySelector('a').hash);
    this.setIndicatorPos();
};

Tabs.prototype.setIndicatorPos = function() {
    var tabsElRect = this.element.getBoundingClientRect();
    var activeTabElRect = this.activeTabEl.getBoundingClientRect();
    var translate = 'translate3d('+ (activeTabElRect.left - tabsElRect.left) + 'px, '+ (activeTabElRect.top - tabsElRect.top) +'px, 0)';

    this.indicatorEl.style[helper.getPropertyValue('transform')] = translate;
};

Tabs.prototype.addEventListener = function() {
    this.element.addEventListener('click', function(evt) {
        if (evt.target && (helper.matches(evt.target, '.tab') || helper.matches(evt.target, '.tab *'))) {
            var target = evt.target;
            evt.preventDefault();

            if (!target.classList.contains('.tab')) {
                target = findAncestor(target, 'tab');
            }

            target.classList.add('tab--active');
            this.setActiveTabLink(target);
            this.setIndicatorPos();
            this.setActiveTabContent(target);
        }
    }.bind(this));

    function findAncestor (el, cls) {
        while ((el = el.parentElement) && !el.classList.contains(cls));
        return el;
    }
};

Tabs.prototype.setActiveTabLink = function(newTabLink) {
    this.activeTabEl.classList.remove('tab--active');
    this.activeTabEl = newTabLink;
    this.activeTabEl.classList.add('tab--active');
};

Tabs.prototype.setActiveTabContent = function(newTabLink) {
    this.activeTab.classList.add('hidden');
    this.activeTab = document.querySelector(newTabLink.querySelector('a').hash);
    this.activeTab.classList.remove('hidden');
};

function init() {
    tabsEls = document.querySelectorAll('.tabs');
    Array.prototype.forEach.call(tabsEls, function(tabsEl) {
        var tabs = new Tabs(tabsEl);
        tabs.init();
    });
}
module.exports = {
    init: init
};