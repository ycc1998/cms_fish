@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="mainTitle">会员列表</span></div>
        </div>
        <div class="mainBox">
            <div class="mainBox-tools clearfix">
                <div class="actions clearfix">
                    <div class="mainTool">
                        <form name="search" method="get" action="">
                            <input name="keywords" type="text" class="keywords _search" value="{{request('keywords','')}}" size="20" placeholder="请输入手机号码或电子邮件">
                            <input name="search" class="btn search go _search" type="submit" value="go">
                        </form>
                    </div>
                    <div class="subTool"></div>
                </div>
            </div>
            @if($list->total())
            <div class="list">
                <div class="statistics">
                    <span>会员总数：<strong class="red">{{$count}}</strong></span>
                    <span>今日注册：<strong class="red">{{$today_count}}</strong></span>
                    <span>昨日注册：<strong class="red">{{$yesterday_count}}</strong></span>
                    <span>新会员：<strong>{{$newest['account_name']}}</strong></span>
            </div>
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                    <tbody>
                    <tr class="">
                        <th class="col col-id col-number">{!! _order('id','UID','admin.member.index') !!}</th>
                        <th class="col col-opt" style="width:150px;">操作</th>
                        <th class="col">会员名称</th>
                        <th class="col" style="text-align: right;">帐户金额</th>
                        <th class="col" style="width:100px;">会员类型</th>
                        <th class="col" style="width:100px;">会员头像</th>
                        <th class="col" style="width:100px;">{!! _order('login_count','登录次数','admin.member.index') !!}</th>
                        <th class="col col-state" style="text-align: left;width:80px;">会员状态</th>
                        <th class="col col-datetime">{!! _order('last_login','最后登录','admin.member.index') !!}</th>
                    </tr>
                    @foreach($list as $vo)
                    <tr class="">
                        <td align="center">{{$vo['id']}}</td>
                        <td align="center">
                            <a class="opt preview" href="" onclick="openLayer('{{route('admin.member.preview',['id'=>$vo['id']])}}', '#{$vo.id} 会员信息', ['750px', '475px'],0.8,layer_callback); return false;">查看</a><span class="opt-separator"> | </span> <a href="" onclick="openLayer('{{route('admin.member.charge',['id'=>$vo['id']])}}', '#{{$vo['id']}} 会员充值', ['750px', '445px'],0.8,layer_callback); return false;" class="opt charge">充值</a> <span class="opt-separator"> | </span><a class="opt delete" href="{{route('admin.member.delete',['id'=>$vo['id']])}}" data-confirm="你确定删除该会员吗？">删除</a></td>
                        <td>{{$vo['account_name']}}</td>
                        <td align="right">{{price_format($vo['money'])}}</td>
                        <td align="left">
                            <?php
                            if ($vip = isVIP($vo['id'])) {
                                echo  '<i title="'.$vip.'">VIP会员</i>';
                            }else {
                                echo '普通会员';
                            }
                            ?>
                        </td>
                        <td align="left" class="avatar"><a target="_blank"><img src="{{getAvatarPath($vo['id'])}}" width="50" height="50" style="border-radius:50%;" /></a></td>
                        <td align="left">{{$vo['login_count']}}</td>
                        <td align="left">@if($vo['is_verify'] == 1)<strong>验证</strong>@else未验证@endif <br />
                            @if($vo['member_enabled'] == 1)<strong>启用</strong>@else禁用@endif
                        </td>

                        <td align="center">{{$vo['last_login']}}</td>
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
                        没有注册会员
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection
