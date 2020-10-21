(function (){
	 function ammountFix(opts, isEnd){
		 var vals, val, $this = $(this);
		 var oldVal = $(this).val();
		 var isMinus = /^-/.test(oldVal) && opts.isMinus;
		 if (!opts.isFloat) {
			 val = $this.val().replace(/\D/g, '').replace(/^0+/, '');
		 }else{
			 vals = $this.val().replace(/[^0-9\.]/g, '').split('.').slice(0, 2);
			 vals[0] = vals[0].replace(/^0+/, '0').replace(/^0([1-9])/, '$1');
			 if (vals[1]) {
				 vals[1] = vals[1].slice(0, opts.digits);
			 }else if(isEnd){
				 vals = [vals[0]];
			 }
			 val = vals.join('.');
			 if (val == '.') {
				 val = '';
			 }
		 }
		 if(isEnd){
			 if (val) {
				 val = parseFloat(val) + '';
			 }
			 if (val == '0' || val == '') {
				 val = opts.defaultValue;
			 }else if(isMinus){
				val = -val;
			 }
		 }else if(isMinus){
			 val = '-' +val;
		 }
		 if (val > opts.max) {
			 val = opts.max;
		 }
		 if (isEnd && val && val < opts.min) {
			 val = opts.min;
		 }
		 if (val !== $this.val()) {
			 $this.val(val);
		 }
	 }
	 $.fn.ammountFix = function (opts){
		 if (opts) {
			if (opts.isMinus && !('min' in opts)) {
				opts.min = -Number.MAX_VALUE;
			}
		 }
		 opts = $.extend({
			 min: 0, //最小值
			 digits: 2, //小数位数
			 defaultValue: '',
			 max: Number.MAX_VALUE,//最大值
			 isMinus: false, //是否为负数
			 isFloat: false //是否为小数
		 }, opts);
		 ammountFix.call(this,opts);
		 this.keyup(function (){
			 ammountFix.call(this, opts);
		 }).blur(function (){
			 ammountFix.call(this, opts, true);
		 });
	 }
 })();