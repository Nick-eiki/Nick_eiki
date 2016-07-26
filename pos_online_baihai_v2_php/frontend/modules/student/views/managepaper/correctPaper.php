<?php
/**
 * Created wangchunlei
 * User: Administrator
 * Date: 14-12-2
 * Time: 下午3:09
 */
/* @var $this yii\web\View */  $this->title="批阅试卷";
?>
<div class="currentRight grid_16 push_2 test_correctPaper">
    <div class="noticeH clearfix">
        <h3 class="h3L">教师判卷</h3>
    </div>
    <hr>
    <div class="correctPaper">
        <h5><?php echo $testResult->studentName."的".$testResult->name?></h5>
        <div class="pageCount"></div>
        <div class="correctPaperSlide">
            <div class="testPaperWrap mc">
                <ul class="testPaperSlideList slid">
                    <?php foreach($testResult->testCheckInfoS as $v){?>
                        <li><img src="<?php echo publicResources().$v->imageUrl?>" width="830" height="508"  alt=""/>
                            <input type="hidden" value="<?php echo $v->tID?>">
                        </li>
                    <?php }?>
                </ul>
            </div>
            <a href="javascript:" id="prevBtn" class="correctPaperPrev">上一页</a> <a href="javascript:;" id="nextBtn" class="correctPaperNext">下一页</a>
            <div class="slideControlPanel">
                <div class="setBg" title="设定背景">
                    <div class="pop"> <span></span> <a class="red"></a> <a class="pink"></a> <a class="yellow"></a> <a class="blue"></a> <a class="green"></a> </div>
                </div>
                <div class="setFont"><span class="tit"></span>
                    <div class="pop"> <span></span> <a>12</a> <a>14</a> <a>16</a> </div>
                </div>
                <div class="mySelect correctSelect"> <span class="title">点拨</span>
                    <ul class="selectList pop">
                        <li><a href="javascript:;">点拨</a></li>
                        <li><a href="javascript:;">评分</a></li>
                    </ul>
                    <a class="openBtn" href="javascript:;"></a> </div>
                <div class="comment">
                    <input class="text" type="text">
                </div>
                <div class="score hide">
                    <input class="text" type="text">
                </div>
                <div class="ok">确定</div>
                <div class="finish">保存本页批改</div>
                <div id="tipsPrev" class="tipsPrev"></div>
                <div class="play"></div>
                <div id="tipsNext" class="tipsNext"></div>
                <div class="hideText">隐藏批语</div>
                <div class="help">？</div>
            </div>
            <br>
            <div class="tc bottomBtnBar"><button type="button" class="bg_green correctEndBtn">批改完成</button></div>

        </div>
    </div>
</div>
<!--弹出框-->
<!--阅卷完成-->
<div class="popBox correctEndBox clearfix" title="阅卷完成">
    <h5>您已经完成本题答案的批阅</h5>
    <p>得分:<span id="score"></span>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;总排名:<span id="ranking"></span></p>

</div>
<script>
    $(function(){

//判卷
        var tpArg={on:false,bg:"#fff",fontSize:14,testPaperSize:0,page:0,tipsTxt:null};

        function current(){
            $('.testPaperSlideList li').eq(tpArg.page).addClass('current').siblings().removeClass('current')
        }
        current();

        tpArg.testPaperSize=$('.testPaperSlideList li').size();//翻页
        $('.pageCount').text('共'+tpArg.testPaperSize+'页,第'+(tpArg.page+1)+'页');
        $('.testPaperSlideList').css("width",tpArg.testPaperSize*800);
        $('#nextBtn').click(function(){
            if(tpArg.page<tpArg.testPaperSize-1){
                tpArg.page++;
                $('.testPaperSlideList').animate({"left":-800*tpArg.page});
                $('.pageCount').text('共'+tpArg.testPaperSize+'页,第'+(tpArg.page+1)+'页');
                current();
            }
        });
        $('#prevBtn').click(function(){
            if(tpArg.page>0){
                tpArg.page--;
                $('.testPaperSlideList').animate({"left":-800*tpArg.page});
                current();
            }
        });

        $('.setBg,.setFont').click(function(){//打开弹出框
            $(this).children('div').show();
            return false;
        });
        $('.setBg div a').click(function(){//设定背景
            $(this).parent().hide();
            $('.setBg').css('background-color',$(this).css('background-color'));
            tpArg.bg=$(this).css('background-color');
            return false;
        });
        $('.setFont div a').click(function(){//设定字号
            $(this).parent().hide();
            $('.setFont').css("background-image","none").children('.tit').text($(this).text());
            tpArg.fontSize=$(this).text();
            return false;
        });

        $('.comment .text').placeholder({'top':"1px",'value':"评语"});

        $('.correctSelect').mySelect({fn:function(){//下拉菜单
            if($('.correctSelect .title').text()=="评分"){
                $('.comment .text').width("88px");
                $('.score').show();
                $('.score .text').placeholder({'top':"1px",'value':"分数"});
            }
            if($('.correctSelect .title').text()=="点拨"){
                $('.comment .text').width("150px");
                $('.score').hide();
            }
        }});



        $('.slideControlPanel .ok').click(function(){
            if($('.score .text').val()!=""){
                tpArg.tipsTxt='<span class="scoreTxt">'+$('.comment .text').val()+'</span><br><strong class="scoreVal">'+$('.score .text').val()+'</strong>分';
            }
            else{
                tpArg.tipsTxt='<span class="scoreTxt">'+$('.comment .text').val()+'</span><br><strong class="scoreVal"></strong>';
            }
            tpArg.on=true;
        });

        $('.testPaperSlideList li').click(function(ev){//添加tips
            var tipLeft=ev.clientX-$(this).offset().left+$(document).scrollLeft();
            var tipTop=ev.clientY-$(this).offset().top+$(document).scrollTop();
            if(tpArg.on==true && tpArg.tipsTxt!=""){
                $(this).append('<div class="tips" style="top:'+tipTop+'px;left:'+tipLeft+'px; background:'+tpArg.bg+';font-size:'+tpArg.fontSize+'px">'+tpArg.tipsTxt+'<span class="removeBtn hide">×</span></div>');

                var TipsJson={id:null,pid:null,left:tipLeft,top:tipTop,background:tpArg.bg,fontSize:tpArg.fontSize };
                var timer;
                $('.tips').hover(
                    function(){
                        var _this=$(this);
                        clearTimeout(timer);
                        timer=setTimeout(function(){_this.children('.removeBtn').fadeIn()},800)
                    },
                    function(){
                        var _this=$(this);
                        clearTimeout(timer);
                        _this.children('.removeBtn').fadeOut()
                    }
                );


                $('.tips').drag().children('.removeBtn').click(function(){
                    $(this).parent().remove();
                });
                $('.slideControlPanel input:text').val("").next().show();
                tpArg.on=false;
            }
        });






        /*阅卷完成popBox*/


        $('.correctEndBox').dialog({
            autoOpen: false,
            width:500,
            modal: true,
            resizable:false,
            buttons: [
                {
                    text: "确定",
                    class:"okBtn",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                },
                {
                    text: "取消",
                    class:"cancelBtn",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });
//  批改完成
        $( ".correctEndBtn" ).click(function(){
            var testAnswerID="<?php echo app()->request->getQueryParam('testAnswerID')?>";
            var totalScore=0;
            $('li  div.tips').each(function(index,el){
                var scoreVal=$(el).find(".scoreVal").html()==""?0:$(el).find(".scoreVal").html();
                totalScore+=parseInt(scoreVal);
            });
            $.post("<?php echo url('student/managepaper/finish-correct')?>",{"testScore":totalScore,"testAnswerID":testAnswerID},function(result){
                if(result.code==1){
                    $("#score").html(result.data.testScore);
                    $("#ranking").html(result.data.rankingNum);
                    $( ".correctEndBox" ).dialog( "open" );
                }else{
                    popBox.alertBox(result.message);
                }
            });

            return false;
        });





//        保存本页批改
        $(".finish").click(function(){
            var checkInfoArray=[];
            $('li.current div.tips').each(function(index,el){
                var scoreTxt=$(el).find(".scoreTxt").html();
                var scoreVal=$(el).find(".scoreVal").html();
                var style=$(el).attr("style");
                var checkInfo={"style":style,"comments":scoreTxt,"score":scoreVal};
                checkInfoArray.push(checkInfo);
            });
            var checkInfoList={"checkInfoList":checkInfoArray};
            var tID=$("li.current").find("input").val();
            var testAnswerID="<?php echo app()->request->getQueryParam('testAnswerID')?>";
            $.post("<?php echo url('student/managepaper/hold-correct')?>",{"checkInfoJson":JSON.stringify(checkInfoList),"tID":tID,"testAnswerID":testAnswerID},function(result){
                if(result.code){
                    popBox.alertBox(result.message);
                }
                else{
                    popBox.alertBox(result.message);
                }
            })
        })






    })
</script>