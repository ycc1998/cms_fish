function array_clone(arr){
	var clone = {};
	for(var i in arr){
		if(typeof arr[i] != 'object'){
			clone[i] = arr[i];
		}else{
			clone[i] = array_clone(arr[i]);
		}
	}
	return clone;
}

function count_length(arr){
	var length = 0;
	for(var i in arr){
		length++;
	}
	return length;
}

function getCombines(_array){
	var combineArray = [[]];
	for(var i in _array){
		var newCombineArray = [];
		for(var k in combineArray){
			for(var h in _array[i]){
				var newArray = array_clone(combineArray[k]);
				newArray[count_length(newArray)] = array_clone(_array[i][h]);
				newCombineArray[count_length(newCombineArray)] = newArray;
			}
		}
		combineArray = newCombineArray;
	}
	return combineArray
}

//var selects = [['a','b','c'],['d','e','f'],['g','h','i']];
//console.log(getCombines(selects));


function _message(type, txt) {
    onceInsertHTML('<div class="_message"></div>');
    var message = $('._message');
    message.removeClass('message-success message-warning');
    message.addClass(type);
    message.html(txt);
    message.stop(true).css('margin-left', -message.outerWidth() / 2).fadeIn(500).delay(3000).fadeOut(500);
}

function directURL(url, time) {
    if (time) {
        time = time *1000;
    }
    
    if (url) {
        setTimeout(function(){
            window.location.href = url; // 操作成功刷新页面，为空则当前页
        } ,time);
    }
}

function success(txt, direct, time) {
    _message('message-success', txt);
    directURL(direct, time); // 刷新当前页
}
function warning(txt, direct, time) {
    _message('message-warning', txt);
    directURL(direct, time);
}

$(function() {
    $("._close_window").click(function() {
        //当你在iframe页面关闭自身时
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭     
    });
});

// 使用闭包来实现
function getOnceFunc(fn, context) {
    var called;
    return function() {
        if (!called) {
            called = true;
            return fn.apply(context, arguments);
        }
    }
}
// 只插入一次HTML
var onceInsertHTML = getOnceFunc(function(html) {
    $('body').append(html);
});

// 一般用于前台弹出交互对话框
function openDialog(element, title, area, url) {
	$(element).click(function(){
		href = url;
		if (url == undefined) {
			var href = $(this).attr('href');
		}
		window.layer.open({
		    type:2,
		    title: title,
		    shadeClose: true,
		    closeBtn: 1,
		    shade: 0.6,
		    area: area,
		    shift: 2, 
		    content: href,
		    skin: 'layui-layer-rim', //加上边框
		    end : function(){
		        parent.location.reload();
	        }
		}); 
		return false;
	});
}

// iframe风格的打开窗口
function openLayer(url, title, area, shade, fun, close) {
    if (shade == undefined) {
        shade = 0.6
    }

	if (close == undefined) {
        close = true;
    }
    layer.open({
        type: 2,
        title: title,
        shadeClose: close,
        shade: shade,
        area: area,
        shift: 2,
        content: url,
        //iframe的url
        end: fun,
    });
}

function setUploadFiles(data, upload_id, is_multi, config) {
	var fileName = data.name;
	var suffix = fileName.substring(fileName.lastIndexOf('.')+1, fileName.length).toLowerCase();
    if (($.inArray(suffix, ['', 'gif','png','jpeg', 'bmp', 'jpg']))) {
		setUploadImages(data, upload_id, is_multi, config);
	}

	// 处理其它文件类型
	return;
}

//打开图片上传图片文件
function openLayerUploadProxy(){
	var args = [].slice.call(arguments, 1);
	var url = args[0];
	var m_reg = /\b(multi_num=)(\d+)/;
	if (m_reg.test(url)) {
		var max = parseFloat(url.match(m_reg)[2]);
		var el = $('#'+arguments[0]);
		var imgCount = el.closest('div.upload-images').find('div.images-thumb img').length;
		var size = max - imgCount;
		el.attr('data-size', max);
		args[0] = url.replace(m_reg, "$1" + size);
	}
	openLayer.apply(this, args);
}

function fitUploadImageSize(img){
    var src = img.src;
	var parent = $(img).parent();
	var width = parent.width();
	parent.css('text-align', 'center');
	var el = new Image();
	el.onload = function (){
		var b = el.width/el.height;
		$(img).css({
			width: width*b,
			height: width
		});
		if (el.width > el.height) {
			parent.css({
				width: width*b
			});
		}
	};
	el.src=src;
}

// 多文件上传组件回调函数
function setUploadImages(data, upload_id, is_multi, config) {
	var hideInput = $('#' + upload_id);
    var upload_images = hideInput.closest('div.upload-images');
    var setMain = upload_images.find('span._setMain');
    var thumbs = upload_images.find('.thumbs');
    var html = '<div class="images-thumb"><img data-md5="' + data['md5'] + '" src="' + data['path'] + '" onload="fitUploadImageSize(this)" />';
    if (setMain.attr('data-main') == '1' && is_multi == true) {
        html += '<span data-id="' + data.id + '" class="setMain">主图</span>';
    }
    html += '<span data-id="' + data.id + '" class="remove">移除</span>';
    html += '</div>';
    var imgs = $('.images-thumb img');
    var isInsert = true;
    imgs.each(function(i) {
        var md5 = ($(this).attr('data-md5'));
        if (md5 == data['md5']) {
            isInsert = false;
        }
    });
    if (isInsert) {
        thumbs.append(html);
        var fid = hideInput.val();
        if (fid == '') {
            id = data.id;
        } else {
            id = fid + ',' + data.id;
        }
        hideInput.val(id);
		isShowUploadButton(upload_id, upload_images, is_multi);
    }
}

function isShowUploadButton(upload_id, upload_images, isMulti, isDel){
	var isHide;
	var btn = upload_images.find('.btnUpload');
	if (!isMulti && !isDel) {
			isHide = true;
	}else{
		var el = $('#'+upload_id);
		var size = parseFloat(el.attr('data-size'));
		var count = $('.images-thumb img').length;
		if (size) {
			isHide = size - count < 1;
		}
	}
	if (isHide) {
		btn.hide();
	}else{
		btn.show();
	}
}
 
$(function() {
    $('.upload-images').delegate('.setMain', 'click',
    function() {
        var fids = $(this).parents('.upload-images').find('input.fids'); // 当前图片列表ID
        var id = $(this).attr('data-id'); // 当前图片ID
        //alert(id);
        // 把当前ID移动到最前面
        var rs = fids.val().replace(eval("/" + id + ",?/"), '').replace(/(,*$)/g, "");
        fids.val((id + ',' + rs));
        //alert((id + ',' + rs));
        $(this).parents('div.thumbs').prepend($(this).parent('div.images-thumb'));
    });

    // 还的判断是否为单图模式，如果是单图模式，还的把上传按钮还原回来
    $('.upload-images').delegate('.remove', 'click',
    function() {
        if (confirm('确认移除此图片？')) {
            var fids = $(this).parents('.upload-images').find('input.fids');
            var id = $(this).attr('data-id');
            var rs = fids.val().replace(eval("/" + id + ",?/"), '').replace(/(,*$)/g, "");
            fids.val(rs);
            var upload_images = $(this).parents('.upload-images');
            var isMulti = upload_images.find('._multi').attr('data-main') == '1';
            $(this).parent('div.images-thumb').remove(); // 不能用隐藏，否则删除再上传原来的图片则会无法正常显示
			isShowUploadButton(0, upload_images, isMulti, true); 
        }
    });
});


function browserRedirect(mobile_url, pc_url) { 
	var sUserAgent= navigator.userAgent.toLowerCase(); 
	var bIsIpad= sUserAgent.match(/ipad/i) == "ipad"; 
	var bIsIphoneOs= sUserAgent.match(/iphone os/i) == "iphone os"; 
	var bIsMidp= sUserAgent.match(/midp/i) == "midp"; 
	var bIsUc7= sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4"; 
	var bIsUc= sUserAgent.match(/ucweb/i) == "ucweb"; 
	var bIsAndroid= sUserAgent.match(/android/i) == "android"; 
	var bIsCE= sUserAgent.match(/windows ce/i) == "windows ce"; 
	var bIsWM= sUserAgent.match(/windows mobile/i) == "windows mobile"; 
	if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) { 
		window.location.href= mobile_url; 
	}else {
		window.location.href= pc_url; 
	}
}


function checkContent(value) {   
    var reg = /[ ，,\\/]/; // 空格，中英文逗号
    var ret = !reg.test(value);
    return ret;
}