/**
 * Created with JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-21
 * Time: 下午2:59
 * To change this template use File | Settings | File Templates.
 */
define(function(require, exports, module) {

    var site_url = Looklo.site_url;
    var common = require('looklo.common');
    //console.log(common);
    //ajax 请求公用参数
    function ajaxParas (){
        var paras = {
            dataType : 'json',
            type : 'post',
            beforeSend:function(){
                common.showTip('wait','正在发送请求...');
            },
            timeout : function() {
                common.showTip('err','请求超时,请重试！');
            },
            abort : function() {
                common.showTip('err','网路连接被中断！');
            },
            parsererror : function() {
                common.showTip('err','运行时发生错误！');
            },
            complete:function(){
                setTimeout(function(){
                    common.hideTip();
                },2000);
            },
            error : function() {
                common.showTip('err','请求数据发生错误,请联系管理员！');
            }
        };
        return paras;
    }

    function showErrorCode (err){
        common.showTip('err',err.msg+' 错误码:'+err.code);
    }

    exports.requestApi = function(data,url,func){
        var paras = $.extend({},ajaxParas(),{
            url:site_url+'review/pass',
            data:data,
            success:function(res){
                if(typeof func == 'function'){
                    func(res);
                }
            }
        });
        //console.log(paras);
        $.ajax(paras);
    }

    // login ajax api
    exports.loginApi = function(data,btn,func){
        var paras = $.extend({},ajaxParas(),{
            url:site_url+'login/logindo',
            data:data,
            beforeSend:function(){
                common.showTip('wait','正在登录...');
            },
            success:function(res){
                if(res.error.code==0){
                    if(typeof func == 'function'){
                        func(res.data);
                    }
                }else{
                    showErrorCode(res.error);
                }
                // undisable button
                $(btn).attr('disabled',false);
            }
        });
        $.ajax(paras);
    }



});
