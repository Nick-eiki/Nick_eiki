define(["popBox",'jquery_sanhai','jqueryUI','jQuery_cycle'],function(popBox,jquery_sanhai){
	//轮播图
	$('.slider-box').slide_min({
        autoplay:0
    });

    $('#classes_AD_banner_list').cycle({
        fx:'scrollLeft',
        pager:'.slideBtn',
        showSlideNum:true,
        speed:1000,
        timeout:4000
    });

})