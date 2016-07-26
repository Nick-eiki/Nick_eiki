  <?php /* @var $this yii\web\View */  $this->title="搜索试题";?>

    <script>
        $(function(){
            $('.openAnswerBtn').click(function(){
                $(this).parents('.testpaper').children('.answerArea').toggle();
            });
            //搜索按钮切换
            $('.terrace_btn_js span').bind('click',function(){
                $(this).addClass('s_btn').siblings('span').removeClass('s_btn');

            })
        })
    </script>

<!--主体内容开始-->

        <div class="deta_de">
            <a href="#">首页</a>&gt;&gt;<a href="#" class="this">题目详情页</a>
        </div>
        <div class="class_c clearfix tch">
            <div class="currentLeft grid_16 topic_magmt">
                <div class="noticeH clearfix">
                    <h3 class="h3L">共有题目<em><?php echo  $pages->totalCount;?></em>道题如下:</h3>
                </div>
                <hr>
                <div class="schResult">
                    <?php foreach($tagsModel as $key=>$item){
                        echo $this->render('//publicView/paper/_showItemProblem',['item'=>$item]);
               }?>
                </div>
                    <?php
                     echo \frontend\components\CLinkPagerExt::widget( array(
                           'pagination'=>$pages,
//                            'updateId' => '#updateId',
                            'maxButtonCount' => 9
                        )
                    );
                    ?>
            </div>
待定
        </div>



<!--主体内容结束-->
