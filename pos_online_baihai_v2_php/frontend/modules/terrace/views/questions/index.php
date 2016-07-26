
<?php
/**
 * Created by PhpStorm.
 * User: gaocailong
 * Date: 14-11-17
 * Time: 下午2:09
 */
/* @var $this yii\web\View */  $this->title="题库";
?>
<script type="text/javascript">
    $(function(){
//搜索按钮切换
        $('.terrace_btn_js span').bind('click',function(){
            $(this).addClass('s_btn').siblings('span').removeClass('s_btn');

        });
        var timer=null;
        //科目切换1
        $('.tabs_js li').mouseover(function() {
            var this_s= $(this).parents('.tere_line').children('.tere_b').children('.con');
            $(this).addClass("curr").siblings().removeClass("curr");
            this_s.eq($(this).index()).show().siblings().hide();

        });
    })
</script>

<script type="text/javascript">
    function getStyle(obj,name)
    {
        if(obj.currentStyle)
        {
            return obj.currentStyle[name]
        }
        else
        {
            return getComputedStyle(obj,false)[name]
        }
    }

    function getByClass(oParent,nClass)
    {
        var eLe = oParent.getElementsByTagName('*');
        var aRrent  = [];
        for(var i=0; i<eLe.length; i++)
        {
            if(eLe[i].className == nClass)
            {
                aRrent.push(eLe[i]);
            }
        }
        return aRrent;
    }

    function startMove(obj,att,add)
    {
        clearInterval(obj.timer);
        obj.timer = setInterval(function(){
            var cutt = 0 ;
            if(att=='opacity')
            {
                cutt = Math.round(parseFloat(getStyle(obj,att)));
            }
            else
            {
                cutt = Math.round(parseInt(getStyle(obj,att)));
            }
            var speed = (add-cutt)/4;
            speed = speed>0?Math.ceil(speed):Math.floor(speed);
            if(cutt==add)
            {
                clearInterval(obj.timer)
            }
            else
            {
                if(att=='opacity')
                {
                    obj.style.opacity = (cutt+speed)/100;
                    obj.style.filter = 'alpha(opacity:'+(cutt+speed)+')';
                }
                else
                {
                    obj.style[att] = cutt+speed+'px';
                }
            }

        },30)
    }

    $(function()
        {
            var oDiv = document.getElementById('playBox');
            var oPre = getByClass(oDiv,'pre')[0];
            var oNext = getByClass(oDiv,'next')[0];
            var oUlBig = getByClass(oDiv,'oUlplay')[0];
            var aBigLi = oUlBig.getElementsByTagName('li');
            var oDivSmall = getByClass(oDiv,'smalltitle')[0];
            var aLiSmall = oDivSmall.getElementsByTagName('li');

            function tab()
            {
                for(var i=0; i<aLiSmall.length; i++)
                {
                    aLiSmall[i].className = '';
                }
                aLiSmall[now].className = 'thistitle';
                startMove(oUlBig,'left',-(now*aBigLi[0].offsetWidth))
            }
            var now = 0;
            for(var i=0; i<aLiSmall.length; i++)
            {
                aLiSmall[i].index = i;
                aLiSmall[i].onclick = function()
                {
                    now = this.index;
                    tab();
                }
            }
            oPre.onclick = function()
            {
                now--;
                if(now ==-1)
                {
                    now = aBigLi.length;
                }
                tab();
            };
            oNext.onclick = function()
            {
                now++;
                if(now ==aBigLi.length)
                {
                    now = 0;
                }
                tab();
            };
            var timer = setInterval(oNext.onclick,3000); //滚动间隔时间设置
            oDiv.onmouseover = function()
            {
                clearInterval(timer)
            };
            oDiv.onmouseout = function()
            {
                timer = setInterval(oNext.onclick,3000); //滚动间隔时间设置
            }
        }

    )
</script>






<!--主体内容开始-->

<div class="terrace_content clearfix replace">
    <div class="currentLeft grid_17">
        <div id="playBox" class="playBox">
            <div class="pre"></div>
            <div class="next"></div>
            <div class="smalltitle">
                <ul>
                    <li class="thistitle"></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
            <ul class="oUlplay">
                <li><a href="#" target="_blank"><img src="<?php echo publicResources()?>/images/1.jpg" alt=""></a></li>
                <li><a href="#" target="_blank"><img src="<?php echo publicResources()?>/images/2.jpg" alt=""></a></li>
                <li><a href="#" target="_blank"><img src="<?php echo publicResources()?>/images/3.jpg" alt=""></a></li>
                <li><a href="#" target="_blank"><img src="<?php echo publicResources()?>/images/4.jpg" alt=""></a></li>
                <li><a href="#" target="_blank"><img src="<?php echo publicResources()?>/images/5.jpg" alt=""></a></li>
                <li><a href="#" target="_blank"><img src="<?php echo publicResources()?>/images/6.jpg" alt=""></a></li>
            </ul>
        </div>
        <!--菜单-->
        <div class="teer_nav">
            <div class="tere_line">

                <?php echo $this->render('_questions_view',array('department'=>$grade));?>
            </div>


            <div class="tere_line">
                <?php echo $this->render('_questions_view',array('department'=>$middleSchool));?>
            </div>

            <div class="tere_line">
                <?php echo $this->render('_questions_view',array('department'=>$highSchool));?>
            </div>




        </div>

        <!--菜单-->



    </div>
    <div class="centRight">
        待定
    </div>


</div>

<!--主体内容结束-->

