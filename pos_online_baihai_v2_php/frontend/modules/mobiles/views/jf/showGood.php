<?php
/**
 * Created by PhpStorm.
 * User: gaoling
 * Date: 2016/6/23
 * Time: 15:19
 */
//echo "123";
?>
<style>
	* {
		margin: 0;
		padding: 0;
		list-style: none;
	}

	html {
		font-size: 0.31rem;
	}

	.good-main{
		float: left;
		text-align: center;
		margin: auto;
	}
	.good-content{
		float: left;
		margin:1.55rem 0.15rem 0rem 0.15rem;
		display: block;
	}
	.good-show{
		float: left;
		width: 4.95rem;
		height: 7.11rem;
		margin: 0rem 0.14rem 0.97rem 0.14rem ;
	}
	.good-img{
		width: 4.80rem;
		height: 3.28rem;
		margin-bottom: 0.66rem;
		display: block;
	}
	.good-img img {
		width: 4.95rem;
		height:3.28rem;
		font-size: 0.53rem;
		line-height: 3.28rem;
	}
	.good-text{
		width: 4.95rem;
		display: block;
		margin-bottom: 0.44rem;
	}
	.good-text p{
		font-size: 0.53rem;
	}
	.good-button{
		width: 4rem;
		height: 1.33rem;
		font-size: 0.57rem;
		color: #0099ff;
		padding:0.22rem;

	}

</style>
<script>
	window.onresize = window.onload = function () {
		document.documentElement.style.fontSize = document.documentElement.clientWidth / 16 + 'px';
	};
</script>
<div class="good-main">

	<div class="good-content">

		<?php if (!empty($goods)) { ?>
		<?php foreach ($goods as $key => $val) { ?>
		<div class="good-show">
			<span class="good-img">
				<img src="<?php echo $val->image; ?>" title="<?php echo $val->name?>">
			</span>

			<span class="good-text">
				<p style="margin-bottom: 0.22rem;" >所需积分：<em style="color: #fe7c2e"><?php echo $val->points;?></em></p>
<!--			    <p>已有<em style="color: #0099ff">2</em>人兑换</p>-->

			</span>
			<span>
				<p class="good-button">请到电脑端兑换</p>
			</span>
		</div>
		<?php }
		}?>
	</div>
</div>
