@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="mainTitle">系统登录日志</span>  </div>
        </div>
        <div class="mainBox">

            <div class="mainBox-tools clearfix">
                <div class="actions clearfix">
                    <div class="mainTool">
                        <form name="search" method="get" action="">
                            <div class="time-scope-group">
                                <input name="start_date_time" type="text" data-format="@{{yyyy}}/@{{MM}}/@{{dd}} @{{hh}}:@{{mm}}:@{{ss}}" data-lt="#end_date" id="start_date" class="date-picker time-scope" value="@isset($_GET['start_date_time']){{$_GET['start_date_time']}}@endisset" size="18" placeholder="起始日期" readonly=""> <!-- data-lt="#end_date" 指定要小于结束日期 -->
                                <span class="time-scope">至</span>
                                <input name="end_date_time" data-gt="#start_date" data-format="@{{yyyy}}/@{{MM}}/@{{dd}} @{{hh}}:@{{mm}}:@{{ss}}" id="end_date" data-lt="now" type="text" class="date-picker time-scope" value="@isset($_GET['end_date_time']){{$_GET['end_date_time']}}@endisset" size="18" readonly="" placeholder="结束日期"> <!-- data-lt="#end_date" 指定要大于开始日期 data-lt="now" 指定结束日期要小于当前日期 -->
                            </div>
                            <input name="keywords" type="text" class="keywords _search" value="@isset($_GET['keywords']){{$_GET['keywords']}}@endisset" size="20" placeholder="管理员名称">
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
                        <th class="col col-id col-number">{!! _order('id','ID','admin.loginlog.index') !!}</th>
                        <th class="col">管理员名称</th>
                        <th class="col">所属角色</th>
                        <th class="col" style="width:60px;">操作</th>
                        <th class="col" style="width:120px;">登录IP地址</th>
                        <th class="col col-datetime">{!! _order('login_time','登录时间','admin.loginlog.index') !!}</th>
                    </tr>
                    @foreach($list as $vo)
                    <tr class="">
                        <td align="center">{{$vo['uid']}}</td>
                        <td align="">{{$vo['username']}}</td>

                        <td align="left">{{$vo['role']}}</td>

                        <td align="left">
                            @if($vo['result'] == 0)
                                失败
                            @else
                                成功
                            @endif
                        </td>
                        <td align="left">{{$vo['ip']}}</td>
                        <td align="center">{{date_formate($vo['login_time'])}}</td>
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
                        没有登录日志
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection