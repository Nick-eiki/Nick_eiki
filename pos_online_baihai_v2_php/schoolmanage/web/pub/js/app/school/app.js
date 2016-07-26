require.config({
    baseUrl: '/pub/js/',
    paths: {
        jquery: 'jquery',
        school_mag:'app/school/school_mag'
    }
});
require(['school_mag']);
