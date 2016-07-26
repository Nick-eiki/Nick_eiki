
require.config({
    urlArgs: "v=" + (new Date()).getTime(),
    baseUrl: "../dev/js",
    paths: {
        "domReady":"domReady",
        "jquery":"jquery",
        "jqueryUI":"jquery-ui",
        "jquery_sanhai":"lib/jquery.sanhai",
        "base":"module/base",
        "popBox":"module/popBox",
        "echarts":"lib/echarts/echarts",
        "sanhai_tools":"module/sanhai_tools",
        'userCard':"module/userCard",
        'validationEngine':'lib/jquery.validationEngine.min',
        'validationEngine_zh_CN':'lib/jquery.validationEngine_zh_CN',
        'jQuery_cycle':'lib/jQuery_cycle',
        "FlexoCalendar":"module/FlexoCalendar"
    },
    shim:{
        "validationEngine":{
            deps:["jquery"],
            exports:"validationEngine"
        },
        "validationEngine_zh_CN":{
            deps:["jquery"],
            exports:"validationEngine_zh_CN"
        },
        "jQuery_cycle":["jquery"],
        "FlexoCalendar":["jquery"]

    }
});

require(['domReady','base'], function(domReady,base){
    domReady(function(){
        var requireModule=document.getElementById('requireModule').getAttribute('rel');
        require([requireModule]);
    })
});



