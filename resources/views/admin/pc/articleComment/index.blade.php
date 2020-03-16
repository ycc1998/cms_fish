@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="mainTitle">文章评论列表</span>
            </div>
        </div>
        <div class="mainBox">
            <div class="mainBox-tools clearfix">
                <div class="actions clearfix">
                    <div class="mainTool">
                        <form name="search" method="get" action="">
                            <select name="type" id="type" class="_search">
                                <option value="comment" @if(request('type','') == 'comment') selected="selected"@endif>评论内容</option>
                                <option value="title" @if(request('type','') == 'title') selected="selected"@endif>文章标题</option>
                            </select>
                            <input name="keywords" type="text" class="keywords _search" value="{{request('keywords','')}}" size="20" placeholder="请输入搜索关键字">
                            <input name="search" class="btn search go _search" type="submit" value="go">
                        </form>
                    </div>
                    <div class="subTool">
                        {{--{:widget('widget/filterMenus', array('filterMenus' => $filterMenus))}--}}
                        {!! _filterMenus($filterMenus) !!}
                    </div>
                </div>
            </div>
            @if($list->total())
            <div class="list">
                <form method="post" action="{{route('admin.articleComment.doAction')}}" name="action">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic default-list-table">
                        <tbody>
                        <tr class="">
                            <th class="col col-checkbox"><input onclick="selectcheckbox(this.form)" name="chkall" type="checkbox" id="chkall" class="select-item" value="check"></th>
                            <th class="col col-opt">操作</th>
                            <th class="col" style="width:20%;">文章标题</th>
                            <th class="col">评论内容</th>
                            <th class="col" style="width:8%;">点赞</th>
                            <th class="col" style="width:50px; text-align:center;">回复</th>
                            <th class="col col-state">状态</th>
                            <th class="col col-datetime">{!! _order('create_time','提交时间','admin.articleComment.index') !!}</th>
                        </tr>
                        @foreach($list as $vo)
                        <tr class="">
                            <td align="center" class="checkbox_td"><input type="checkbox" name="ids[]" class="select-item" value="{{$vo['id']}}" title="{{$vo['id']}}"></td>
                            <td align="center">
                                <a class="opt preview" href="#" onclick="openLayer('{{route('admin.articleComment.preview',['id'=>$vo['id']])}}', '查看评论', ['740px', '428px'],0.8,layer_callback);return false;">查看</a>

                                <span class="opt-separator"> | </span><a class="opt delete" href="{{route('admin.articleComment.delete',['id'=>$vo['id']])}}" data-confirm="你确定删除该评论吗？">删除</a></td>
                            <td>
                                {{trimmed_title($vo['title'],20)}}</td>
                            <td style="color:#d0d0d0;">
                                {{trimmed_title($vo['comment'],40)}}</td>
                            <td align="left">{{$vo['praise']}}</td>
                            <td align="center">@if(empty($vo['reply']))否@else<strong>是</strong>@endif</td>
                            <td align="left">
                                <?php
                                switch($vo['is_check']) {
                                    case 1:
                                        echo '<strong>通过</strong>';
                                        break;
                                    case -1:
                                        echo '未通过';
                                        break;
                                    default:
                                        echo '未审核';
                                        break;
                                }
                                ?>
                            </td>
                            <td align="center">{{$vo['create_time']}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="do-action" id="action-div">
                        <div class="action">
                            <select id="actoin_select" name="action" onchange="opAction()">
                                <option value="0">批量操作</option>
                                <option value="set-delete">删除选中评论</option>
                                <option value="set-show">设置选中评论审核</option>
                                <option value="set-notpass">设置选中评论未通过</option>
                                <option value="set-disabled">设置选中评论隐藏</option>
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
                        没有文章评论
                    @else
                        没有搜索到结果
                    @endif
                    <a class="goback" href="javascript:history.go(-1);">返回上一页</a>
                </div>
            @endif
        </div>
    </div>
@endsection
