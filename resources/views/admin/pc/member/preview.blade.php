@extends('layouts.layer')

@section('content')
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
                        <td align="right" class="form-item-name"><span>最后一次登录时间：</span></td>
                        <td>{{$fields['last_login']}}</td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>最后一次登录IP：</span></td>
                        <td>{{$fields['last_loginip']}}</td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>登录次数统计：</span></td>
                        <td>{{$fields['login_count']}}</td>
                    </tr>

                    <tr>
                        <td align="right" class="form-item-name"><span>注册时间：</span></td>
                        <td>{{$fields['create_time']}}</td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>状态：</span></td>
                        <td>
                            <div class="radio-inline"><label for="state_enabled">
                                    <input type="radio" name="member_enabled" id="state_enabled" value="1"
                                           @isset($member_enabled)
                                           @if($member_enabled == 1 ) checked="checked"@endif
                                            @endisset
                                    >
                                    启用</label></div>
                            <div class="radio-inline"><label for="state_disable">
                                    <input type="radio" name="member_enabled" id="state_disable" value="0"
                                           @isset($member_enabled)
                                           @if($member_enabled == 0 ) checked="checked" @endif
                                            @endisset>
                                    禁用</label></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>是否已验证：</span></td>
                        <td>
                            <div class="radio-inline"><label for="verify_yes">
                                    <input type="radio" name="is_verify" id="verify_yes" value="1"

                                           @isset($is_verify)
                                           @if($is_verify == 1 ) checked="checked"@endif
                                            @endisset>
                                    是</label></div>
                            <div class="radio-inline"><label for="verify_no">
                                    <input type="radio" name="is_verify" id="verify_no" value="0"
                                           @isset($is_verify)
                                           @if($is_verify == 0 ) checked="checked"@endif
                                            @endisset>
                                    否</label></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>是否已验证：</span></td>
                        <td>
                            @if($fields['is_verify'] == 1)<strong>是</strong>@else 否 @endif
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
            </form></div>
    </div>
</div>
<script>
    $('.btn').click(function(){
        ajaxPost($('#form'));
        return false;
    });
</script>
@endsection