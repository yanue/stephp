define(function(require, exports, module) {
	
	// 左边自适应高度
	exports.init = function() {
		$('#searchItem').click(function(){
			var taoid = $(this).parent().find('#taoItem').val();
			
			alert(taoid);
		});
	};
	
	
});
