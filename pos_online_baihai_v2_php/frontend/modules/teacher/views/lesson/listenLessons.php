<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-9-18
 * Time: 下午2:34
 */
/* @var $this yii\web\View */  $this->title="教师听课";
$publicResources = Yii::$app->request->baseUrl;
;
$this->registerJsFile($publicResources . '/js/My97DatePicker/WdatePicker.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/jquery-ui.min.js'.RESOURCES_VER);
$this->registerJsFile($publicResources . '/js/ztree/jquery.ztree.all-3.5.min.js'.RESOURCES_VER);
$this->registerCssFile($publicResources . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
?>
<script type="text/javascript">
    $(function () {
        $('h3.Signature i').editPlus();
        $('.newBtnJs').click(function () {
            /*安排听课的弹窗*/
            $('#hear').dialog({
                autoOpen: false,
                width: 600,
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",

                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                var chapterID = $('#point_chapt .hidVal').val();
                                var teacherID = $("#teacherID").val();
                                var title = $("#title").val();
                                var joinTime = $("#joinTime").val();
//                                var joinUsers=$("[name='joinUsers[]']").val();
                                var joinUsers = $("#joinUsers").find("input");
                                var array = [];
                                joinUsers.each(function (index, el) {
                                    var obj = $(el).val();
                                    array.push({"id": obj});
                                });
                                var joinUsersArray = {"users": array};
                                $.post("<?php echo url('teacher/lesson/add-listen-lessons')?>", {chapterID: chapterID, teacherID: teacherID, title: title, joinTime: joinTime, joinUsers: JSON.stringify(joinUsersArray)}, function (result) {
                                    popBox.alertBox(result.success);
                                    location.reload();
                                })


                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#hear").dialog("open");
            //event.preventDefault();
            return false;


        });

        $('.class_time h4 i').live("click", function () {
            /*修改听课计划弹窗*/
            $('#hear').dialog({
                autoOpen: false,
                width: 500,
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: "确定",

                        click: function () {
                            if ($('#mySchoolPop .text').val() == 1) {
                                $(this).dialog("close");
                            }
                            else {
                                //alert(4545)
                            }

                        }
                    },
                    {
                        text: "取消",

                        click: function () {
                            $(this).dialog("close");
                        }
                    }

                ]
            });
            $("#hear").dialog("open");
            //event.preventDefault();
            return false;
        })
    })


</script>


<!--主体内容开始-->


<div class="currentRight grid_16 push_2 hear">
<div class="notice ">
    <div class="noticeH clearfix noticeB">
        <h3 class="h3L">听课安排</h3>

        <div class="fr">
            <select class="select_tab">
                <option value="">所有课程</option>
                <option value="2">我主讲的课程</option>
                <option value="1">我参与的课程</option>
            </select>
          </div>
    </div>
    <hr>
    <div class="tab">
        <div class="tchHear tchone on teaching">
            <?php echo $this->render("_teaching_list_view", array("teachingListenList" => $teachingListenList, "pages" => $teachingPages)) ?>
        </div>
    </div>
</div>
</div>

<!--主体内容结束-->
<style type="text/css">
    .fot_z span {
        font-size: 14px;
    }
</style>



<script type="text/javascript">
    //点击更多显示全部
    $(function () {
        $('.morejs').live('click', function () {
            $('.morehide').show();
        });
        $('.class_time .click_btn').live('mouseover', function () {
            if($(this).find("i").html()!=0){
                $(this).siblings('.tch_popo').show();
            }


        });


        $('.select_tab').change(function () {
            var val = $('.select_tab').val();
            if (val == "我主讲的课程") {
                $('.tab .tchone').addClass('on');
                $('.tab .tchone').removeClass('hide');
                $('.tab .tchtow').removeClass('on');
                $('.tab .tchtow').addClass('hide');


            }
            if (val == "我参与的课程") {
                $('.tab .tchtow').addClass('on');
                $('.tab .tchtow').removeClass('hide');
                $('.tab .tchone').removeClass('on');
                $('.tab .tchone').addClass('hide');
            }


        });
      $(".select_tab").change(function(){
               var queryType=$(this).val();
          $.post('<?php echo url("teacher/lesson/listen-lessons")?>',{queryType:queryType},function(result){
              $(".teaching").html(result);
          })
      })
    });

    var obj = $(".teaching").find(".class_B");
    var array = [];
    obj.each(function (index, el) {
        var result = $(el).find(".class_time").find("dt").find("span").text();
        if ($.inArray(result, array) == -1) {
            array.push(result);
        }
        else {
            $(el).find(".class_time").find("dt").find("span").hide();
        }

    });
    /*知识数*/
    $(function () {
        var id_arr = [];//后台要的id
        var setting = {
            check: {enable: true, chkboxType: {"Y": "", "N": ""} },
            data: {simpleData: {    enable: true} },
            callback: {onCheck: zTreeOnCheck},
            view: {showIcon: false, showLine: false}
        };

        var zNodes = [
            { id: 1, pId: 0, name: "语文" },
            { id: 11, pId: 1, name: "拼音"},
            { id: 111, pId: 11, name: "声母"},
            { id: 112, pId: 11, name: "韵母"},
            { id: 113, pId: 11, name: "语法"},
            { id: 12, pId: 1, name: "标点符号"},
            { id: 13, pId: 1, name: "造句"},
            { id: 14, pId: 1, name: "语法"},
        ];

        $.fn.zTree.init($("#treeList"), setting, zNodes);

        //点击树上的checkbox
        function zTreeOnCheck(event, treeId, treeNode) {
            if (treeNode.checked == true) {
                $('#point_chapt .pointArea').show();
                $('#point_chapt .labelList').append('<li val="' + treeNode.id + '"  index="' + treeNode.tId + '">' + treeNode.name + '</li>');
                id_arr.push(treeNode.id);
                $('#point_chapt .hidVal').val(id_arr);
                return false;
            }
            else {
                $('#point_chapt .labelList li[index=' + treeNode.tId + ']').remove();
                id_arr.remove(treeNode.id)//base.js中定义的方法
                $('#point_chapt .hidVal').val(id_arr);
                return false;
            };
        }
    })
</script>
<style type="text/css">
    .on {
        display: block;
    }

</style>
</body>
</html>

