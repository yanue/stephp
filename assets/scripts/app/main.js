define(function(reqire,exports){
    exports.test = function(){
        $('#test').live('click',function(){
            seajs.log($(this).text());
        });
    }
});