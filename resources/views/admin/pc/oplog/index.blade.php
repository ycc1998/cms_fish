@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="mainTitle">系统操作日志</span></div>
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
                            <input name="keywords" type="text" class="keywords _search" value="@isset($_GET['keywords']){{$_GET['keywords']}}@endisset" size="20" placeholder="操作人员名称">
                            <input name="search" class="btn search go _search" type="submit" value="go">
                        </form>
                    </div>
                    <div class="subTool"><!-- <button type="button" class="btn tools" onclick="window.location.href = '{:_url('oplog/export')}'">导出操作日志</button>  -->
                    </div>
                </div>
            </div>
            @if($list->total())
            <div class="list">
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                    <tbody>
                    <tr class="">
                        <th class="col col-id col-number">{!! _order('id','ID','admin.oplog.index') !!}</th>
                        <th class="col" style="width:15%;">操作人员</th>
                        <th class="col">操作模块</th>
                        <th class="col" style="width:60px;">操作</th>
                        <th class="col">提交方式</th>
                        <th class="col" style="width:120px;">操作IP地址</th>
                        <th class="col col-datetime">{!! _order('create_time','操作时间','admin.oplog.index') !!}</th>
                    </tr>
                    @foreach($list as $vo)
                    <tr class="">
                        <td align="center">{{$vo['id']}}</td>
                        <td align="left">{{$vo['username']}}</td>
                        <td align="left">
                        <?php
                        $name = '';
                        $op_action = strtoupper($vo['action']);
                        $op_controller = strtoupper($vo['controller']);;

                        if(isset($actions[$op_controller]['ACTIONS'][$op_action]) and is_string($actions[$op_controller]['ACTIONS'][$op_action])) {
                            $name .= $actions[$op_controller]['ACTIONS'][$op_action];
                        }else {
                            if (isset($actions[$op_controller]['ACTIONS'][$op_action]) && count($actions[$op_controller]['ACTIONS'][$op_action]) >= 2) {
                                $pos = $vo['post_id'] ? 1:0;
                            }else {
                                $pos = 0;
                            }

                            if(isset($actions[$op_controller]['ACTIONS'][$op_action][$pos])) {
                                $name .= $actions[$op_controller]['ACTIONS'][$op_action][$pos];
                            }else {

                                if (isset($actions['ACTIONS'][$op_action]) and is_string($actions['ACTIONS'][$op_action])) {
                                    $name .= $actions['ACTIONS'][$op_action];
                                }else {
                                    if (isset($actions['ACTIONS'][$op_action]) and count($actions['ACTIONS'][$op_action]) >= 2) {
                                        $pos = $vo['post_id'] ? 1:0;
                                    }else {
                                        $pos = 0;
                                    }

                                    if (isset($actions['ACTIONS'][$op_action][$pos])) {
                                        $name .= $actions['ACTIONS'][$op_action][$pos];
                                    }else {
                                        $name = '#'.$op_controller . ' / ' . $op_action;
                                    }
                                }
                            }
                        }

                        if (! (substr($name, 0,1) == '#')) {
                            if (isset($actions[$op_controller][0])){
                                $name .= $actions[$op_controller][0];
                            }else {
                                $name = $op_controller . ' / '. $op_action;
                            }
                        }
                        echo '<span class="mainSubject">'.trim($name, '#').'</span>';
                        echo $vo['message'];
                        ?>
                        <td align="left">
                            @if($vo['result'] == 0)
                                <span style="color:">失败</span>
                            @else
                                <strong style="">成功</strong>
                            @endif
                        </td>
                        <td align="left">
                            @if($vo['type'] == 0)
                                GET
                            @else
                                POST
                            @endif
                            @isset($vo['post_id'])id = {{$vo['post_id']}}@endisset

                        </td>
                        <td align="left">{{$vo['ip']}}</td>
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
                        没有操作日志
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection