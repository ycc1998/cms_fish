<?php
// 仅在列表页面时显示加载动画
if ($actionName == 'index') {
    echo '<div id="loading" style="border-radius: 8px; border:solid 1px #eee; background-color: #eee;padding:16px;"><img style="position:absolute;top:20%;left:20%;" src="/static/admin/images/loader.gif" width="37" height="37" alt="loading" /></div>';
}

$currentController = $controller;
switch(strtolower($controller)) {
    // case strtolower('xxxControllerName'):
    //     $currentController = 'yyyControllerName';
    // break;
}
?>

<div id="top">
    <div class="navbar-header">
        <div class="logo"><a href="{{route('admin.index')}}"><img src="/static/admin/images/logo-main.png" /><span style="display:none"></span></a></div>
        <div id="close-left-menu" title="关闭左侧菜单"></div>
    </div>
    <div class="nav">

        <ul class="navLeft">
            @foreach($frame_menus as $k => $menu)
            @if(isset($controllers[$k]))
            <li @php echo (in_array(strtolower($currentController), $controllers[$k])) ? 'class="curTopNav"' : '';@endphp><a href="javascript:void(0);" onclick="return false;" class="topMenu" data-key="{{$k}}">{{$menu['name']}}</a>
            </li>
            @endif
            @endforeach
        </ul>
        <ul class="navRight">
            <li class="noLeftBorder message_ico M">
                <p class="sub_menu"><i></i><span class="badge"><em><?php echo $message_num > 100 ? '99' : $message_num; ?></em></span>
                </p>
                <div class="mContent message-Content" style="right:0">
                <?php
                if ($message_num == 0) {
                    echo '<a href="" style="font-size:15px;line-height:60px;height:60px;background-color:#eee;">没有提示消息</a>';
                }else {
                    foreach((array) $message_array as $item) {
                        printf("<a href=\"%s\">%s</a>", $item['url'], $item['message']);
                    }
                }
                ?>
            </li>
            <li class="M">
                <a class="sub_menu" href="#">查看站点</a>
                <div class="mContent"> <a href="{{$www_home}}" target="_blank">网站首页</a> <a href="{{$mobile_home}}" target="_blank">移动版</a> </div>
            </li>
            <li class="M"><a class="sub_menu" href="#">您好，{{$username}}</a>
                <div class="mContent">

                    <a href="{{route('admin.loginlog.index')}}">我的登录记录</a>
                    <a href="{{route('admin.login.out')}}" onclick="return confirm('确定退出系统？');">退出</a>
                </div>
            </li>
            <li class="noRightBorder"><a href="{{route('admin.login.out')}}" onclick="return confirm('确定退出系统？');">退出</a></li>
        </ul>
    </div>
</div> <!--左侧菜单开始-->

<script>

    //切换主菜单CUR 高亮状态
    $(function(){
            var top = $('div.nav ul.navLeft li');
            top.click(function (){
                top.removeClass('curTopNav');
                $(this).addClass('curTopNav');
            });
        }
    );
</script>
<input id="layer_hide_value" name="layer_hide_value" value="">