@extends('layouts.layer')

@section('content')

<script src="/static/global/js/ammountfix.js"></script>
<div id="main-detail" style="left:0px;">
    <div class="mainBox mainBox-form">
        <div class="frame-form">
        <form method="post" action="" name="form" id="form">
            @csrf
            <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                <tbody>
                <tr>
                    <td width="120" align="right" class="form-item-name"><span>电子邮件：</span></td>
                    <td>{{$fields['email']}}</td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"><span>手机号码：</span></td>
                    <td>{{$fields['mobile']}}</td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"><span>注册时间：</span></td>
                    <td>{{$fields['create_time']}}</td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"><span>帐户金额：</span></td>
                    <td id="userMonery">{{price_format($fields['money'])}}</td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"><span>充值后金额：</span></td>
                    <td class="after_money" id="afterMonery">{{price_format($fields['money'])}}</td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"><span>充值金额：</span></td>
                    <td>
                        <input type="text" name="charge" value="" size="40" class="" style="width:280px;" data-money="{{$fields['money']}}">
                    </td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"><span>操作备注：</span></td>
                    <td>
                        <textarea name="intro" class="" style="width: 280px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="form-item-name"></td>
                    <td>
                        @isset($fields['id'])<input type="hidden" name="id" id="" value="{{$fields['id']}}"/>@endisset
                        <button type="submit" class="btn">提交</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
</div>
<script>

    $(function() {
        $(function() {
            var el = $('input[name=charge]');
            el.ammountFix({
                // min: 10,         // 最小值
                // defaultValue: '',
                // max: 50000,      // 最大值
                isMinus: true,      // 允许负号
                isFloat: true,      // 允许为小数
                digits: 2           // 小数点位数
            });

            el.keyup(function (){
                var el = this;
                setTimeout(function (){
                    var val = parseFloat(el.value) || 0;
                    var old = parseFloat(el.getAttribute('data-money')) || 0;
                    var after = '￥' + (val + old).toFixed(2);
                    $('#afterMonery').html(after);
                }, 0);
            });
        });
    });

    var error_placment = function (error, element){
        $(element).after(error);
        $(error).addClass('error_message help-inline');
    }

    $(function(){
        var vali = $('#form').validate({
            errorElement:"span",
            errorClass:"err",
            errorPlacement:error_placment,
            rules : {
                charge : {
                    required : true,
                },
                intro : {
                    required : true,
                },
            },
            messages : {
                charge : {
                    required : '请填写充值金额',
                },
                intro : {
                    required : '请填写充值备注',
                },
            },
            submitHandler : function(){
                ajaxPost($('#form'));
                return false;
            },
        });
    });
</script>
@endsection