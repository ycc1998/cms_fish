@extends('layouts.page')

@section('content')
<!--右侧内容-->
<div id="main">
    <div class="mainBox-header">
        <div class="page_h1 clearfix"><span class="mainTitle">素材文件列表</span></div>
    </div>
    <div class="mainBox">
        <div class="mainBox-tools clearfix">
            <div class="actions clearfix">
                <div class="mainTool">
                    <form name="search" method="get" action="">
                        <select name="album_name" id="album_name" class="_search">
                            <option value="0">查看所有相册</option>

                            @if(!empty($albums))

                            @foreach($albums as $k=>$vo)
                            <option value="{{$k}}" @if($k == request('album_name')) selected="selected"@endif>{{$vo}}</option>
                            @endforeach
                            @endif
                        </select>
                        <input name="keywords" type="text" class="keywords _search" value="{{request('keywords')}}" size="20" placeholder="请输入文件名称">
                        <input name="search" class="btn search go _search" type="submit" value="go">
                    </form>
                </div>
                <div class="subTool"></div>
            </div>
        </div>
        @if($list->total())
        <div class="list">
            <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                <tbody>
                <tr class="">
                    <th class="col col-id col-number">{!! _order('id','ID','admin.file.index') !!}</th>
                    <th class="col col-opt">操作</th>
                    <th class="col">文件名称</th>
                    <th class="col" style="width:10%;">上传会员</th>
                    <th class="col" style="width:10%;">相册名称</th>
                    <th class="col" style="width:120px;">文件大小</th>
                    <th class="col col-datetime">{!! _order('create_time','上传时间','admin.links.index') !!}</th>
                </tr>
                @foreach($list as $vo)
                <tr class="">
                    <td align="center">{{$vo['id']}}</td>
                    <td align="left"><a href="{{$vo['path']}}" name="imageurl"></a><a target="_blank" class="opt download" href="{{route('admin.file.download',['id'=>$vo['id']])}}">下载</a><span class="opt-separator"> | </span><a class="opt delete" href="{{route('admin.file.delete',['id'=>$vo['id']])}}" data-confirm="你确定删除该文件吗？">删除</a></td>
                    <td>{{$vo['name']}}</td>
                    <td align="left">{{accountName($vo['uid'])}}</td>
                    <td align="left">{{albumName($vo['config'])}}</td>
                    <td align="left">{{get_real_size($vo['size'])}}</td>
                    <td align="center">{{$vo['create_time']}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wraper">
            {{$list->render()}}
            <span class="total">共{{$list->total()}}记录，每页{{$list->perPage()}}</span>
        </div>
        @else
        <div class="no-content">
            @if(empty($_GET['search']))
                没有上传文件素材
            @else
                没有搜索到结果
            @endif
            <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
        </div>
        @endif
    </div>
</div>
<script>

    $(function (){
        var box;
        function createBox(){
            if (!box) {
                box = $('<div></div>');
                box.css({
                    position: 'absolute',
                    width: 180,
                    height: 'auto',
                    border: '1px solid #ddd',
                    padding: 5,
                    background: '#fff',
                    overflow: 'hidden',
                    boxShadow: "rgba(111, 111, 111, 0.498039) 2px 2px 5px"
                });
                box.appendTo(document.body);
            }
        }
        $('.list').on('mouseenter', 'tr', function (){
            if($(this).index() == 0) return; // 排除标题行
            createBox();
            var href = 	new String($('a[name=imageurl]', this).attr('href'));
            // 扩展名为图片才显示预览
            var suffix = href.substring(href.lastIndexOf('.')+1, href.length).toLowerCase();
            if (($.inArray(suffix, ['', 'gif','png','jpeg', 'bmp', 'jpg']))) {
                box.html('<img src="'+href+'" style="1px solid #eee;width:100%">');
                box.css({
                    top: $(this).offset().top - 5,
                    right: 0
                });
                box.stop().fadeIn();
            }
        }).on('mouseleave', 'tr', function (){
            createBox();
            box.stop().fadeOut();
        })
    })

</script>
@endsection