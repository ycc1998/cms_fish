<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title> 平台管理登录</title>
    <script src="/static/global/js/jquery.js"></script>

    <script>
        function refreshimage()
        {
            var cap =document.getElementById("captcha");
            cap.src=cap.src+'?';
        }

        if(!/chrome/i.test(navigator.userAgent)){
            alert('请使用Google Chrome浏览器');
            //history.back();
        }

        $(function (){
            $('input').focus(function (){
                $(this).closest('div').addClass('focus')
            }).blur(function (){
                $(this).closest('div').removeClass('focus');
            });
        })
    </script>
    <link href="/static/admin/css/login.css" rel="stylesheet" type="text/css">
</head>
<body class="login">
<div class="m-bg-bar"></div>
<div class="cen-box login-anim">
    <div class="logo">
        <img src="/static/admin/images/logo-login.png" style="width:300px;" />
    </div>
    <div class="login-box">
        <form autocomplete="off" action="{{ route('admin.login.check') }}" method="POST">
            @csrf
            <div class="title">平台管理登录</div>
            <div class="input">
                <input type="text" name="username" id="username" placeholder="请输入帐号">
            </div>
            <div class="input">
                <input type="password" name="password" id="password" placeholder="请输入密码">
            </div>
            <div class="yzm-box">
                <div class="input yzm">
                    <input type="text" name="yzm" id="yzmtext"  style="width:90px;" placeholder="请输入验证码" onfocus="";>
                </div>
                <div class="yzm_img_box">
                    <img src="{{captcha_src()}}" id="captcha" alt="验证码" onClick="refreshimage()"><span id="up_yzm" onClick="refreshimage()" title="看不清？点击更换另一个验证码。"></span>
                </div></div>
            <div class="btn-box">
                <input name="submit" class="btn" type="submit" value="登录" onclick="login(this)">
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
    <div class="links">
        <a href="">返回首页</a>
        <em class="separator">|</em>
        <a href="">忘记密码？</a>
    </div>
</div>
</body>
</html>
<script>
    function login(btn) {
        btn.value = '正在登录...';
        btn.style.background = '#ccc';
    }
</script>