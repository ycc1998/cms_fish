@extends('layouts.page')

@section('content')
<!--右侧内容-->
<div id="main">
    <div class="mainBox-header">
        <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.links.index')}}" class="btn actionBtn">友情链接列表</a></span> <span class="mainTitle"><?php echo isset($_GET['id']) ? '编辑友情链接' : '添加友情链接'?></span></div>
    </div>
    <div class="mainBox mainBox-form">
        <div class="frame-form">
            <form method="post" action="" name="form">
                @csrf
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                    <tbody>
                    <tr>
                        <td align="right" class="form-item-name"><span>网站名称：</span></td>
                        <td><input type="text" name="name" value="@isset($fields['name']){{$fields['name']}}@endisset" size="40" class="wx2">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>网站地址(http或https开头)：</span></td>
                        <td><input type="text" name="url" value="@isset($fields['url']){{$fields['url']}}@endisset" size="40" class="wx2">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>分类关键字：</span>

                        </td>
                        <td><input type="text" name="class_key" onfocus="this.select();" style="text-transform: uppercase;" onkeyup="this.value=this.value.replace(/[^0-9A-Za-z]/g, '')" value="@isset($fields['class_key']){{$fields['class_key']}}@endisset" size="40" class="wx2">
                            <span class="help_message">仅支持英文字母和数字</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>链接排序：</span></td>
                        <td><input type="text" name="position" value="@if(!empty($fields['position'])){{$fields['position']}}@else{{0}}@endif" size="40" class="" onfocus="this.select();"></td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>显示状态：</span></td>
                        <td> <div class="radio-inline"><label for="state_enabled">
                                    <input type="radio" name="enabled" id="state_enabled" value="1" @if(!empty($fields['enabled']))@if($fields['enabled']==1)checked="checked"@endif @else checked="checked" @endif >
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