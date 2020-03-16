@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="mainTitle">文章标签列表</span></div>
        </div>
        <div class="mainBox">
            @if($list->total())
            <div class="mainBox-tools clearfix">
                <div class="actions clearfix">
                    <div class="mainTool">
                        <form name="search" method="get" action="">
                            @csrf
                            <input name="keywords" type="text" class="keywords _search" value="{{request('keywords','')}}" size="20" placeholder="请输入文章标签名称">
                            <input name="search" class="btn search go _search" type="submit" value="go">
                        </form>
                    </div>
                    <div class="subTool"></div>
                </div>
            </div>
            <div class="list">
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                    <tbody>
                    <tr class="">
                        <th class="col col-id col-number">{!! _order('id','ID','admin.articleTags.index') !!}</th>
                        <th class="col col-opt">操作</th>
                        <th class="col">标签名称</th>
                        <th class="col col-number">{!! _order('tag_usenum','使用次数','admin.articleTags.index') !!}</th>
                        <th class="col col-datetime">{!! _order('create_time','创建时间','admin.articleTags.index') !!}</th>
                    </tr>
                    @foreach($list as $vo)
                    <tr class="">
                        <td align="center">{{$vo['id']}}</td>
                        <td align="center"><a class="opt delete" href="{{route('admin.articleTags.delete',['id'=>$vo['id']])}}"  data-confirm="你确定删除该文章标签吗？">删除</a></td>
                        <td align="left">{{$vo['tag_name']}}</td>
                        <td align="left"><a class="opt" target="_blank" href="{{route('admin.article.index',['ids'=>$vo['tag_ids']])}}">{{$vo['tag_usenum']}}</a></td>
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
                        没有文章标签
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection
