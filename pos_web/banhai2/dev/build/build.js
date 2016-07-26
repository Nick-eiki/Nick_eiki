({
    appDir: '../',                  //相对build.js的当前路径的所属地址
    baseUrl: './js',                //定位到appDir后，找到js脚本相对页面地址的位置
    dir: '../../dist',             //生成的文件地址
    modules: [
        {
            name: 'app/classes/classes_index',
            exclude: ['jquery'],
            exclude: ['jqueryUI']
        }
        //{
        //    name: 'app/classes/classes_file',
        //    exclude: ['jquery'],
        //    exclude: ['jqueryUI']
        //}
    ],
    fileExclusionRegExp: /^(r|build)$/,
    optimizeCss: 'standard', /* none|standard|standard.keepLines|standard.keepComments|standard.keepComments.keepLines */
    removeCombined: true,

    //路径配置,需要包含所有模块中用的资源，可以从common.js中取
    paths: {
        /*库依赖*/
        "jquery":"jquery",
        "jqueryUI":"jquery-ui",
        "jquery_sanhai":"lib/jquery.sanhai",
        "base":"module/base",
        "popBox":"module/popBox",
        //"echarts":"lib/echarts",
        'userCard':"module/userCard",
        "sanhai_tools":"module/sanhai_tools",
        'classes_answering_question':'app/classes/classes_answering_question',
        'classes_file':'app/classes/classes_file',
        'jQuery_cycle':'lib/jQuery_cycle',
        'FlexoCalendar':"module/FlexoCalendar"
        //text: "plugin/require.text",    //text!text_path/hello.tpl
        //css: "plugin/require.css",      //css!css_path/swiper.css
        //text_path: "templates",
        //css_path: "../css"
    },
    //map  建立一个模块到其它模块的ID地图
    //map: {
    //    '*': {
    //        'text': 'plugin/require.text',
    //        'css': 'plugin/require.css'
    //    }
    //},
    shim:{
        jquery:{
            exports:'$'
        },

        underscore: {
            exports: '_'
        },
        domReady: {
            exports: "domReady"
        },

        template: {
            exports: "template"
        },

        lazyload: ['jquery'],

        bootstrap: ['jquery'],

        swiper: {
            deps: ['jquery'],
            exports: "swiper"
        },
        "validationEngine":{
            deps:["jquery"],
            exports:"validationEngine"
        },
        "validationEngine_zh_CN":{
            deps:["jquery"],
            exports:"validationEngine_zh_CN"
        },
        "jQuery_cycle":["jquery"],
        "FlexoCalendar":["jquery"],

    }


    //packages: []  配置CommonJS包，

    //mainConfigFile:"",              配置文件地址。默认所有的优化配置都在命令行或者配置文件里，而通过requirejs的data-main引入配置文件的方式是不起作用的。当然，如果你不想重复声明配置，可以直接通过这个参数指向data-main的文件。文件中的第一个requirejs({})，require({})，requirejs.config({})或者require.config({})方法调用会被用到。在2.1.10版本中，mainConfigFile可以是数组，并且后指定的值会覆盖先指定的值。  如果mainConfigFile没有声明，shim配置就必须声明。

    //optimize: “uglify”    默认会压缩所有js文件，closure|none

    //skipDirOptimize: false    2.1.2中提到，如果使用dir作为输出目录，优化器会优化输出目录的所有JS（包括没有在modules配置中声明的）。当然，如果没有在modules里面声明的JS文件在生成过后不会被使用你可以跳过这些文件，以加快生成速度。将该参数设置为true来跳过这些不用被生成的JS文件。

    //normalizeDirDefines: “skip”   2.1.11中：如果dir被声明且不为”none”，并且skipDirOptimize 为false，通常所有的JS文件都会被压缩，这个值自动设置为”all”。为了让JS文件能够在压缩过正确执行，优化器会加一层define()调用并且会插入一个依赖数组。当然，这样会有一点点慢如果有很多文件或者有大文件的时候。所以，设置该参数为”skip”这个过程就不会执行，如果optimize设置为”none”也一样。如果你想手动设置值的话： 1）优化后：如果你打算压缩没在modules声明的JS文件，在优化器执行过后，你应该设置这个值为”all”   2）优化中：但在动态加载过后，你想做一个会文件优化，但不打算在动态加载这些文件可以设置成”skip”  最后：所有生成的文件（无论在不在modules里声明过）自动标准化


    /*
    uglify: {

        toplevel: true,

        ascii_only: true,

        beautify: true,

        max_line_length: 1000,

        defines: {

            DEBUG: [“name”, “false”]
        },
        no_mangle: true
    }
     如果用UglifyJS做优化，这些配置参数会被传递到UglifyJS，详情见：https://github.com/mishoo/UglifyJS
    */

    /*
     Uglify2: {

     output: {

     beautify: true

     },

     compress: {

     sequences: false,

     global_defs: {

     DEBUG: false

     }

     },

     warnings: true,

     mangle: false

     }
     如果用UglifyJS2来优化，这些配置参数会被传入UglifyJS2
     */

    /*
     closure: {

     CompilerOption: {},

     CompilationLevel: “SIMPLE_OPTIMIZATIONS”,

     loggingLevel: “WANING”

     }
     如果用Closure Compiler优化，这个参数可以用来配置Closure Compiler，详细请看Closure Compiler的文档
     */

})


























