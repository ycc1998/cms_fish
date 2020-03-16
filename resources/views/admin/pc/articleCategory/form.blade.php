@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.articleCategory.index')}}" class="btn actionBtn">文章分类</a></span> <span class="mainTitle"><?php echo isset($_GET['id']) ? '编辑文章分类' : '添加文章分类'?></span></div>
        </div>
        <div class="mainBox mainBox-form">
            <div class="frame-form">
                <form method="post" action="" name="form">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                        <tbody>
                        <tr>
                            <td width="120" align="right" class="form-item-name"><span>所属分类：</span></td>
                            <td>
                                <select name="pid">
                                    <option value="0">请选择所属分类</option>

                                    @isset($tree)
                                        @foreach($tree as $item)
                                            <option value="{{$item['id']}}" @if(isset($fields['pid']) && $item['id'] == $fields['pid']) 'selected="selected"';@endif>{!!str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item['level'])!!}{{$item['name']}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                                <span class="help_message">不选择所属分类则添加为一级分类</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="right" class="form-item-name"><span>分类名称：</span></td>
                            <td><input type="text" name="name" value="@isset($fields['name']){{$fields['name']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td width="120" align="right" class="form-item-name"><span>分类简介：</span></td>
                            <td><textarea name="intro" class="wx2">@isset($fields['intro']){{$fields['intro']}}@endisset</textarea></td>
                        </tr>
                        <tr>
                            <td width="120" align="right" class="form-item-name"><span>分类排序：</span></td>
                            <td><input type="text" name="position" value="@if(empty(!$fields['position'])){{$fields['intro']}}@else{{0}}@endif" size="40" class="wx2" onfocus="this.select();"></td>
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
