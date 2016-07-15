var course = require('./course');
var lesson = require('./lesson');
var tabs = require('./tabs');
var user = require('./user');

document.addEventListener('DOMContentLoaded', function() {
    course.init();
    lesson.init();
    tabs.init();

    switch(document.body.id) {
        case 'js-create-course-page':
            course.create.init();
            break;
            
        case 'js-user-page':
            user.edit.init();
            break;

        case 'js-create-user-page':
            user.create.init();
            break;
    }
});
