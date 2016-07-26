<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-12-9
 * Time: 上午11:52
 */
?>
<?php foreach($modelHonorList as $key=>$item){ ?>
    <li>
        <div class="m">
            <strong class="tt"><?php echo $item->honorInfor; ?></strong>
            <a href="javascript:" class="edit" >编辑</a>
            <a href="javascript:;" class="del" delId="<?php echo $item->honorID;?>">删除</a>
        </div>
        <div class="b" style="display:none;">
            <input type="text" class="text text_js">
            <a href="javascript:" class="a_button bg_red ok" editId ="<?php echo $item->honorID;?>">确定</a>
            <a href="javascript:;" class="a_button bg_gray no">取消</a>
        </div>
    </li>
<?php  }?>