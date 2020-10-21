(function (){
	var _tmpl = '<div class="m-calendar" style="display:none">'+
			'	<div class="m-calendar-day zoomFx">'+
			'		<div class="m-calendar-head">'+
			'			<span class="prev"></span>'+
			'			<span class="next"></span>'+
			'			<span class="title"></span>'+
			'			<span class="time"></span>'+
			'		</div>'+
			'		<div class="m-calendar-offset">'+
			'			<div class="m-calendar-week">'+
			'				<span>日</span>'+
			'				<span>一</span>'+
			'				<span>二</span>'+
			'				<span>三</span>'+
			'				<span>四</span>'+
			'				<span>五</span>'+
			'				<span>六</span>'+
			'			</div>'+
			'			<div class="m-calendar-list"></div>'+
			'		</div>'+
			'	</div>'+
			'	<div class="m-calendar-month zoomFx">'+
			'		<div class="m-calendar-head">'+
			'			<span class="prev"></span>'+
			'			<span class="next"></span>'+
			'			<span class="title">2012年</span>'+
			'		</div>'+
			'		<div class="m-calendar-offset">'+
			'			<div class="m-calendar-list">'+
			'				<span>1月</span>'+
			'				<span>2月</span>'+
			'				<span>3月</span>'+
			'				<span>4月</span>'+
			'				<span>5月</span>'+
			'				<span>6月</span>'+
			'				<span>7月</span>'+
			'				<span>8月</span>'+
			'				<span>9月</span>'+
			'				<span>10月</span>'+
			'				<span>11月</span>'+
			'				<span>12月</span>'+
			'			</div>			'+
			'		</div>'+
			'	</div>'+
			'	<div class="m-calendar-year zoomFx">'+
			'		<div class="m-calendar-head">'+
			'			<span class="prev"></span>'+
			'			<span class="next"></span>'+
			'			<span class="title"></span>'+
			'		</div>'+
			'		<div class="m-calendar-offset">'+
			'			<div class="m-calendar-list">'+
			'				<span>2012</span>'+
			'				<span>2013</span>'+
			'				<span>2014</span>'+
			'				<span>2015</span>'+
			'				<span>2016</span>'+
			'				<span>2017</span>'+
			'				<span>2018</span>'+
			'				<span>2019</span>'+
			'				<span>2020</span>'+
			'				<span>2021</span>'+
			'				<span>2022</span>'+
			'				<span>2023</span>'+
			'			</div>'+
			'		</div>'+
			'	</div>'+
			'	<div class="m-calendar-time zoomFx">'+
			'		<div class="m-calendar-head">'+
			'			<span class="title"></span>'+
			'			<span class="close"></span>'+
			'		</div>'+
			'		<div class="m-calendar-select">'+
			'			<select name="hour">'+
			'			</select> '+
			'			<select name="minute">'+
			'			</select> '+
						'<select name="second">'+
			'			</select>'+
			'		</div>'+
			'	</div>'+
			'	<div class="m-calendar-toggle"></div>'+
			'	<div class="m-calendar-today"></div>'+
			'</div>';
	var single;
	function stringf(tpl, o) {
		return (tpl + '').replace( /\{\{(\w+)\}\}/g,function(a, b) {
			var v = o[b];
			return v === undefined || v === null || isNaN(v) ? '': v;
		});
	}
	function getMaxDay(y, m){
		return m==2 ? ((y%4==0&&y%4!=100||y%100==0&&y%400==0)?29:28) :
			(/^(4|6|9|11)$/.test(m) ? 30 : 31);
	}
	function getWeekByDate(y, m, d){return (new Date(y, --m, d)).getDay();}
	function getOldList(y, m, w){
		var date = new Date(y, m - 1, 1), tmp = [];
		date.setDate(0);
		var y = date.getFullYear(), m = date.getMonth(),
			lastDay = getMaxDay(y, m+1);
		for (w; w--;) {tmp.push(['old',lastDay - w, [y, m+1, lastDay - w].join('/')]);}
		return tmp;
	}
	function getNowList(lastDay, y, m){
		var tmp = [];
		for (var i = 0; i < lastDay; i++) {tmp.push(['today',i+1, [y, m, i + 1].join('/')]);}
		return tmp;
	}
	function getNewList(n, date){
		var i = 0, tmp = [];
		date = new Date(date);
		date.setMonth(date.getMonth() + 1);
		var y = date.getFullYear(), m = date.getMonth()+1;
		for (; i < n; i++) {tmp.push(['new',i+1, [y, m, i+1].join('/')]);}
		return tmp;
	}
	function Calendar(){
		this.onchange = $.noop;
		this.curDate = new Date();
		this.ui = {};
		this.options = {};
		this.getUI(this.ui);
		this.setToday(this);
		this.addEvent(this, this.ui);
		this.updateDay();
	}
	function toTime(time){
		return time ? (new Date(typeof time == 'string' ? time.replace(/-/g, '/') : time)) : new Date();
	}
	Calendar.prototype = {		
		reset: function (opts, input){
			this.currentInput = input;
			var date = toTime($(input).data('date'));
			this.options = {
				format: '{{yyyy}}-{{MM}}-{{dd}}',
				gt: false,
				lt: false
			};
			for(var k in opts){
				if (opts[k]) {
					this.options[k] = opts[k];
				}
			}
			if (this.options.format.indexOf('{{hh}}') > -1) {
				this.options.hasTime = true;
			}
			this.updateDay(date);
			this.el.children().hide();
			if (this.options.format == '{{hh}}:{{mm}}' || this.options.format == '{{hh}}:{{mm}}:{{ss}}') {
				this.options.onlyTime = true;
				this.options.notSecond = false;
				if (this.options.format == '{{hh}}:{{mm}}') {
					this.options.notSecond = true;
				}

				this.updateTime(this, this.ui);
				this.ui.timeBox.show();
				if (this.options.notSecond) {
					this.ui.second.hide();
				}else {
					this.ui.second.show();
				}

				if (this.options.onlyTime) {

					this.ui.timeClose.show();
				}else{
					this.ui.timeClose.hide();
				}
			}else{
				this.ui.dayBox.show();
				this.ui.today.show();
			}
		},
		showTo: function (el){
			var pos = $(el).offset();
			pos.top += $(el).outerHeight() + 1;
			this.el.css(pos).show();
		},
		setToday: function (self){
			var today = new Date();
			this.ui.today.html('今天' + (today.getMonth()+1) + '月' + today.getDate() + '日');
			this.ui.today.click(function (){
				self.updateDay();
			});
		},
		getUI: function (ui){
			this.createUI();
			ui.dayBox = this.el.find('div.m-calendar-day');
			ui.nextMonth = this.el.find('div.m-calendar-day span.next');
			ui.prevMonth = this.el.find('div.m-calendar-day span.prev');
			ui.toMonth = this.el.find('div.m-calendar-day span.title');
			ui.dayList = this.el.find('div.m-calendar-day div.m-calendar-list');
			ui.today = this.el.find('div.m-calendar-today');
			ui.toTime = this.el.find('div.m-calendar-day span.time');

			ui.monBox = this.el.find('div.m-calendar-month');
			ui.nextY = this.el.find('div.m-calendar-month span.next');
			ui.prevY = this.el.find('div.m-calendar-month span.prev');
			ui.toYear = this.el.find('div.m-calendar-month span.title');
			ui.monthList = this.el.find('div.m-calendar-month div.m-calendar-list');

			ui.yearBox = this.el.find('div.m-calendar-year');
			ui.nextYs = ui.yearBox.find('span.next');
			ui.prevYs = ui.yearBox.find('span.prev');
			ui.yearTitle = ui.yearBox.find('span.title');
			ui.yearList = ui.yearBox.find('div.m-calendar-list');

			ui.timeBox = this.el.find('div.m-calendar-time');
			ui.timeTitle = this.el.find('div.m-calendar-time span.title');
			ui.timeClose = this.el.find('div.m-calendar-time span.close');
			ui.hour = this.el.find('select[name=hour]');
			ui.minute = this.el.find('select[name=minute]');

			// 增加秒的UI
			ui.second = this.el.find('select[name=second]');
		},
		addEvent: function (self, ui){
			function setOffsetMonth(n, s){
				var date = new Date(self.curDate), to;
				date.setDate(1);
				to = arguments.length > 1 ? s : (date.getMonth()+n);
				date.setMonth(to);
				var md = Math.min( self.curDate.getDate(), getMaxDay(date.getFullYear(), date.getMonth() + 1));
				date.setDate(md);
				self.updateDay(date);				
			}
			ui.nextMonth.click(function (){
				setOffsetMonth(1);
			});
			ui.prevMonth.click(function (){
				setOffsetMonth(-1);
			});
			ui.dayList.on('click', 'span', function (){
				var css = this.className, offset = 0;
				if (css.indexOf('disabled') > -1) {
					return false;
				}
				if (css.indexOf('today') == -1) {
					offset = css.indexOf('old') > -1 ? -1 : 1;
				}
				var date = new Date(self.curDate);
				date.setMonth(date.getMonth()+offset);
				date.setDate(this.innerHTML);
				self.updateDay(date, true);
				self.el.hide();
			});
			ui.toTime.click(function (){
				self.updateTime(self, self.ui);
				self.el.children().hide();
				ui.timeBox.show();

				if (self.options.format.indexOf('{{ss}}') > -1) {
					ui.second.show();
				}else {
					ui.second.hide();
				}

				if (self.options.onlyTime) {
					ui.timeClose.show();
				}else{
					ui.timeClose.hide();
				}				
			});
			ui.hour.add(ui.minute).add(ui.second).change(function (){
				var date = self.curDate;
				date.setHours(ui.hour.val());
				date.setMinutes(ui.minute.val());
				date.setSeconds(ui.second.val());
				self.updateDay(date, true);
			});
			ui.toMonth.click(function (){
				self.el.children().hide();
				ui.monBox.show();
			});
			ui.timeTitle.click(function (){
				if (!self.options.onlyTime) {
					self.el.children().hide();
					ui.dayBox.show();		
				}		
			});
			ui.timeClose.click(function (){
				self.el.hide();
			});
			ui.nextY.click(function (){
				var date = new Date(self.curDate);
				date.setFullYear(date.getFullYear() + 1);
				self.updateDay(date);
			});
			ui.prevY.click(function (){
				var date = new Date(self.curDate);
				date.setFullYear(date.getFullYear() - 1);
				self.updateDay(date);
			});
			ui.monthList.on('click', 'span', function (){
				if (this.className.indexOf('disabled') > -1) {
					return false;
				}
				setOffsetMonth(false, parseInt(this.innerHTML, 10) - 1);
				self.el.children().hide();
				ui.dayBox.show();
				ui.today.show();
			});
			ui.toYear.click(function (){
				self.el.children().hide();
				self.updateYear();
				ui.yearBox.show();
			});
			ui.yearList.on('click', 'span', function (){
				if (this.className.indexOf('disabled') > -1) {
					return false;
				}
				var date = new Date(self.curDate);
				date.setFullYear(this.innerHTML);
				self.updateDay(date);
				self.el.children().hide();
				ui.monBox.show();
			});
			ui.nextYs.click(function (){
				self.currentRelYear += 10;
				self.updateYear(self.currentRelYear);
			});
			ui.prevYs.click(function (){
				self.currentRelYear -= 10;
				self.updateYear(self.currentRelYear);
			});
			$(self.el).mousedown(function (e){
				e.stopPropagation();
			});
			$(document).mousedown(function (){
				self.el.hide();
			});
            $(window).blur(function (){
                self.el.hide();
            });
		},
		updateYear: function (n){
			var date = this.curDate, year = date.getFullYear(), D = this.getDateVals(date), self = this;
			n = parseInt((n || date.getFullYear())/10, 10)*10;
			this.currentRelYear = n;
			var html = [], m = n - 1;
			for (var i = 0; i < 12; i++) {
				var y = m+i, c='';
				if (y == year) {
					c='checked';
				}else if(i==0 || i==11){
					c = "old";
				}
				html.push('<span class="'+c+'">'+(m+i)+'</span>');
			}
			this.ui.yearTitle.html((n-1)+'-'+(n+10));
			this.ui.yearList.html(html.join(''));
			if (!(this.options.gt || this.options.lt)) {
				return;
			}
			this.ui.yearList.find('span').each(function (){
				if(self.isDisabled(parseInt(this.innerHTML, 10)+'/'+D.M+'/'+D.d)){
					$(this).prop('className', 'disabled');
				}else{
					$(this).removeClass('disabled');
				}
			});
		},
		updateTime: function (self, ui){
			var h = ui.hour.get(0).options, m = ui.minute.get(0).options, s = ui.second.get(0).options, opt,
				th = this.curDate.getHours(), tm = this.curDate.getMinutes(),  ts = this.curDate.getSeconds();
			h.length = 0;
			m.length = 0;
			s.length = 0;
			for (var i = 0; i < 60; i++) {
				if (i < 24) {
					opt = new Option(('00'+i).slice(-2), i);
					h.add(opt);
					opt.selected = th == i;
				}		
				opt = new Option(('00'+i).slice(-2), i);
				m.add(opt);


				opt_s = new Option(('00'+i).slice(-2), i);
				s.add(opt_s);
				opt_s.selected = ts==i;

				opt.selected = tm == i;
			}
		},
		isDisabled: function (date){
			var date = new Date(date).getTime(), gt = this.options.gt, lt = this.options.lt;
			return (gt && date <= gt) || (lt && date >= lt);
		},
		updateDay: function (date, trigger){

			if (!date) {date = new Date();}
			this.curDate = date;
			if (!this.options.hasTime) {//如果不带时间段，则认为时间是0
				this.curDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
			}
			var D = this.getDateVals(date), 
				last = getMaxDay(D.y, D.M),
				w = getWeekByDate(D.y, D.M, 1) || 7
				nowList = getNowList(last, D.y, D.M),
				oldList = getOldList(D.y, D.M, w),
				newList = getNewList(42-last-oldList.length, date),
				html = $.map(oldList.concat(nowList.concat(newList)), function (v, i){
					var css = v[0]=='today'&&v[1]==D.d?' checked':'';
					return '<span class="'+v[0]+css+'" data-date="'+v[2]+'">'+v[1]+'</span>';
				});
			this.ui.dayList.html(html.join(''));
			this.ui.toMonth.html(D.yyyy + '年' + D.MM + '月');
			this.ui.timeTitle.html(D.yyyy + '年' + D.MM + '月' + D.dd + '日' + ' 周' + '日一二三四五六'.charAt(date.getDay()));
			this.ui.monthList.find('span').removeClass('checked').eq(D.M-1).addClass('checked');
			this.ui.toYear.html(D.yyyy+'年');
			this.ui.toTime.html(this.options.hasTime ? stringf('{{hh}}:{{mm}}', D) : '');
			if (this.options.hasTime) {
				this.ui.toTime.show();
			}else{
				this.ui.toTime.hide();
			}
			this.checkRange(D);
			if (trigger) {
				this._change(this.curDate);
			}			
		},
		checkRange: function (D){
			var self = this, last;
			this.ui.dayList.find('span').each(function (){
				var datestr = this.getAttribute('data-date');
				if(self.isDisabled(datestr)){
					$(this).prop('className', 'disabled');
				}else{
					last = datestr;
				}
			});
			this.ui.monthList.find('span').each(function (){
				var m = parseInt(this.innerHTML, 10);
				if(self.isDisabled(D.y+'/'+m+'/1')
					&& self.isDisabled(D.y+'/'+m+'/'+getMaxDay(D.y, m))){
					$(this).prop('className', 'disabled');
				}else{
					$(this).removeClass('disabled');
				}
			});
		},
		_change: function (){
			var el = $(this.currentInput).data('date', this.curDate).val(stringf(this.options.format, this.getDateVals(this.curDate)));
            el.trigger('dateChange', this.curDate);
		},
		addZero: function (s){return ('0'+s).slice(-2);},
		getDateVals: function (date){
			var split = {
				src: date,
				y: date.getFullYear(),
				M: date.getMonth()+1,
				MM: date,
				d: date.getDate()	,
				h: date.getHours(),
				m: date.getMinutes(),
				s: date.getSeconds()
			}, addZero = this.addZero;
			split.yyyy = split.yy = split.y;
			split.mm = addZero(split.m);
			split.MM = addZero(split.M);
			split.dd = addZero(split.d);
			split.hh = addZero(split.h);
			split.ss = addZero(split.s);
			return split;
		},
		createUI: function (){
			this.el = $(_tmpl).appendTo(document.body);
		}
	}
	function getCalendar(){
		if (!single) {
			single = new Calendar();
		}
		return single;
	}
	function getDate(t){
		if (t) {
            if (t==='now') {
                return new Date();
            }
			if (/[^0-9-\/\: ]/.test(t)) {
				var glt = $(t).data('date');
				return glt ? toTime(glt).getTime() : false;
			}else{
				return new Date(t.replace(/-/g, '/')).getTime();
			}
		}
	}
	$.fn.calendar = function (opts){
		var obj = getCalendar();
		this.prop('readonly', true);
		this.focus(function (){
			obj.reset({
				format: this.getAttribute('data-format'),
				gt: getDate(this.getAttribute('data-gt')),
				lt: getDate(this.getAttribute('data-lt'))
			}, this);
			obj.showTo(this);
		});
		this.mousedown(function (e){
			e.stopPropagation();
		});		
	}
})();