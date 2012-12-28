/*
 ----------综合弹窗提示-------
 1 定时载入
 2 confirm

 @author : yanue
 */
define(function(require, exports, module) {

    //	加载css
    require('../css/looklo.dialog.css');

    function Dialog(){
        this.add();
    }

    //
    /**
     -------- 初始化选项参数 ------------------------------------------
     title    : 提示标题
     message  : 提示内容
     delay	 : 跳转选项，[是否跳转(跳转1.关闭0)，过度时间(3)，跳转地址]
     elem     : 绑定的节点 (点击该节点才弹出)
     button   : 按钮信息['确认按钮','取消按钮']
     confirm  : 回掉函数(点击确认时回掉，而取消按钮就直接隐藏)
     ------------------------------------------------------------------
     **/
    Dialog.option = {
        title : '警告！',
        message : '你确定要进行此操作吗?',
        delay :{
            stay:1,
            time:1,
            showTip:0,
            redirect:0,
            url:''
        },
        elem : '',
        button : [ '确定', '取消' ],
        confirm : ''
    };

    Dialog.timer = null; //定时跳转或关闭

    Dialog.prototype = {
        init:function(options){
            var _this = this;
            //console.log(options);
            // 替换默认值
            var opts = $.extend({}, Dialog.option, options);

            // 未指定节点elem时直接弹出
            if(!opts.elem){
                //console.log(opts);
                _this._set(opts);

                return false;
            }


            $(opts.elem).data('opts',opts);

            $(opts.elem).live('click',function(e){
                // 将原有数据清除
                Dialog.timer = null;

                var opt = $(this).data('opts');

                _this._set(opt);

                return false;
            });
        },

        // set common
        _set:function(opt){
            //console.log(opt.title);
            // 替换内容
            $('#msgDiv #msgTitle').find('b').html(opt.title);
            $('#msgDiv #msgContent').html(opt.message);

            this._setButton(opt);
            this._redirect(opt);
            this._confirm(opt);

            this.show();
        },

        // 设置按钮
        _setButton:function(opt){
            var button = '';
            if(opt.button[0]){
                button += '<a href="javascript:;" id="msgConfirm" class="msgbutton">' + opt.button[0] + '</a> ';
            }
            if(opt.button[1]){
                button += '<a href="javascript:;" id="msgClose" class="msgbutton">' + opt.button[1] + '</a>';
            }
            $('#msgDiv #confirm').html(button);
        },

        // 设置跳转
        _redirect:function(opt){
            var _this = this;
            if(opt.delay){
                //console.log(opt);
                var isRedirect = opt.delay.redirect;// 是否进行跳转，0为不跳转，1为跳转
                var t = opt.delay.time ? opt.delay.time : 3;
                var showTip = opt.delay.showTip==1 ? 1 : 0 ;//是否显示
                var url = opt.delay.url;
                var stay = opt.delay.stay; //是否一直显示

                if(stay==1){
                    return false;
                }

                var tip = isRedirect && url != undefined ? '跳转' : '关闭' ;

                if(showTip==1){
                    $("#msgDiv #msgContent").append('<p class="tip"><span id="timer">' + t + '</span> 秒后自动'+tip+'</p>');
                }

                Dialog.timer = window.setInterval(function() {
                    t--;
                    if (t == 0) {
                        window.clearInterval(Dialog.timer);
                        // 进行跳转
                        if(isRedirect && url != undefined ){
                            window.location.href = url;
                        }
                        _this.close();
                    }
                    $("#timer").html(t);
                }, 1000);
            }
        },

        // 点击确认
        _confirm : function(opts){
            var _this = this;

            if(typeof (opts.confirm) == 'function'){

                // 点击了确认
                $('#msgConfirm').live('click',function(e){

                    // 回掉函数
                    opts.confirm();
                    // 隐藏对话框
                    setTimeout(function(){_this.close()},300);
                    e.stopImmediatePropagation();
                });

            }else{

                $('#msgConfirm').data('opt',opts).live('click',function(e) {

                    var opt = $(this).data('opt');
                    // 如果是跳转
                    if(opt.delay){
                        var isRedirect = opt.delay.redirect;// 是否进行跳转，0为不跳转，1为跳转
                        var url = opt.delay.url;
                        // 进行跳转
                        if(isRedirect && url != undefined ){
                            window.clearInterval(Dialog.timer);
                            window.location.href = url;
                        }else{
                            window.clearInterval(Dialog.timer);
                            _this.close();
                        }
                    }

                    e.stopImmediatePropagation();
                });
            }
        },

        // 添加元素
        add:function(){

            dHtml = '<div id="msgbg"></div>'
                +'<div id="msgDiv">'
                +'	<p id="msgTitle"><span title="关闭" id="msgClose">x</span><b>' + Dialog.option.title + '</b></p>'
                +'	<div id="msgContent">' + Dialog.option.message + '</div>'
                +'	<p id="confirm"></p>'
                +'</div>';
            // 检测容器是否存在
            if(!$('#msgbg').size()){
                $(document.body).append(dHtml);
            }
            var _this = this;
            // 取消按钮
            $('#msgClose').live('click',function() {
                _this.close();
                //_this._redirect(0);// 取消跳转
            });

        },

        // 显示对话框
        show : function(){

            $('#msgbg').show();
            $('#msgDiv').fadeIn();
        },

        // 关闭对话框
        close : function() {
            if(Dialog.timer){
                window.clearInterval(Dialog.timer);
            }
            $("#msgDiv").fadeOut();
            $("#msgbg").hide();
        }
    }

    // 实例化
    var a = new Dialog();
    exports.init = function(opts){
        a.init(opts);
    }
});