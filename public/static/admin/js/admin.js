// 查看图片，文件上传缩略图
$(".upload-images, .avatar").setImgView();

$(document).ready(function() {
    // 左侧菜单折叠
    $('div#menu').delegate('h3', 'click',
    function() {
        $(this).toggleClass('_close');
        $(this).next('ul')[$(this).hasClass('_close') ? 'slideUp': 'slideDown']();
    });

    // 左侧子菜单项点击选中效果
    var opts = $('div#menu li');
    opts.click(function() {
        opts.removeClass('cur');
        $(this).addClass('cur');
    });

});

// 关闭左侧菜单
$(function() {
    var key = 'is_show_home_left_menu';
    var isHideMenu = $.cookie(key) ? $.cookie(key) : '1';
    function toggleMenu(isHide) {
        var isCookie = typeof isHide == 'boolean';
        var curIsHide = isCookie ? isHide: $('#menu').is(':hidden');
        if (curIsHide) {
            $('#menu').show();
            $('#main').css('left', '210px');
        } else {
            $('#menu').hide();
            $('#main').css('left', 0);
        }
        if (!isCookie) $.cookie(key, curIsHide ? '1': '0');
    }
    $('#close-left-menu').click(toggleMenu);
    toggleMenu(isHideMenu === '1');
});

// 下拉菜单实现
$(document).ready(function() {
    $('.M').hover(function() {
        $(this).addClass('active');
    },
    function() {
        $(this).removeClass('active');
    });
});

/**
 * 全选表单
 */
function selectcheckbox(form) {
    $('#chkall').each(function() {
        var fn = (this.checked ? 'add': 'remove') + 'Class';
        $(form).find('tr :checkbox').slice(1).prop('checked', this.checked).each(function() {
            $(this).closest('tr')[fn]('tr-checked');
        });
    });
}

function clickCheckBox() {
    if (!/all/i.test(this.id)) {
        $(this).closest('tr')[(this.checked ? 'add': 'remove') + 'Class']('tr-checked');
        var alls = $(this).closest('table').find(':checkbox').slice(1).filter(':not(:checked)').length === 0;
        $('#chkall').prop('checked', alls);
    }
}

/**
 * 页面的Checkbox不好选择，在TD上设置了事件，方便操作
 */
$('input:checkbox').click(function(e) {
    e.stopPropagation()
}); // 阻止input冒泡，要不然input会把click传到td中，变成点了也白点
$(".checkbox_td").bind('click',
function() {
    $(this).children('input').each(function() {
        this.checked = !this.checked;
        clickCheckBox.call(this);
    })
})

// 鼠标经过高亮行显示
$.fn.hoverCss = function(css) {
    css = css || 'hover';
    return this.hover(function() {
        $(this).addClass(css);
    },
    function() {
        $(this).removeClass(css);
    });
};
$('.list .tableBasic tr').hoverCss();

/**
 * 重置选中
 */
$(document).ready(function() {
    if ($("form[name='action']").length > 0) {
        $("form[name='action']")[0].reset();
    }
});

/**
 * 选项显示设置
 */
function opAction() {
    var frm = document.forms['action'];
    if (frm.elements['cid']) {
        frm.elements['cid'].style.display = frm.elements['action'].value == 'set-cid' ? '': 'none';
    }
}

function opActoinCheck(form) {
    if ($("#actoin_select").val() == '0') {
        alert('请选择批量操作类型');
        return false;
    }
    
    var selected = $("#actoin_select").find("option:selected");
    var txt = selected.text();
    var val = selected.val();

    if (val == 'set-delete') {
        if (!confirm('你确定' + txt + '吗？')) {
            return false;
        }
    }

    ajaxPost($(form));
    return false;
}

// 统计已选择的记录数量，用于批量执行操作使用
function showCheckCount(all, chks, chk2, showbar, tmpl) {
    function action() {
        var size = $(chks).filter(':checked').size();
        if (size) {
            tmpl = tmpl || '已选择<strong>%1</strong>记录';
            $(showbar).html(tmpl.replace('%1', size)).show();
        } else {
            $(showbar).hide();
        }
    }
    // .add将两个选择器选择的元素添加起来
    $(chks).add(chk2).add(all).click(action); // chk2为点击td有统计功能
}
$(function() {
    showCheckCount('#chkall', 'td.checkbox_td input:checkbox', 'td.checkbox_td ', '#selectedChks');
});

// 页面加载完后隐藏
$(function() {
    $('#loading').fadeOut('normal');
})

/**
 * 验证密码强度
 */
$('#vpass').keyup(function(e) {
    var okRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{6,}).*", "g");
    var txt = $('#passstrength span');
    var passstrength = $('#passstrength');
    var strength = $('#passstrength .passwordStrength');

    if (false == enoughRegex.test($(this).val())) {
        passstrength.prop('className', 'pass-trip');
        strength.val( - 1);
        txt.html('密码至少6位');
    } else if (okRegex.test($(this).val())) {
        passstrength.prop('className', 'pass-trip pass-ok');
        strength.val(2);
        txt.html('符合');
    } else {
        passstrength.prop('className', 'pass-trip pass-error');
        strength.val(1);
        txt.html('较弱');
    }
    return true;
});

document.onclick = function(e) {
    e = e || window.event;
    var target = e.target || e.srcElement;
    if (target.className.indexOf('delete') > -1) {
        var _confirm = $(target).attr('data-confirm');
        if ($.trim(_confirm) != '') {
            return confirm(_confirm);
        } else {
            return confirm('您真的要删除吗？');
        }
    }
}

function ajaxPost(form) {
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: form.serialize(),
        success: function(retDat) {
            console.log(retDat);
            if (retDat.code == '1') {
                if (retDat.data.direct == false) {
                    retDat.url = undefined;
                }
                top.success(retDat.msg, retDat.url, retDat.wait);
                $("#layer_hide_value", window.parent.document).val('success');
                var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                if (index) parent.layer.close(index);
            } else {
                top.warning(retDat.msg);
            }
        }
    });
    return false;
}

function layer_callback(){
   	var tag = $('#layer_hide_value').val();
    if(tag == "success"){
        setTimeout(function(){
            $("#layer_hide_value").val('');
            window.location.reload();
        },1000)
	}
}