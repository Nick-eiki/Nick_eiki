require.config({
    baseUrl: '../dev/js/',
    paths: {
        jquery: 'jquery',
        teacher_MyMessage:'app/teacher/teacher_MyMessage'
    }
});
require(['teacher_MyMessage']);
