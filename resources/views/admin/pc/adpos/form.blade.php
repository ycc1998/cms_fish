@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.adpos.index')}}" class="btn actionBtn">广告位列表</a></span> <span class="mainTitle"><?php echo isset($_GET['id']) ? '编辑广告位' : '添加广告位'?></span></div>
        </div>
        <div class="mainBox mainBox-form">
            <div class="frame-form">
                <form method="post" action="" name="form">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                        <tbody>
                        <tr>
                            <td align="right" class="form-item-name"><span>广告位名称：</span></td>
                            <td><input type="text" name="name" value="@isset($fields['name']){{$fields['name']}}@endisset" size="40" class="wx2">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>显示终端：</span></td>
                            <td>
                                <select name="type">
                                    <option value="1" @if(!empty($fields['type']))@if($fields['type']==1)checked="checked"@endif  @endif>PC</option>
                                    <option value="2"  @if(!empty($fields['type']))@if($fields['type']==2)checked="checked"@endif  @endif>移动</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>广告位模板代码：</span></td>
                            <td>
                                <textarea name="htmlcode" class="wx2">@isset($fields['htmlcode']){{$fields['htmlcode']}}@endisset</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>广告位描述：</span></td>
                            <td><input type="text" name="intro" value="@isset($fields['intro']){{$fields['intro']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>显示状态：</span></td>
                            <td> <div class="radio-inline"><label for="state_enabled">
                                        <input type="radio" name="enabled" id="state_enabled" value="1" @if(!empty($fields['enabled']))@if($fields['enabled']==1)checked="checked"@endif @else checked="checked" @endif>
                                        显示</label></div>
                                <div class="radio-inline"><label for="state_disable"><input type="radio" name="enabled" id="state_disable" value="0" @isset($fields['enabled'])@if($fields['enabled']==0)checked="checked"@endif @endisset>
                                        隐藏</label></div>
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
