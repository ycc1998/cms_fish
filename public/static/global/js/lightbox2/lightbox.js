//<![CDATA[
(function($) {
	var plus, html, win, isdrag, mask, 
		ie6 = !window.XMLHttpRequest && window.ActiveXObject;

	html = '<div class="view-header">' 
			+ '        <div class="view-btns">' 
			+ '            <a href="javascript:void(0)" onclick="return false" title="在新窗口打开" class="v-b-no">在新窗口打开</a>' 
			+ '            <a href="javascript:void(0)" onclick="return false" title="实际大小" class="v-b-rs">实际大小</a>' 
			+ '            <a href="javascript:void(0)" onclick="return false" title="关闭" class="v-b-c">关闭</a>' 
			+ '        </div>' 
			+ '        滚轮缩放' 
			+ '    </div>' 
			+ '    <div class="view-body">' 
			+ '        <img src="" alt="" />' 
			+ '    </div>';

	function Drag(title, body, range) {
		var w              = window,
		win                = body   || title,
		x, y, _left, _top, 
		range              = range  || function(x) {
			return x
		}

		title.style.cursor = 'move';
		title.onmousedown  = function(e) {
			e                    = e  || event;
			x                    = e.clientX,
			y                    = e.clientY,
			_left                = win.offsetLeft,
			_top                 = win.offsetTop;
			this.ondragstart     = function() {
				return false
			}

			document.onmousemove = e_move;
			document.onmouseup   = undrag;
		}

		function e_move(e) {
			e              = e  || event;
			var cl         = range(_left + e.clientX - x, 'x'),
			ct             = range(_top + e.clientY - y, 'y');
			win.style.left = cl + 'px';
			win.style.top  = ct + 'px';
			isdrag         = true;

			w.getSelection 
				? w.getSelection().removeAllRanges() 
				: document.selection.empty();
		}

		function undrag() {
			this.onmousemove = null;

			setTimeout(function() {
				isdrag = false;
			},
			10);
		}
	}

	function loadImg(url, end) {
		var img = new Image();
		img.src = url;

		if (!end) return abort;
		if (img.complete) return end(img),
		abort;

		img.onload  = function() {
			abort();
			end(img)
		};
		img.
		onerror = function() {
			end(img, true)
		};

		function abort() {
			img.onload = img.onerror  = null
		};

		return abort
	}

	function wheel(el, fn) {
		var type = 'onmousewheel' in document 
			? 'mousewheel'
			: 'DOMMouseScroll';

		return $(el).bind(type, function(e) {
			var delta, fix, 
			oe    = e.originalEvent;
			delta = oe.wheelDelta?oe.wheelDelta / 120 : -(oe.detail  || 0) / 3;
			fix   = window.opera  && window.opera.version() < 10?-1 : 1;

			e.preventDefault();
			e.offset = Math.round(delta) * fix;
			fn.call(e.target, e);
		})
	}

	function getSize(w, h, rW, rH) {
		var s        = w / h;
		var useWidth = isNaN(rH)  || rW / s <= rH;

		return useWidth 
			? [rW, rW/s]
			: [rH*s, rH];
	}

	function createPlus() {
		if (!plus) {
			win = $('<div class="view-out"/>').appendTo(document.body);
			win.html(html);
			var img = win.find('img');

			win.find('a.v-b-no').click(function() {
				window.open(img.attr('src'));
			});

			Drag(win.get(0), win.get(0));
			mask = $('<div class="viewMask" />').appendTo(document.body);
			plus = new View(win);
		}
	}

	function View(win) {
		var This      = this,
		img           = win.find('img');

		this.ui_img   = img;
		this.ui_body  = win.find('div.view-body');
		this.ui_fix   = win.find('a.v-b-rs');
		this.ui_close = win.find('a.v-b-c');
		this.ui_win   = win;

		$(window).resize(function() {
			if (This.isShow) {
				This.ui_img.css({
					width: Math.min(This.ui_img.width(), This.getMaxWidth())
				});
			}
		});

		wheel(this.ui_body[0],function(e) {
			This.resize(This.ui_img[0], e.offset, true);
		});

		mask.click(function() {
			This.close()
		});

		this.ui_fix.click(function() {
			This.srcSize();
		});

		this.ui_close.click(function() {
			This.close();
		});
	}

	View.prototype = {
		minWidth: 180,

		show: function(img) {
			var This = this;

			loadImg(img.src, function(img, err) {
				This.isShow = true;
				This.ui_img.attr('src', img.src).attr('real-w', img.width).attr('real-h', img.height);
				This.ui_img.css('width', Math.min(This.getMaxWidth(), img.width));
				This.ui_win.show();

				mask.show();
				This.cen();
			});
		},

		getMaxWidth: function() {
			var web = $(window);

			return getSize(this.ui_img.attr('real-w'), this.ui_img.attr('real-h'), web.innerWidth() - 40, web.innerHeight() - 82)[0];
		},

		cen: function() {
			var db = $(document.body),
			dd     = $(document.documentElement);

			mask.css({
				height: $(document).height()
			});

			this.ui_win.css({
				left : ($(window).innerWidth() - this.ui_win.width() - 20) / 2 + (ie6  ?dd.scrollLeft()  : 0),
				top  : ($(window).innerHeight() - this.ui_win.height() - 22) / 2 + (ie6?dd.scrollTop()   : 0)
			})
		},

		resize: function(img, val, noCen) {
			$(img).css({
				width: Math.max(this.minWidth, $(img).width() + val * 20)
			});
			if (!noCen) {
				this.cen();
			}			
		},

		srcSize: function() {
			this.ui_img.css({
				width: this.ui_img.attr('real-w')
			});

			this.cen();
		},

		close: function() {
			this.isShow = false;

			this.ui_win.hide();
			mask.hide();
		}
	};
	$.fn.setImgView = function(w, h) {
		var imgs = this.find('img');

		createPlus();

		if (w || h) {
			w = w == 'auto' ? Number.MAX_VALUE: w;
			h = h == "auto" ? Number.MAX_VALUE: h;

			imgs.each(function(i, x) {
				loadImg(this.src, function(img) {
					var iw        = img.width,
					ih            = img.height;
					x.style.width = getSize(iw, ih, Math.min(w, iw), Math.min(h, ih))[0] + 'px'
				});
			})
		}

		this.delegate('img', 'click', function() {
			plus.show(this)
		});
	}
})(jQuery);
/*
jQuery插件: setImgView, 用于等比缩放图片，并弹出预览窗口
setImgView(缩略图宽度，缩略图高度 );
*/
//$(document.body).setImgView(500, 100);
//$(document.body).setImgView(500, 'auto');
//]]>
