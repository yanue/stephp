// do some things

define(function(require, exports, module) {

    var common = require('looklo.common');//公共函数
    var site_url = Looklo.site_url;

    exports.manageReview = function(){
        $('#passed').live('click',function(){
            var rid = $(this).attr('rid');
            var reason = $('#reason').val();
            var data = {rid:rid,desc:reason};

            // disable button
            $(this).attr('disabled','disabled');

            seajs.use('looklo.api',function(a){
                a.delReview(data,function(res){
                    // unDisable button
                    $('#passed').attr('disabled',false);
                })
            });

        });
    }

    // login access
    exports.login = function(btn){
        $(btn).live('click',function(){
            var user = $('#loginForm #user').val();
            var passwd = $('#loginForm #passwd').val();

            var data  = {
                'user':user,
                'passwd':passwd
            }

            // disable button
            $(this).attr('disabled','disabled');

            seajs.use('looklo.api',function(a){
                a.loginApi(data,btn,function(res){
                    if(res){
                        //alert(res);
                        //window.location.href = site_url;
                    }


                })
            });


        });
    }


});