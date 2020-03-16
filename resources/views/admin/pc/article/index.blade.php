@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.article.form')}}" class="btn actionBtn add">添加文章</a></span><span class="mainTitle">文章列表</span>
            </div>
        </div>
        <div class="mainBox">

            <div class="mainBox-tools clearfix">
                <div class="actions clearfix">
                    <div class="mainTool">
                        <form name="search" method="get" action="">
                            <select name="cid" id="cid" class="_search">
                                <option value="0">请选择文章分类</option>
                                @isset($tree)
                                @foreach($tree as $item)
                                <option value="{{$item['id']}}" @if(request('cid') == $item['id']) selected="selected"@endif>{!!str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item['level'])!!}{{$item['name']}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <input name="keywords" type="text" class="keywords _search" value="{{request('keywords','')}}" size="20" placeholder="请输入搜索关键字">
                            <input name="search" class="btn search go _search" type="submit" value="go">
                        </form>
                    </div>
                    <div class="subTool">

                        {!! _filterMenusBySelect($filterMenus) !!}
                    </div>
                </div>
            </div>
            @if($list->total())
            <div class="list">
                <form method="post" action="{{route('admin.article.doAction')}}" name="action">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                        <tbody>
                        <tr class="">
                            <th class="col col-checkbox"><input onclick="selectcheckbox(this.form)" name="chkall" type="checkbox" id="chkall" class="select-item" value="check"></th>
                            <th class="col col-id col-number">{!! _order('id','ID','admin.article.index') !!}</th>
                            <th class="col col-opt">操作</th>
                            <th class="col">文章标题</th>
                            <th class="col" style="width:8%;">文章分类</th>
                            <th class="col col-state">{!! _order('enabled','状态','admin.article.index') !!}</th>
                            <th class="col col-datetime">{!! _order('create_time','添加时间','admin.article.index') !!}</th>
                            <th class="col col-datetime">{!! _order('update_time','更新时间','admin.article.index') !!}</th>
                            <th class="col col-number">{!! _order('click_num','点击次数','admin.article.index') !!}</th>
                        </tr>
                        @foreach($list as $vo)
                        <tr class="">
                            <td align="center" class="checkbox_td"><input type="checkbox" name="ids[]" class="select-item" value="{{$vo['id']}}" title="{{$vo['id']}}"></td>
                            <td align="center">{{$vo['id']}}</td>
                            <td align="center"><a class="opt edit" href="{{route('admin.article.form',['id'=>$vo['id']])}}">编辑</a><span class="opt-separator"> | </span><a class="opt delete" href="{{route('admin.article.delete',['id'=>$vo['id']])}}" data-confirm="你确定删除该文章吗？">删除</a></td>
                            <td><a href="{{route('admin.article.form',['id'=>$vo['id']])}}">{!! trimmed_title($vo['title'], 30) !!}</a></td>
                            <td align="left">@isset($categorys[$vo['cid']]){{$categorys[$vo['cid']]}}@endisset</td>
                            <td align="left">
                                @if($vo['enabled'] == 1)
                                    <strong>显示</strong>
                                @else
                                    隐藏
                                @endif

                            </td>
                            <td align="center">{{$vo['create_time']}}</td>
                            <td align="center">{{$vo['update_time']}}</td>
                            <td align="left">{{$vo['click_num']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="do-action" id="action-div">
                        <div class="action">
                            <select id="actoin_select" name="action" onchange="opAction()">
                                <option value="0">批量操作</option>
                                <option value="set-delete">删除选中文章</option>
                                <option value="set-cid">移到分类到</option>
                                <option value="set-show">设置选中文章显示</option>
                                <option value="set-disabled">设置选中文章隐藏</option>
                            </select>

                            <select name="cid" style="display: none;">
                                <option value="0">请选择所属分类</option>
                                @isset($tree)
                                    @foreach($tree as $item)
                                        <option value="{{$item['id']}}">{!!str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item['level'])!!}{{$item['name']}}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <input name="do" class="btn" type="submit" value="执行" onclick="return opActoinCheck(this.form);"><span id="selectedChks" style="display:none">已选择<strong>5</strong>记录</span>
                        </div>

                        <div class="pagination-wraper">
                            {{$list->render()}}
                            <span class="total">共{{$list->total()}}记录，每页{{$list->perPage()}}</span>
                        </div>

                    </div>
                </form>
            </div>

            @else
                <div class="no-content">
                    @if(empty($_GET['search']))
                        没有文章
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection
