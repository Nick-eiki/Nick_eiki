define(['jquery','popBox','jquery_sanhai', 'validationEngine', 'validationEngine_zh_CN'], function ($,popBox) {
    $('#edit_user_info_form').validationEngine();
    $('#edit_user_info_form_again').validationEngine();
    var  add_user_next=null;
    $("#proof").click(function(){
        var classID=$('#classID').val();
        if ($('#edit_user_info_form').validationEngine('validate')) {
            var stuID=$('#stu_ID').val();
            var stuName=$('#stu_name').val();
            var phone=$('#stu_mol').val();
            var sex=$("[name='sex']:checked").val();
            $.post('query-students',{stuID:stuID,stuName:stuName,phone:phone,sex:sex,classID:classID},function(result){
                        $('.add_stu_verification').html(result);
                if($('[name="message"]').size()>1){
                    $('.unconflict').hide();
                }else{
                    $('.conflict').hide();
                }
            });
            $(".add_stu_verification").css("display","block");
              add_user_next=null;
        }else{
            $(".add_stu_verification").css("display","none");
        };

    });

    $("#return_up").live('click',function(){
        $("#form_add_stu").css("display","block");
        $("#form_add_stu_1").css("display","none");
    });

    $("#add_user_accounts").live("click",function(){
        add_user_next='新信息';
    });
    $(".add_user_accounts").live("click",function(){

        add_user_next='旧信息';
    });
    //学生信息&家长信息跳转页面
    $("#next").live("click",function(){
        if(add_user_next==null&&$('#message').find('input').size()==0){
            add_user_next='新信息';
        }
        if(add_user_next=="新信息"){//新建帐号
            $("#form_add_stu").css("display","none");
            $("#form_add_stu_1").css("display","block");
            var stuID=$('#stu_ID').val();

            var trueName=$('#stu_name').val();
            var bindphone=$('#stu_mol').val();
            var sex=$('input[name="sex_again"]:checked').val();
            $.post('get-stu-html',{trueName:trueName,bindphone:bindphone},function(result){
                $('#form_add_stu_1').html(result);

                $('#stu_ID_again').val(stuID);

                $('input[name="sex"]').each(function(index,el){
                    if($(el).val()==sex){

                        $(el).attr('checked','checked');
                    }else{
                        $(el).removeAttr('checked');
                    }
                })
                $("#stu_name_again, #stu_mol_again").attr({"disabled":true});
                $("#stu_name_again, #stu_mol_again").css({"border":"0px","background":"#ffffff"});

        });


        }else if(add_user_next=="旧信息"){//选择帐号
            $("#form_add_stu").css("display","none");
            $("#form_add_stu_1").css("display","block");
           var userID=$("input[name='message']:checked").val();
            var stuID=$('#stu_ID').val();
            $.post('get-stu-details',{userID:userID},function(result){
                 $('#form_add_stu_1').html(result);
                $('#stu_ID_again').val(stuID);
                $(".user_select_").attr({"disabled":true});
                $(".user_select_").css({"border":"0px","background":"#ffffff"});
            });


        }else if(add_user_next==null){
            popBox.errorBox('请选择学生或者新建账号');
        }
    });
//更新已有账号
    $('#submit_update').live('click',function(){
        var classID=$('#classID').val();
        var stuID=$('#stu_ID_again').val();
        var trueName=$('#stu_name_again').val();
        var  bindphone=$('#stu_mol_again').val();
        var sex=$('input[name="sex"]:checked').val();
        var phoneReg=$('#phoneReg').val();
        var parentsName=$('#parent_ID').val();
        var phone=$('#parent_mol').val();
        if ($('#edit_user_info_form_again').validationEngine('validate')) {
            $.post('move-stu-to-class', {
                stuID: stuID,
                trueName: trueName,
                bindphone: bindphone,
                sex: sex,
                phoneReg: phoneReg,
                parentsName: parentsName,
                phone: phone,
                classID: classID,
                type: 0
            }, function (result) {
                if (result.success) {

                    popBox.successBox(result.message);
                } else {
                    popBox.errorBox(result.message);
                }
                window.location.href = 'manage-list?classId=' + classID;
            })
        };
    });
//新建账号
    $('#submit_new').live('click',function(){
        var classID=$('#classID').val();
        var stuID=$('#stu_ID_again').val();
        var trueName=$('#stu_name_again').val();
        var  bindphone=$('#stu_mol_again').val();
        var sex=$('input[name="sex"]:checked').val();
        var phoneReg=$('#phoneReg').val();
        var parentsName=$('#parent_ID').val();
        var phone=$('#parent_mol').val();
        if ($('#edit_user_info_form_again').validationEngine('validate')) {
            $.post('move-stu-to-class',{
                stuID:stuID,
                trueName:trueName,
                bindphone:bindphone,
                sex:sex,
                phoneReg:phoneReg,
                parentsName:parentsName,
                phone:phone,
                classID:classID,
                type:1
            },function(result){
                if(result.success){

                    popBox.successBox(result.message);
                }else{
                    popBox.errorBox(result.message);
                }
                window.location.href='manage-list?classId='+classID;
            })
        }
    })
});