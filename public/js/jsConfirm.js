(function($){
	$.fn.jqConfirm = function(callback, options){
		// 默认参数
		var defaults = {
			confirmText: '确认此操作？',
			submitBtnText: '确认',
			cancelBtnText: '取消'
		};
		
		// 合并参数
		var options = $.extend(defaults, options);
		
		// 元素
		var target = null;
		var jqConfirmId = "jqconfirm";
		var jqConfirmSubmitId = "jqconfirm-submit";
		var jqConfirmCancelId = "jqconfirm-cancel";
		var jqConfirmHtml = '<div id="jqconfirm">'
			+ '<div id="jqconfirm-text">' + options.confirmText + '</div>'
			+ 	'<div id="jqconfirm-options">'
			+ 		'<button id="jqconfirm-submit" type="button">' + options.submitBtnText + '</button>'
			+ 		'<button id="jqconfirm-cancel" type="button">' + options.cancelBtnText + '</button>'
			+ 	'</div>'
			+ '</div>';
		
		// 检测容器是否存在
		if(!$('#'+jqConfirmId).size()){
			$(document.body).append(jqConfirmHtml);
		}
		
		var jqConfirmClose = function(){
			var $jqConfirm = $('#'+jqConfirmId);
			
			var jOutHeight = $jqConfirm.outerHeight();
			
			var jsOffset = $jqConfirm.offset();
			
			var css = {display: 'none'};
			var animate = {top: '+='+jOutHeight +'px', opacity: 0};
			
			$jqConfirm.animate(animate, 300, function(){
				$jqConfirm.css(css);
			});
		}
		
		$('#'+jqConfirmSubmitId).live('click', function(){
			if(typeof callback == 'function'){
				callback(target, $(this));
				jqConfirmClose();
			}
		});
		
		$('#'+jqConfirmCancelId).live('click', function(){
			jqConfirmClose();
		});
		
		// 遍历元素
		this.each(function(){
			$(this).click(function(){
				var $this = $(this);
				target =  $this;
				var $jqConfirm = $('#'+jqConfirmId);
				
				var confirmText = $this.attr('confirmtext') || options.confirmText;
				var submitBtnText = $this.attr('submitbtntext') || options.submitBtnText;
				var cancelBtnText = $this.attr('cancelbtntext') || options.cancelBtnText;
				
				var jOutWidth = $jqConfirm.outerWidth();
				var jOutHeight = $jqConfirm.outerHeight();
				
				var aOutWidth = $this.outerWidth();
				var aOutHeight = $this.outerHeight();
				var aOffset = $this.offset();
				var css = {left: (aOffset.left + (aOutWidth - jOutWidth)/2), top: aOffset.top, display:'block', opacity: 0};
				var animate = {top: '-='+(jOutHeight-aOutHeight) +'px', opacity: 1};
		
				$jqConfirm.find('#jqconfirm-text').html(confirmText).end()
				.find('#jqconfirm-submit').text(submitBtnText).end()
				.find('#jqconfirm-cancel').text(cancelBtnText).end()
				.css(css).animate(animate, 200);
			});
		});
	}
})(jQuery);