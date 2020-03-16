@extends('layouts.layer')

@section('content')
<!--右侧内容-->
<div id="main-detail" style="left:0px;">
    <div class="mainBox mainBox-form">
        <div class="frame-form">
            <form method="post" action="" name="form" id="form">
                @csrf
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                    <tbody>
                    <tr>
                        <td width="120" align="right" class="form-item-name"><span>文章标题：</span></td>
                        <td>{{$fields['title']}}</td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>评论内容：</span></td>
                        <td class="comment-content"><div title="{{$fields['comment']}}">{{$fields['comment']}}</div></td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>会员帐号：</span></td>
                        <td>{{$fields['account']}}</td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>提交时间：</span></td>
                        <td>{{$fields['create_time']}}</td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>显示状态：</span></td>
                        <td>

                            <?php
                            switch($fields['is_check']) {
                                case 1:
                                    echo '<strong>显示</strong>';
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
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>回复：</span></td>
                        <td>
                            <textarea name="reply" class="wx2" style="height:100px;width:500px;">@isset($fields['reply']){{$fields['reply']}}@endisset</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"></td>
                        <td>
                            @isset($fields['id'])<input type="hidden" name="id" id="" value="{$fields['id']}"/>@endisset
                            <button type="submit" class="btn">提交</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form></div>
    </div>
</div>
<style>
    .comment-content div{
        width:545px;/*容器的基本定义*/
        max-width:545px;
        height: 30px;line-height: 32px;overflow: hidden;
        white-space: nowrap;
        text-overflow:ellipsis;
    }
    #commentBox{
        position:absolute;
        border:1px solid #999;
        padding:12px 15px 15px 17px;
        background:#fff;
        width:573px;
        z-index:99;
        color: #555;
        font-size: 13px;
        line-height :24px;
        -webkit-box-shadow:2px 2px 10px #ddd;
        -moz-box-shadow:2px 2px 10px #ddd;
        max-height: 300px;
        overflow: auto;
    }
</style>
<script>
    //显示更多评论内容
    $('.comment-content').hover(function (){
        var box = $('<div id="commentBox">').appendTo(document.body).html($('div', this).html());
        if (box.height() > 30) {
            var pos = $(this).offset();
            pos.left -= 10;
            box.css(pos).hover($.noop, function (){
                $(this).remove();
            });
        }else{
            box.remove();
        }
    })
    $('.btn').click(function(){
        ajaxPost($('#form'));
        return false;
    });
</script>
@endsection