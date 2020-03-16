@extends('layouts.page')

@section('content')
    <style>
        #main .mainBox {
            height: auto !important;
        }
        .tableBasic td a {color: #0072C6;}
        .tableBasic td a:hover {text-decoration: underline;color: #0072C6;}
    </style>
    <div id="main">
        <div class="mainBox">
            <div class="indexBox">
                <div class="boxTitle">服务器信息</div>
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic">
                    <tbody><tr>
                        <td width="130" align="right">文件上传：</td>
                        <td width="160">64M</td>
                        <td width="130" align="right">服务器系统：</td>
                        <td width="180">{{$sys_info['os']}}</td>
                        <td width="130" align="right">MySQL版本：</td>
                        <td width="">{{$sys_info['mysql_version']}}</td>
                    </tr>
                    <tr>
                        <td align="right">REWRITE：</td>
                        <td>{{$sys_info['rewrite_module']}}</td>
                        <td align="right">SOCKET支持：</td>
                        <td align="left">{{$sys_info['socket']}}</td>
                        <td align="right">WEB服务器：</td>
                        <td>{{$sys_info['webserver']}}</td>
                    </tr>
                    <tr>
                        <td align="right">GD图形库：</td>
                        <td>{{$sys_info['gd']}}</td>
                        <td align="right">时区设置：</td>
                        <td align="left">{{$sys_info['timezone']}}</td>
                        <td align="right">服务器时间：</td>
                        <td>{{$sys_info['date']}}}</td>
                    </tr>
                    <tr>
                        <td align="right">PHPINFO：</td>
                        <td><a href="{:_url('index/index', ['phpinfo'=>'yes'])}" target="_blank">查看</a></td>
                        <td align="right">内存占用大小：</td>
                        <td align="left">{{$sys_info['memory_info']}}</td>
                        <td align="right">空间占用大小：</td>
                        <td>{!! $sys_info['dirsize'] !!}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="indexBox">
                <div class="boxTitle">统计信息</div>
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic">
                    <tbody><tr>
                        <td width="130" align="right">开站以来IP总量：</td>
                        <td width="160">{{$statistics->ip}}</td>
                        <td width="130" align="right">当天IP访问量：</td>
                        <td width="180">{{$statistics->intraday_ip}}</td>
                        <td width="130" align="right">昨天IP访问量：</td>
                        <td width="">{{$statistics->yesterday_ip}}</td>
                    </tr>
                    <tr>
                        <td align="right">开站以来PV总量：</td>
                        <td>{{$statistics->pv}}</td>
                        <td align="right">当天PV访问量：</td>
                        <td align="left">{{$statistics->intraday_pv}}</td>
                        <td align="right">昨天PV访问量：</td>
                        <td>{{$statistics->yesterday_pv}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection