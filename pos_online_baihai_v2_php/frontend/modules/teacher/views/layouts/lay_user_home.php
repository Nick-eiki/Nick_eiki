<?php
/**
 * Created by yangjie
 * User: Administrator
 * Date: 15-4-13
 * Time: 上午9:48
 */
use frontend\components\WebDataCache;
use frontend\models\dicmodels\SubjectModel;
use frontend\services\pos\pos_HonorManageService;
use frontend\services\pos\pos_UserSloganService;
use yii\web\View;

/** @var $this yii\web\View */
$this->beginContent('@app/views/layouts/main.php');
$this->blocks['bodyclass'] = "teacher";
$this->registerCssFile(publicResources_new() . '/css/teacher.css'.RESOURCES_VER);
$this->registerCssFile(publicResources_new() . '/js/ztree/zTreeStyle/zTreeStyle.css'.RESOURCES_VER);

$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine.min.js".RESOURCES_VER,['position'=>View::POS_HEAD]);
$this->registerJsFile(publicResources_new() . "/js/jquery.validationEngine-zh_CN.js".RESOURCES_VER, ['position'=>View::POS_HEAD]);
$this->registerJsFile(publicResources_new() . "/js/ztree/jquery.ztree.all-3.5.min.js".RESOURCES_VER,['position'=>View::POS_HEAD]);

$teacherId = app()->request->getParam('teacherId', '');

$honorManage = new pos_HonorManageService();
$queryHonor = $honorManage->queryHonor($teacherId, '', '50302');
$teacherInfo = loginUser()->getUserInfo($teacherId);

//教研组
$group = $teacherInfo->getGroupInfo();

$Slogan = new pos_UserSloganService();
$userSlogan = $Slogan->searchUserSlogan($teacherId);

?>

<div class="cont24 homepage teacher_home">
    <div class="grid_24 myInfo">
        <div class="infoBar pr">
            <div class="infoBarBg"></div>
            <div class="infoCont">
                <div class="imgBG"><img data-type="header"
                                        src="<?php echo publicResources() . WebDataCache::getFaceIcon($teacherInfo->userID) ?>"
                                        onerror="userDefImg(this);" width="220" height="220" alt=""></div>
                <div class="teacher_title clearfix">
                    <h2><?php echo $teacherInfo->getTrueName(); ?></h2>
                    <span><?php echo isset($userSlogan) ? $userSlogan->userSlogan : ''; ?></span>
                </div>
                <p>
                    学校：<?= WebDataCache::getSchoolName($teacherInfo->schoolID); ?> |
                    班级：<?php  foreach($teacherInfo->getUserClassGroup() as $v){
                      echo  WebDataCache::getClassesName($v->classID);
                    }  ?> |
                    科目：<?php echo SubjectModel::model()->getSubjectName($teacherInfo->subjectID);
                    ?> |
                    教研组：<?php foreach ($group as $val) {
		                echo $val->groupName .'&nbsp';
                    }
	                ?>
                </p>
<!--                --><?php //if ($teacherId != user()->id) { ?>
<!--                    <a class="btn btn50 w160 bg_green iconBtn sendLetterBtn newBtnJs"-->
<!--                       data_val="--><?php //echo $teacherId . '|' . WebDataCache::getTrueName($teacherId); ?><!--"> <i-->
<!--                            class="btn_ico_letter"></i>私信-->
<!--                    </a>-->
<!--                --><?php //} ?>
            </div>
        </div>
    </div>
    <div class="grid_24 main">
        <?php echo $content ?>
    </div>
</div>

<script>
    $(function () {
        $('.newBtnJs').click(function () {
            var $_this = $(this);
            var data = $_this.attr("data_val");
            var arrinfo = data.split('|');
            var id = arrinfo[0];
            var name = arrinfo[1];
            popBox.private_new_msg([{'id': id, 'name': name}], function () {
                var messageContent = $.trim($('.private_msg_Box textarea').val());
                if (messageContent == "") {
                    popBox.errorBox("内容不能为空!");
                    return false;
                }
                if (messageContent.length > 140) {
                    popBox.errorBox("文字已超出!");
                    return false;
                }
                var url = '<?= url("messagebox/send-message")?>';
                var userId = $('.popCont .sel').val();
                $.post(url, {userId: userId, messageContent: messageContent}, function (result) {
                    if (result.success == true) {
                        $('.private_msg_Box').remove();
                        location.reload();
                    }
                });
            });
            return false;

        })
    });
</script>
<?php $this->endContent() ?>