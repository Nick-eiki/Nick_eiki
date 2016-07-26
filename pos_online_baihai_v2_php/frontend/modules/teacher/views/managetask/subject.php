<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 14-12-11
 * Time: 下午4:53
 */
use yii\helpers\Url;

/* @var $this yii\web\View */  $this->title="教师-组卷-筛选题目";
$backend_asset = publicResources();
;
$this->registerJsFile($backend_asset . '/js/ztree/jquery.ztree.all-3.5.min.js');
$this->registerCssFile($backend_asset . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);
?>
<div class="currentRight grid_16 push_2 filterSubject">
    <div class="noticeH clearfix">
        <h3 class="h3L">题目筛选</h3>
    </div>
    <hr>
    <ul class="stepList clearfix">
        <li class="over"><span>试卷结构</span><i class="step01"></i></li>
        <li class="ac"><span>筛选题目</span><i class="step02"></i></li>
        <li class=""><span>设定分值</span><i class="step03"></i></li>
    </ul>
    <br>

    <div class="searchBar">
        <?php echo Html::form('', 'post', ['id' => 'searchForm']) ?>
        <ul class="form_list">
            <li>
                <div class="formL">
                    <label><i></i>题目关键字：</label>
                </div>
                <div class="formR">
                    <input type="text" class="text" name="key">
                    <input type="button" class="btn" id="actionSearch" value="搜索">
                </div>
            </li>
        </ul>
        <?php echo Html::endForm() ?>
    </div>
    <br>
    <br>

    <div class="schResult">
        <h3>搜索结果:</h3>
        <ul id="showSearchKey" class="form_list" style="display: none">
            <li>
                <div class="formL">
                    <label>搜索条件：</label>
                </div>
                <div class="formR  schTxtArea">
                    <p><span id="searchKey"></span></p>
                </div>
            </li>
        </ul>
        <h4 id="searchcount">共有题目0道题如下:</h4>

        <div class="paperStructure clearfix">
            <h5>试卷中的题目:</h5>
            <ul class="paperItemList">
                <?php foreach ($queryType as $key => $item) { ?>
                    <li tab="Q_<?php echo $item->typeId ?>" class="<?php echo $key == 0 ? 'ac' : ''; ?>"
                        typeId="<?php echo $item->typeId ?>"><?php echo $item->typeName ?></li>
                <?php } ?>
            </ul>
            <div class="paperItemConts">
                <?php foreach ($queryType as $key1 => $item1) {
                    ?>
                    <ul id="Q_<?php echo $item1->typeId ?>" class="clearfix <?php echo $key1 == 0 ? '' : 'hide'; ?>">
                        <?php foreach ($item1->questions as $itemqu) { ?>
                            <li val="<?php echo $itemqu->id; ?>"><?php echo $itemqu->id; ?><?php echo $itemqu->name; ?></li>
                        <?php } ?>
                    </ul>
                <?php
                } ?>
                <div class="demoBar hide">
                    <span class="close">×</span>
                </div>
            </div>

        </div>
        <div id="uploadId">
            <div class="schResult">
                <div class="testPaperView pr">
                    <div class="paperArea">

                        <?php foreach ($list as $key => $item) {
                            echo $this->render('_itemProblem', array('item' => $item));
                        } ?>
                    </div>
                </div>
            </div>
  <?php
                 echo \frontend\components\CLinkPagerExt::widget( array(
                       'pagination'=>$pages,
                        'updateId' => '#uploadId',
                        'maxButtonCount' => 10
                    )
                );
                ?>
        </div>
    </div>

    <p class="tc bottomBtnBar">
        <button type="button" class="btn preStepBtn">上一步</button>
        <button type="button" class="btn nextStepBtn">下一步</button>
    </p>
</div>

<script>
    $('#actionSearch').click(function () {

        $searchForm = $('#searchForm');
        $.post($searchForm.attr('action'), $searchForm.serialize(), function (html) {
            $('#uploadId').html(html);
        });
    });

    $('.btn.nextStepBtn').click(function () {
        $qus = [];
        $('.paperItemList li[tab]').each(function (index) {
            pid = $(this).attr('tab');
            tabid = $(this).attr('typeId');
            value = [];
            $("#" + pid + " li[val]").each(function () {
                value.push($(this).attr('val'));
            });
            $qus.push({"name": 'items[' + tabid + ']', "value": value.join(',')});
        });

        $.post('<?php echo Url::to(['SaveSubject','homeworkId'=>app()->request->getParam('homeworkId')]) ?>', $.param($qus), function (result) {
            if (result.success) {
                window.location.href = "<?php  Url::to(["index"])  ?>";
            } else {
                popBox.errorBox('保存失败');
            }

        });
    });

    $(function () {
//已选题目 选项卡
        $('.paperItemList li').click(function () {
            var index = $(this).index();
            $(this).addClass('ac').siblings().removeClass('ac');
            $('.paperItemConts ul').eq(index).show().siblings().hide();
        });
        $('.openAnswerBtn').click(function () {
            $(this).parents('.testpaper').children('.answerArea').toggle();
        });

//试卷中的题目 fixed
        var divTop = $('.paperStructure').offset().top;
        var divW = $('.paperStructure').width();
        var divH = $('.paperStructure').outerHeight() + 20;
        var windowScrollTop;
        $(window).scroll(function () {
            windowScrollTop = $(window).scrollTop();
            if (windowScrollTop >= divTop) {
                $('.paperStructure').css({'position': 'fixed', 'top': 0, 'width': divW, 'z-index': 100});
                $('.paperStructure').next().css({'padding-top': divH + 'px'})
            }
            else {
                $('.paperStructure').css({'position': 'static'});
                $('.paperStructure').next().css({'padding-top': 0})
            }
        });

//组卷按钮
        $('.paper .addBtn').live('click', function () {
            var id = $(this).attr('id');
            var pid = $(this).attr('pid');
            var index = $('#' + pid).index();
            var tab = "";
            $('.paperItemList li').each(function () {//判断是否有此题型
                if ($(this).attr('tab') == pid) tab = true;
            });
            if (tab == true) {
                $(this).removeClass('addBtn').addClass('delBtn').text('删除');
                $('.paperItemList li').eq(index).addClass('ac').siblings().removeClass('ac');
                $('#' + pid).show().siblings().hide();
                $('#' + pid).append('<li val="' + id + '">' + id + '</li>');
            } else {
                popBox.errorBox('本试卷没有该题型!!')
            }
        });

        $('.paper .delBtn').live('click', function () {
            var id = $(this).attr('id');
            $(this).removeClass('delBtn').addClass('addBtn').text('组卷');
            var pid = $(this).attr('pid');
            var index = $('#' + pid).index();
            $('.paperItemList li').eq(index).addClass('ac').siblings().removeClass('ac');
            $('#' + pid).show().siblings().hide();
            $('#' + pid + ' li').each(function (index, element) {
                if ($(this).text() == id) $(this).remove();
            });
        });

//点击题目id,显示题型
        $('.paperItemConts li').live('click', function () {
            $('.paperItemConts .demoBar').show();
        });
        $('.demoBar .close').click(function () {
            $(this).parent().hide();
        });
        $('#searchcount').html('共有题目<?php echo  $pages->getItemCount() ?>道题如下:');
    })

</script>

