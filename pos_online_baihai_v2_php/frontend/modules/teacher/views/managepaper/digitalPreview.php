<?php
/**
 * Created by wangchunlei
 * User: Administrator
 * Date: 15-4-14
 * Time: 下午4:23
 */
/* @var $this yii\web\View */  $this->title="试卷预览";
 ?>
<div class="grid_19 main_r">
    <div class="main_cont">
        <div class="title"> <a href="#" class="txtBtn backBtn"></a>
            <h4>试卷预览</h4>
            <div class="title_r"> <a href="#" class="btn btn40 bg_blue w120">去组卷</a> </div>
        </div>
        <br>
        <div class="testPaperView pr">
            <h4>试卷名称试卷名称试卷名称试卷名称</h4>
            <div class="subTitle tc">北京 海淀区 一年级 数学 人教版</div>
            <div class="testPaperInfo">
                <p>1.考察知识点</p>
                <p>2.本试卷包含10道题</p>
                <p>3.各题分值情况</p>
            </div>
            <div class="paperArea">
                <div class="paper"><!--选择题-->
                    <button type="button" id="c10" pid="Q_chodddose" class="editBtn addBtn">组题</button>
                    <h5>题目1:</h5>
                    <h6>【2013年】 高考 选择题</h6>
                    <p class="q_content">题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
                    <div class="checkArea">
                        <input type="radio" class="radio" name="aaa">
                        <label>A 备选项1</label>
                        <input type="radio" class="radio"  name="aaa">
                        <label>B 备选项2</label>
                        <input type="radio" class="radio"  name="aaa">
                        <label>C 备选项3</label>
                        <input type="radio" class="radio"  name="aaa">
                        <label>D 备选项4</label>
                    </div>
                    <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span> </div>
                    <div class="answerArea">
                        <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                        <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                    </div>
                </div>
                <hr>
                <div class="paper"><!--选择题-->
                    <h5>题目1:</h5>
                    <h6>【2013年】 高考 选择题</h6>
                    <p class="q_content">题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
                    <div class="checkArea">
                        <input type="checkbox" class="checkbox">
                        <label>A 备选项1</label>
                        <input type="checkbox" class="checkbox">
                        <label>B 备选项2</label>
                        <input type="checkbox" class="checkbox">
                        <label>C 备选项3</label>
                        <input type="checkbox" class="checkbox">
                        <label>D 备选项4</label>
                    </div>
                    <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span> </div>
                    <div class="answerArea hide">
                        <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                        <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                    </div>
                </div>
                <hr>
                <div class="paper"><!--选择题-->
                    <h5>题目1:</h5>
                    <h6>【2013年】 高考 选择题</h6>
                    <p class="q_content">题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分题干部分</p>
                    <div class="checkArea">
                        <ul>
                            <li><i>1.</i>
                                <input type="checkbox" class="checkbox" checked>
                                <label>A 备选项1</label>
                                <input type="checkbox" class="checkbox">
                                <label>B 备选项2</label>
                                <input type="checkbox" class="checkbox">
                                <label>C 备选项3</label>
                                <input type="checkbox" class="checkbox">
                                <label>D 备选项4</label>
                            </li>
                            <li><i>2.</i>
                                <input type="checkbox" class="checkbox">
                                <label>A 备选项1</label>
                                <input type="checkbox" class="checkbox">
                                <label>B 备选项2</label>
                                <input type="checkbox" class="checkbox">
                                <label>C 备选项3</label>
                                <input type="checkbox" class="checkbox">
                                <label>D 备选项4</label>
                            </li>
                            <li><i>3.</i>
                                <input type="checkbox" class="checkbox">
                                <label>A 备选项1</label>
                                <input type="checkbox" class="checkbox">
                                <label>B 备选项2</label>
                                <input type="checkbox" class="checkbox">
                                <label>C 备选项3</label>
                                <input type="checkbox" class="checkbox">
                                <label>D 备选项4</label>
                            </li>
                        </ul>
                    </div>
                    <div class="btnArea clearfix"> <span class="openAnswerBtn fl">查看答案与解析<i class="open"></i></span> <span class="r_btnArea fr">难度:<em>5</em>&nbsp;&nbsp;&nbsp;录入:admin</span> </div>
                    <div class="answerArea hide">
                        <p><em>答案:</em><span>1.答案</span><span>2.答案</span><span>3.答案</span></p>
                        <p><em>解析:</em>解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容解析内容</p>
                    </div>
                </div>
                <div class="testPaper_view_btn tc">
                    <button type="button" class="w120 btn40 bg_blue conserve2">保存</button>
                    <a href="teacher-_test_organization_details.html" class="btn w120 btn40 bg_blue_l vied_l">取消预览</a> </div>
            </div>
        </div>
    </div>
</div>
