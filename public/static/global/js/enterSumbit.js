// 回车时提交表单
$.fn.enterSumbit = function (){
	    $(this).keyup(function (e){
			if (e.keyCode == 13) {
				$(this).closest('form').submit();
			}
	    });
	}