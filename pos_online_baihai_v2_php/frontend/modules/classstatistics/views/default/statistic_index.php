<!doctype html>
<html id="html">

<head>
    <meta charset="utf-8">
    <title>学校-统计-首页</title>
    <link href="/dev/css/base.css" rel="stylesheet" type="text/css">
    <link href="/dev/css/sUI.css" rel="stylesheet" type="text/css">
    <link href="/dev/css/statistic.css" rel="stylesheet" type="text/css">
    <link href="/dev/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/dev/js/jquery.js"></script>
    <script type="text/javascript" src="/dev/js/jquery-ui.js"></script>
    <script type="text/javascript" data-main="/dev/js/main" src="/dev/js/require.js"></script>

</head>
<body class="statistic">
<div class="headWrap">
    <div class="col1200">
        <div class="head">
            <h1>学校后台管理中心</h1>

            <div class="userCenter">
                <div class="userChannel">
                    <img src="../dev/images/head_70.png" style="vertical-align: middle;">张三丰 <a class="logoff"
                                                                                                href="javascript:;">退出</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col1200 school_name">
    <h3>北京第三十五中学</h3>
</div>
<div class="class_nav col1200">
    <div class="class_nav_opacity"></div>
    <ul class="class_nav_list clearfix">
        <li class="ac"><a href="/exam/default/index">考务管理</a></li>
        <li><a href="javascript:;">人员管理</a></li>
        <li><a href="javascript:;">组织管理</a></li>
        <li><a href="javascript:;">公示管理</a></li>
        <li><a href="/statistics/default/index">学校统计</a></li>
    </ul>
</div>
<div class="main col1200 clearfix statistic_index" id="requireModule" rel="app/statistic/statistic_index">
    <div class="aside col260 no_bg  alpha">
        <div class="asideItem">
            <div class="sel_classes">
                <div class="pd15">
                    <h5>高中部</h5>
                    <button id="sch_mag_classesBar_btn" type="button" class="bg_white icoBtn_wait"><i></i>更换</button>
                    <div id="sch_mag_homes" class="sch_mag_homes pop">
                        <dl>
                            <dt class="schoolLevel cur">
                                <a href="/statistics/default/index?schoolLevel=20202&gradeId=">初中部</a>
                            </dt>
                            <dd data-sel-item class="sel_ac"></dd>
                            <dd data-sel-item></dd>
                        </dl>
                        <dl>
                            <dt class="schoolLevel cur">
                                <a href="/statistics/default/index?schoolLevel=20203&gradeId=">高中部</a>
                            </dt>
                            <dd data-sel-item class="sel_ac"></dd>
                            <dd data-sel-item></dd>
                        </dl>

                    </div>
                </div>
            </div>
        </div>
        <div class="asideItem">
            <ul class="left_menu">
                <li>
                    <a class="cur"
                       href="/statistics/default/index?schoolLevel=20203&gradeId=1013">
                        高三 （2013级）
                    </a>
                </li>
                <li>
                    <a class=""
                       href="/statistics/default/index?schoolLevel=20203&gradeId=1012">
                        高二 （2014级）
                    </a>
                </li>
                <li>
                    <a class=""
                       href="/statistics/default/index?schoolLevel=20203&gradeId=1011">
                        高一 （2015级）
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container col910 no_bg  omega">
        <div class="sel_test_bar">
            <div class="sUI_formList">
                <div class="row">
                    <div class="form_l">
                        <a class="sel_ac" data-sel-item href="javascript:;">全部考试</a>
                    </div>
                    <div class="form_r">
                        <ul class="testList clearfix">
                            <li><a data-sel-item href="javascript:;">期中考试</a></li>
                            <li><a data-sel-item href="javascript:;">期末考试</a></li>
                            <li><a data-sel-item href="javascript:;">模拟考试</a></li>
                            <li><a data-sel-item href="javascript:;">月考</a></li>
                            <li><a data-sel-item href="javascript:;">会考</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="clearfix" id="answerPage" style="float: left">
            <div class="container">
                <div class="pd25">
                    <div class="sUI_pannel title_pannel">
                        <div class="pannel_l">
                            <h4>2015-2016学年高一（2016级）下学期1月月考（文科）</h4>
                        </div>

                        <div class="pannel_r">
                            <a href="javascript:;">查看统计</a>
                        </div>
                    </div>
                    <div class="sch_subject_list  clearfix">
                        <ul class="subList_con">
                            <li class="all_subject">考试科目:</li>
                            <li><a href="javascript:;">语文</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container sch_mag_con">
                <div class="pd25">
                    <div class="sUI_pannel title_pannel">
                        <div class="pannel_l">
                            <h4>2015-2016学年高一（2016级）下学期1月月考（理科）</h4>
                        </div>

                        <div class="pannel_r">
                            <a href="javascript:;">查看统计</a>
                        </div>
                    </div>
                    <div class="sch_subject_list  clearfix">
                        <ul class="subList_con">
                            <li class="all_subject">考试科目:</li>
                            <li><a href="javascript:;">语文</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container sch_mag_con">
                <div class="pd25">
                    <div class="sUI_pannel title_pannel">
                        <div class="pannel_l">
                            <h4>2015-2016学年高一（2016级）下学期4月月考（文科）</h4>
                        </div>

                        <div class="pannel_r">
                            <a href="javascript:;">查看统计</a>
                        </div>
                    </div>
                    <div class="sch_subject_list  clearfix">
                        <ul class="subList_con">
                            <li class="all_subject">考试科目:</li>
                            <li><a href="javascript:;">语文</a></li>
                            <li><a href="javascript:;">数学</a></li>
                            <li><a href="javascript:;">英语</a></li>
                            <li><a href="javascript:;">生物</a></li>
                            <li><a href="javascript:;">物理</a></li>
                            <li><a href="javascript:;">化学</a></li>
                            <li><a href="javascript:;">地理</a></li>
                            <li><a href="javascript:;">政治</a></li>
                            <li><a href="javascript:;">历史</a></li>
                            <li><a href="javascript:;">信息技术</a></li>
                            <li><a href="javascript:;">理综</a></li>
                            <li><a href="javascript:;">文综</a></li>
                            <li><a href="javascript:;">蒙古语文</a></li>
                            <li><a href="javascript:;">汉语</a></li>
                            <li><a href="javascript:;">俄语</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container sch_mag_con">
                <div class="pd25">
                    <div class="sUI_pannel title_pannel">
                        <div class="pannel_l">
                            <h4>2015-2016学年高一（2016级）下学期4月月考（理科）</h4>
                        </div>

                        <div class="pannel_r">
                            <a href="javascript:;">查看统计</a>
                        </div>
                    </div>
                    <div class="sch_subject_list  clearfix">
                        <ul class="subList_con">
                            <li class="all_subject">考试科目:</li>
                            <li><a href="javascript:;">数学</a></li>
                            <li><a href="javascript:;">英语</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container sch_mag_con">
                <div class="pd25">
                    <div class="sUI_pannel title_pannel">
                        <div class="pannel_l">
                            <h4>2015-2016学年高一（2016级）上学期1月月考</h4>
                        </div>

                        <div class="pannel_r">
                            <a href="javascript:;">查看统计</a>
                        </div>
                    </div>
                    <div class="sch_subject_list  clearfix">
                        <ul class="subList_con">
                            <li class="all_subject">考试科目:</li>
                            <li><a href="javascript:;">语文</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="page">
            </div>
        </div>


    </div>
    <br>
</div>

</body>
</html>
