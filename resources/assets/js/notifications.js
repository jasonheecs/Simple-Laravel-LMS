'use strict';

/**
 * Notifications module for display notifications to the user
 */

var helper = require('./helper');
var CustomEvent = require('./custom-event');

var NOTIFICATION_DURATION = 3500; // how long the notification should show on screen (in ms)
var notifications; // Object to store all the notifications
var notificationCls = 'notification'; // base css class of the notifications

function Notification(msg, status) {
    this.element = document.createElement('li');
    this.status = status || 'default';
    this.msg = msg;
    this.rafID = null;
    this.exitTimestamp = null;
    this.deleteStart = false;

    // Sets a flag for the animation status of the notification element
    this.element.addEventListener('notificationDeleteStart', function() {
        this.deleteStart = true;
    }.bind(this));

    this.element.addEventListener(helper.whichTransitionEvent(), function() {
        // if no flag is present, disable the animation transition and reset the transform matrix of the element.
        if (!this.deleteStart){
            this.element.style[helper.getPropertyValue('transition')] = 'none';
            this.element.style[helper.getPropertyValue('transform')] = '';
            this.element.style[helper.getPropertyValue('transition')] = helper.getPropertyValue('transform') + '.35s';
        }
    }.bind(this));
}

Notification.prototype.init = function() {
    this.element.classList.add(notificationCls);
    this.element.classList.add(notificationCls + '--' + this.status);
    this.element.textContent = this.msg;

    // set a delay before adding the 'show' class because of css transitions
    setTimeout( function() {
        this.element.classList.add(notificationCls + '--show');
    }.bind(this), 50);

    this.exit();
};

Notification.prototype.exit = function() {
    this.rafID = window.requestAnimationFrame(setExitInterval);
    var notificationEl = this;

    function setExitInterval(timestamp) {
        if (!notificationEl.exitTimestamp) {
            notificationEl.exitTimestamp = timestamp;
        }

        if (timestamp - notificationEl.exitTimestamp >= NOTIFICATION_DURATION) {
            notificationEl.element.style[helper.getPropertyValue('transform')] = '';
            notificationEl.element.classList.remove(notificationCls + '--show');
            window.cancelAnimationFrame(notificationEl.rafID);

            // dispatch custom event for notification exit animation
            notificationEl.element.dispatchEvent(new CustomEvent('notificationDeleteStart', {
                bubbles: true,
                cancelable: false,
                detail: {
                    notificationEl: notificationEl.element
                }
            }));

            return;
        }

        notificationEl.rafID = window.requestAnimationFrame(setExitInterval);
    }
};

Notification.prototype.delete = function() {
    // delete notification object when css transition is complete
    var transitionEvent = helper.whichTransitionEvent();
    this.element.addEventListener(transitionEvent, function() {
        if (this.deleteStart){
            this.element.parentNode.removeChild(this.element);
        }
    }.bind(this));
};

function init() {
    notifications = {};
    notifications.element = document.getElementById('notifications');
    notifications.element.addEventListener('notificationDeleteStart', function(evt) {
        var deletedNotification = evt.detail.notificationEl;

        notifications.notificationsList.forEach(function(notification, index) {
            // Get current transform matrix applied to element so we can modify it.
            var currentTransform = helper.getTransform(notification.element);
            var newTransform;

            if (notification.element === deletedNotification) {
                // move notification to be deleted offscreen before deleting it
                newTransform = 'translate3d('+ '101%,' + '0px,' + currentTransform[2] +'px)';
                notification.delete();
            } else {
                // apply animation transforms to remaining notifications
                var newTransformY = -(evt.detail.notificationEl.clientHeight);
                newTransform = 'translate3d('+ currentTransform[0] + 'px,' + newTransformY + 'px,' + currentTransform[2] +'px)';
            }

            notification.element.style[helper.getPropertyValue('transform')] = newTransform;
        });
    });

    notifications.notificationsList = [];
}

function notify(msg, status) {
    if (typeof notifications === 'undefined') {
        init();
    }

    // create notification element
    var notification = new Notification(msg, status);
    notification.init();

    notifications.notificationsList.push(notification);
    notifications.element.appendChild(notification.element);
}

//expose notify method to global window object to allow Blade to flash session messages
window.notify = notify;

module.exports = {
    init: init,
    notify: notify
};