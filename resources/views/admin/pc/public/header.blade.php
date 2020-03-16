<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>{{$siteName}} - 平台管理</title>
    <script src="/static/global/js/jquery.js"></script>
    <script src="/static/global/js/common.js"></script>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <script src="/static/global/js/jquery.cookie.js"></script>
    <script src="/static/bootstrap/js/bootstrap.min.js"></script>
    <script src="/static/global/js/layer/layer.js"></script>

    <script src="/static/global/js/datapick/datePicker.js"></script>
    <link href="/static/global/js/datapick/calendar.css" rel="stylesheet" type="text/css">

    <script src="/static/global/js/jquery.tab.js"></script>
    <script src="/static/global/js/ckeditor/ckeditor.js"></script>

    <link href="/static/admin/css/admin.css" rel="stylesheet" type="text/css">
    <script src="/static/global/js/lightbox2/lightbox.js"></script>
    <link href="/static/global/js/lightbox2/lightbox.css" rel="stylesheet" type="text/css">
    <script src="/static/global/js/validate/jquery.validate.min.js"></script>
    <script src="/static/global/js/validate/messages_zh.js"></script>
    <script src="/static/global/js/enterSumbit.js"></script>


</head>
<body>
<noscript>您的浏览器不支持javascript！</noscript>
<script>
    //使用日期选择组件
    $(function() {
        $('.date-picker').calendar()
    });

    //TABS选项卡使用
    $(function() {
        $(".idTabs").idTabs();
    });
</script>