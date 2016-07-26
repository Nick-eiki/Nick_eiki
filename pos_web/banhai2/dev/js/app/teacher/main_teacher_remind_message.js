require.config({
    baseUrl: '../dev/js/',
    paths: {
        jquery: 'jquery',
        teacher_remind_message:'app/teacher/teacher_remind_message'
    }
});
require(['teacher_remind_message']);
