require.config({
    baseUrl: '../dev/js/',
    paths: {
        jquery: 'jquery',
        answer_questions:'app/teacher/answer_questions'
    }
});
require(['answer_questions']);

