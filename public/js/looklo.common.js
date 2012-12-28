/*
 ----------综合弹窗提示-------
 1 定时载入
 2 confirm

 @author : yanue
 */
define(function(require, exports, module) {

    // status : err, ok, wait, warming
    exports.showTip = function(status,tip){
        $('#ajaxStatus').fadeIn();
        $('#ajaxStatus #ajaxTip').html(tip).addClass(status);
    }

    exports.hideTip = function(){
        $('#ajaxStatus').fadeOut();
        $('#ajaxStatus #ajaxTip').removeClass();
    }

    exports.selectAll = function (clickBtn,selc){
        var flag = true;
        $(clickBtn).live('click',function(e){

            if(flag==true){
                $(this).prop('checked',true);
                $(selc).prop('checked',true);

                $('.row').addClass('selected');

                flag = false;
            }else{

                $(this).prop('checked',false);
                $(selc).prop('checked',false);

                $('.row').removeClass('selected');
                flag = true;
            }

            e.stopImmediatePropagation();
        });
    }
});