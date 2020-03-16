@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.adminRole.form')}}" class="btn actionBtn add">添加管理员角色</a></span> <span class="mainTitle">管理员角色列表</span></div>
        </div>
        <div class="mainBox">
            @if($list->total())
            <div class="list">
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                    <tbody>
                    <tr class="">
                        <th class="col col-id col-number">{!! _order('id','ID','admin.adminRole.index') !!}</th>
                        <th class="col col-opt">操作</th>
                        <th class="col">角色名称</th>
                        <th class="col col-state">{!! _order('enabled','状态','admin.adminRole.index') !!}</th>
                        <th class="col col-datetime">{!! _order('create_time','添加时间','admin.adminRole.index') !!}</th>
                        <th class="col col-datetime">{!! _order('update_time','更新时间','admin.adminRole.index') !!}</th>
                    </tr>
                    @foreach($list as $vo)
                    <tr class="">
                        <td align="center">{{$vo['id']}}</td>
                        <td align="center"><a class="opt edit" href="{{route('admin.adminRole.form',['id'=>$vo['id']])}}">编辑</a><span class="opt-separator"> | </span><a class="opt delete" data-confirm="你确定删除该管理员角色吗？" href="{{route('admin.adminRole.delete',['id'=>$vo['id']])}}">删除</a></td>
                        <td>{{$vo['name']}}</td>
                        <td align="left">

                            @if($vo['enabled'] == 1)
                                <strong>启用</strong>
                            @else
                                禁用
                            @endif
                        </td>
                        <td align="center">{{$vo['create_time']}}</td>
                        <td align="center">{{$vo['update_time']}}</td>
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
                        没有网站管理角色
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection