'use strict';

// var helper = require('./helper');

var NOTIFICATION_DURATION = 5000;

var notificationsEl;
var startTimestamp;
var rafID;

function init() {
    notificationsEl = document.getElementById('notifications');
}

function notify(msg, status) {
    if (typeof notificationsEl === 'undefined') {
        init();
    }

    status = status || 'default';

    var notificationEl = document.createElement('li');
    notificationEl.classList.add('notification');
    notificationEl.classList.add('notification--' + status);
    notificationEl.textContent = msg;

    notificationsEl.appendChild(notificationEl);

    setTimeout( function() {
        notificationEl.classList.add('notification--show');
    }, 50);
    
    rafID = window.requestAnimationFrame(setNotificationInterval);

    function setNotificationInterval(timestamp) {
        if (!startTimestamp) {
            startTimestamp = timestamp;
        }

        if (timestamp - startTimestamp >= NOTIFICATION_DURATION) {
            notificationEl.style.background = 'red';
            window.cancelAnimationFrame(rafID);
            return;
        }

        rafID = window.requestAnimationFrame(setNotificationInterval);
    }
}

module.exports = {
    init: init,
    notify: notify
};