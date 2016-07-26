define(["popBox",'userCard','jquery_sanhai','jqueryUI'],function(popBox,userCard,jquery_sanhai){

    var json={
        "pageCount":9,
        "currPage":2,
        "list":[
            {"year":"2016","month":"01","day":"08","picData":["/dev/images/180_120.png","/dev/images/180_120.png","/dev/images/180_120.png","/dev/images/180_120.png","/dev/images/180_120.png","/dev/images/180_120.png"]},
            {"year":"2016","month":"01","day":"09","picData":["/dev/images/180_120.png","/dev/images/180_120.png"]},
            {"year":"2015","month":"03","day":"31","picData":["/dev/images/180_120.png","/dev/images/180_120.png"]},
            {"year":"2014","month":"11","day":"22","picData":["/dev/images/180_120.png","/dev/images/180_120.png","/dev/images/180_120.png","/dev/images/180_120.png"]}
        ]
    }

    var time_json=json.list;

    //时间线_更多大事记


    function addClip(time_json){

        var time_line=$('#time_line_list');
        var this_year=$('#time_line_list .timeLine_year');


        var this_month=null;
        var month_num='';
        var year_num='';

        for(var i=0; i<time_json.length; i++){
            var year=time_json[i].year;
            var month=time_json[i].month;
            var pics=time_json[i].picData;
            var html_pic='';
            for(var j=0; j<pics.length; j++){
                html_pic+='<img src="'+pics[j]+'">';
            }

            this_year.each(function(){
                var _this=$(this);
                year_num=parseInt(_this.text());
                if(year==year_num){//如果年相同
                    this_month=_this.parent('li').find('.timeLine_month:last');
                    month_num=parseInt(this_month.text());
                    if(month!=month_num){
                        _this.parents('li').append('<dl><dt class="timeLine_month">'+month+'月</dt></dl>');
                        this_month=_this.parent('li').find('.timeLine_month:last');
                        month_num=parseInt(this_month.text());
                    }
                }
                else{
                    time_line.append('<li><a class="timeLine_year">'+year+'</a><dl><dt class="timeLine_month">'+month+'月</dt></dl></li>')	;
                    this_year=$('#time_line_list .timeLine_year:last');
                    year_num=parseInt(this_year.text());
                    this_month=this_year.parent('li').find('.timeLine_month:last');
                }

                var pa=this_month.parent('dl');
                var html='<dd>';
                html+='<em>'+time_json[i].day+'日</em><i></i>';
                html+='<div class="clearfix">'+html_pic+'</div>';
                html+='</dd>';
                pa.append(html);
            })
        };

        var this_year=$('#time_line_list .timeLine_year');
        this_year.each(function(){
            if($(this).text()==""){
                $(this).parent('li').remove();
            }
        })


    };

    addClip(time_json);

    $('#time_addMore').click(function(){
        addClip(time_json)
    })



})