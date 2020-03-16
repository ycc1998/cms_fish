@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.admin.index')}}" class="btn actionBtn">管理员列表</a></span> <span class="mainTitle"><?php echo isset($_GET['id']) ? '编辑管理员' : '添加管理员'?></span></div>
        </div>

        <div class="mainBox mainBox-form">
            <div class="frame-form">
                <form method="post" action="" name="form">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                        <tbody>
                        <tr>
                            <td align="right" class="form-item-name"><span>管理员名称：</span></td>
                            <td><input type="text" name="username" value="@isset($fields['username']){{$fields['username']}}@endisset" size="40" class="wx2">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>登录密码：</span></td>
                            <td><input type="password" name="password" value="" size="40" class="wx2" id="vpass"><div id="passstrength"><input type="hidden" name="passwordStrength" class="passwordStrength" value="0"><span></span></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>重复密码：</span></td>
                            <td><input type="password" name="password_repeat" value="" size="40" class="wx2"><span class="help_message help-inline">两次密码必须一致</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>所属管理员角色：</span></td>
                            <td>

                                @foreach($adminRoles as $k=>$role)
                                <div class="checkbox-inline"><label for="{{$role['id']}}"><input name="admin_roles[]" value="{{$role['id']}}" id="{{$role['id']}}" type="checkbox" @if(in_array($role['id'], $admin_roles))  checked="checked"@endif>
                                        {{$role['name']}}</label></div>

                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>是否启用：</span></td>
                            <td><div class="radio-inline"><label for="state_enabled">
                                        <input type="radio" name="admin_enabled" id="state_enabled" value="1" @isset($admin_enabled)@if($admin_enabled == 1) checked="checked"@endif @endisset>
                                        启用</label></div>
                                <div class="radio-inline"><label for="state_disable"><input type="radio" name="admin_enabled" id="state_disable" value="0" @isset($admin_enabled)@if($admin_enabled == 0) checked="checked"@endif @endisset>
                                        禁用</label></div>
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
@endsection