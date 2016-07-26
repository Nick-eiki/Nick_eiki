require.config({
    baseUrl: '../dev/js/',
    paths: {
        jquery: 'jquery',
        teacher_integral:'app/teacher/teacher_integral'
    }
});
require(['teacher_integral']);
