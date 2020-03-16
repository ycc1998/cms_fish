
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="/static/global/js/jquery.js"></script>
    <title>插入已有的标签</title>
    <style>
        body{
            margin:0px;
            background:#E3E3C7;
            border-width:0px
        }
        #Mid{
            font: 12px Verdana, Tahoma, sans-serif;
            height:250px;
            overflow:auto;
            background:#F1F1E3;
        }
        #Bottom{
            border-top:1px solid #D5D59D;
            padding:8px;
            color:#737357;
            text-align:center;
        }
        input{
            border:1px solid #737357;
            color:#3B3B1F;
            background:#C7C78F;
            font-size:12px;
        }
        a{
            display:block;
            background:#D7D79F;
            padding:4px;
            font-size:12px;
            color:#3B3B1F;
            margin:4px;
            border:1px solid #D7D79F;
            text-decoration:none;
        }
        a:hover{
            background:#EFEFDA;
            border:1px solid #D7D79F;
        }
        #close_window {
            padding:2px 6px;
        }
    </style>

</head>
<body scroll="no">
<div id="Mid">
    @foreach($tags as $vo)
    <a href="#" onclick="insertTags(this.title)" title="{{$vo['tag_name']}}">{{$vo['tag_name']}} ({{$vo['tag_usenum']}})</a>
    @endforeach
</div>
<div id="Bottom"><input id="close_window" type="button" value="关闭" />
</div>
</body>
</html>

<script type="text/javascript">
    $("#close_window").click( function () {
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    });
    parent.window.tagnum = 0;
    function insertTags(tagName) {
        if (parent.window.tagnum < 5) {
            var getTagObj = parent.window.document.getElementById('tags');
            var tags;
            if (getTagObj.value.length > 0) {
                tags=getTagObj.value.split(",")
                for (i=0; i<tags.length; i++){
                    if (tags[i].toLowerCase() == tagName.toLowerCase()) return
                }
                getTagObj.value += ","+tagName
            } else {
                getTagObj.value += tagName
            }
            parent.window.tagnum++
        }
        return false;
    }
</script>