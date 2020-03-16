@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action">
    <a href="{{route('admin.articleCategory.form')}}" class="btn actionBtn add">添加文章分类</a></span> <span class="mainTitle">文章分类</span></div>
        </div>
        <div class="mainBox">
            @if($list)
            <div class="list">
                <form method="post" action="{{route('admin.articleCategory.doAction')}}" name="action">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                        <tbody>
                        <tr class="">
                            <th class="col col-id col-number">{!! _order('id','ID','admin.articleCategory.index') !!}</th>
                            <th class="col col-opt">操作</th>
                            <th class="col">分类名称</th>
                            <th class="col">{!! _order('position','排序','admin.articleCategory.index') !!}</th>
                            <th class="col col-datetime">{!! _order('create_time','添加时间','admin.articleCategory.index') !!}</th>
                            <th class="col col-datetime">{!! _order('update_time','更新时间','admin.articleCategory.index') !!}</th>
                        </tr>
                        @foreach($list as $vo)
                        <tr class="">
                            <td align="center">{{$vo['id']}}</td>
                            <td align="center"><a class="opt edit" href="{{route('admin.articleCategory.form',['id'=>$vo['id']])}}">编辑</a><span class="opt-separator"> | </span><a class="opt delete" href="{{route('admin.articleCategory.delete',['id'=>$vo['id']])}}" data-confirm="你确定删除该文章分类吗？">删除</a></td>
                            <td><a href="{{route('admin.articleCategory.form',['id'=>$vo['id']])}}">{!!str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $vo['level'])!!}{{$vo['name']}}</a> ({{$vo['articles']}})</td>
                            <td align="left"><input class="position" name="position[{{$vo['id']}}]" value="{{$vo['position']}}" onfocus="this.select();" tabindex="{{$vo['id']}}" /></td>
                            <td align="center">{{$vo['create_time']}}</td>
                            <td align="center">{{$vo['update_time']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="do-action" id="action-div">
                        <input name="do" class="btn" type="submit" value="更新排序" onclick="return confirm('确定更新排序？');">
                    </div>
                </form>
            </div>
            @else
                <div class="no-content">
                    @if(empty($_GET['search']))
                        没有文章分类
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection
