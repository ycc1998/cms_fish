Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,  //月份 
        "d+": this.getDate(),       //日 
        "h+": this.getHours(),      //小时 
        "m+": this.getMinutes(),    //分 
        "s+": this.getSeconds(),    //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}

// 实际并没有完全和PHP版的兼容
function date_formate(timestamp, format, convert, micros) {
    if (parseInt(timestamp) == 0)
        return '-';

    if (format == null)
    	format = 'yyyy/M/d';//format = 'yyyy/M/d h:m:s.S';
	
    if (convert == null)
    	convert = true;
	
    if (micros == null)
    	micros = false;
	
    var d = new Date();
    
    if (micros) {
    	d.setTime(timestamp);
    }else {
    	d.setTime(timestamp * 1000);
    }
    s = d.Format(format)
    
    if (convert == true) {
        var now = Date.parse(new Date());
        now = now / 1000;
        interval = now - timestamp;
        //分钟内
        if (interval < 60) {
            return '<span title="' + s + '">' + interval + '秒前</span>';
        }
        //小时内
        if (interval < 3600) {
            return '<span title="' + s + '">' + parseInt(interval / 60) + '分钟前</span>';
        }
        //一天内
        if (interval < 86400) {
            return '<span title="' + s + '">' + parseInt(interval / 3600) + '小时前</span>';
        }
    }
    return s;
}