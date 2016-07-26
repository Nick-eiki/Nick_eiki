require.config({
    baseUrl: '../dev/js/',
    paths: {
        jquery: 'jquery',
        teacher_seed_message:'app/teacher/teacher_seed_message'
    }
});
require(['teacher_seed_message']);
