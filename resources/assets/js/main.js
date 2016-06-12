var course = require('./course');
var lesson = require('./lesson');
var tabs = require('./tabs');

document.addEventListener('DOMContentLoaded', function() {
    course.init();
    lesson.init();
    tabs.init();
});
