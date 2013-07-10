define(function(reqire,exports){
    exports.test = function(){
        $('#content').append('我是动态生成的哦!');
        seajs.log('hello seajs');
    }
});