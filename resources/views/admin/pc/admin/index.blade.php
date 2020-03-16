@extends('layouts.page')

@section('content')
    <!--右侧内容-->
<div id="main">
    <div class="mainBox-header">
        <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.admin.form')}}" class="btn actionBtn add">添加管理员</a></span> <span class="mainTitle">管理员列表</span></div>
    </div>
    <div class="mainBox">
        @if($list->total())
        <div class="list">
            <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                <tbody>
                <tr class="">
                    <th class="col col-id col-number">{!! _order('id','ID','admin.admin.index') !!}</th>
                    <th class="col col-opt">操作</th>
                    <th class="col col-username">用户名</th>
                    <th class="col">所属角色</th>
                    <th class="col col-state">{!! _order('_state','状态','admin.admin.index') !!}</th>
                    <th class="col" style="width:120px;">最近登录IP</th>
                    <th class="col col-datetime">{!! _order('last_login','最后登录','admin.admin.index') !!}</th>
                    <th class="col col-datetime">{!! _order('create_time','添加时间','admin.admin.index') !!}</th>
                    <th class="col col-datetime">{!! _order('update_time','更新时间','admin.admin.index') !!}</th>
                </tr>
                @foreach($list as $vo)
                <tr class="">
                    <td align="center">{{$vo['id']}}</td>
                    <td align="center"><a class="opt edit" href="{{route('admin.admin.form',['id'=>$vo['id']])}}">编辑</a><span class="opt-separator"> | </span><a class="opt delete" data-confirm="你确定删除该管理员吗？" href="{{route('admin.admin.delete',['id'=>$vo['id']])}}">删除</a></td>
                    <td align="left"><strong>{{$vo['username']}}</strong></td>
                    <td>
                        <?php
                        $roles = !(empty($vo['admin_roles'])) ? json_decode($vo['admin_roles']) : [];
                        $_roles = [];
                        foreach($allRoles as $key=>$value) {
                            if (in_array($key , $roles)) {
                                $_roles[] = $value;
                            }
                        }
                        echo implode("，", $_roles);
                        ?>
                    </td>
                    <td align="left">
                        @if($vo['admin_enabled'] == 1)
                        <strong>启用</strong>
                        @else
                        禁用
                        @endif
                    </td>
                    <td align="left">{{$vo['last_loginip']}}</td>
                    <td align="center">{{date('Y-m-d H:i:s',$vo['last_login'])}}</td>
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
                    没有网站管理员
                @else
                    没有搜索到结果
                @endif
                <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
            </div>
        @endif
    </div>
</div>
@endsection