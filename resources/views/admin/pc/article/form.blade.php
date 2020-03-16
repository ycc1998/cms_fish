@extends('layouts.page')

@section('content')
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.article.index')}}" class="btn actionBtn">文章列表</a></span> <span class="mainTitle"><?php echo isset($_GET['id']) ? '编辑文章' : '添加文章'?></span></div>
        </div>
        <div class="mainBox mainBox-form">
            <div class="frame-form">
            <form method="post" action="" name="form" id="form">
                @csrf
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                    <tbody>
                    <tr>
                        <td width="120" align="right" class="form-item-name"><span>所属分类：</span></td>
                        <td>
                            <select name="cid">
                                <option value="">请选择文章分类</option>
                                @isset($tree)
                                    @foreach($tree as $item)
                                <option value="{{$item['id']}}" @if(isset($fields['cid']) && $item['id'] == $fields['cid']) selected="selected"@endif>{!!str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item['level'])!!}{{$item['name']}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="120" align="right" class="form-item-name"><span>文章标题：</span></td>
                        <td><input type="text" name="title" value="@isset($fields['title']){{$fields['title']}}@endisset" size="40" class="wx2" style="width: 600px;"></td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>显示时间：</span></td>
                        <td>
                            <input name="date_time" data-format="@{{yyyy}}/@{{MM}}/@{{dd}} @{{hh}}:@{{mm}}" type="text" class="date-picker" value="@if(!empty($fields['date_time'])){{date_formate($fields['date_time'], 'Y/m/d H:i')}}@else{{date_formate(time(), 'Y/m/d H:i')}}@endif" size="15" readonly="">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>文章标签：</span></td>
                        <td>
                            <input name="tags" id="tags" type="text"  value="@isset($fields['tags']){{$fields['tags']}}@endisset" size="15" class="wx2">
                            <input name="oldtags" type="hidden"  value="@isset($fields['tags']){{$fields['tags']}}@endisset" size="15">
                            <img src="/static/admin/images/insert.gif" onclick="window.openLayer('{{route('admin.article.tags')}}', '插入标签', ['250px', '332px'], 0.6);return false;" style="cursor:pointer">
                            <span class="help_message"><a href="javascript:void(0);" onclick="alert('用“,”分隔多个标签, 标签由具体到抽象。\n\n比如：文章是“描写春天的优美句子”，则标签可以分别设置为：春天，季节。\n\n最多允许添加5个标签, 每个标签不能超过20个字符')" style="font-weight:normal;">查看标签设置相关说明</a></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>文章作者：</span></td>
                        <td><input type="text" name="author" value="@isset($fields['author']){{$fields['author']}}@endisset" size="40" class="wx2">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>文章来源：</span></td>
                        <td><input type="text" name="source" value="@isset($fields['source']){{$fields['source']}}@endisset" size="40" class="wx2">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>点击次数：</span></td>
                        <td><input type="text" name="click_num" value="@isset($fields['click_num']){{$fields['click_num']}}@endisset" size="40" class="wx2">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>文章内容：</span></td>
                        <td>
                            <textarea id="ck_content">@isset($fields['content']){{$fields['content']}}@endisset</textarea>
                            <textarea name="content" style="display: none;">@isset($fields['content']){{$fields['content']}}@endisset</textarea>

                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>推荐选项：</span></td>
                        <td>
                            <div class="checkbox-inline"><label for="is_recommend"><input type="checkbox" name="is_recommend" id="is_recommend" value="1" @if(!empty($fields['is_recommend']) && $fields['is_recommend'] == 1)checked="checked"@endif>
                                    推荐</label></div>
                            <div class="checkbox-inline"><label for="is_top" class=""><input type="checkbox" name="is_top" id="is_top" value="1" @if(!empty($fields['is_top']) && $fields['is_top'] == 1)checked="checked"@endif >
                                    置顶</label>
                            </div>
                            <div class="checkbox-inline"><label for="is_hot"><input type="checkbox" name="is_hot" id="is_hot" value="1" @if(!empty($fields['is_hot']) && $fields['is_hot'] == 1)checked="checked"@endif>
                                    热门</label></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>文章状态：</span></td>
                        <td>
                            <div class="radio-inline"><label for="state_enabled">
                                    <input type="radio" name="enabled" id="state_enabled" value="1" @if(!empty($enabled) && $enabled == 1)checked="checked"@endif >
                                    显示</label></div>
                            <div class="radio-inline"><label for="state_disable"><input type="radio" name="enabled" id="state_disable" value="0" @if(isset($enabled) && $enabled == 0)checked="checked"@endif>
                                    隐藏</label></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"><span>文章相册：</span></td>
                        <td style="padding-top:0">
                            {{--{:Widget('common/upload/imageUpload', ['images', 'album', isset($fields['album']) ? $fields['album'] : '', false, false])}--}}
                            {!! _imageUpload('images', 'album', isset($fields['album']) ? $fields['album'] : '', false, false) !!}
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="form-item-name"></td>
                        <td>@isset($fields['id'])<input type="hidden" name="id" id="" value="{{$fields['id']}}"/>@endisset
                            <button type="submit" class="btn" id="submit_form">保存文章</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    </div>
    <script>


        //创建内容编辑器
        var editor = CKEDITOR.replace('ck_content',{
            height:350,
            uiColor : '#f4f4f4',
            removePlugins: 'elementspath',
            resize_enabled : false,
            allowedContent: true, // 关闭标签过滤
            filebrowserUploadUrl: '{{route('admin.upload.editorup')}}', //图片上传路径
        })

        // 设置触发表单事件
        $(function(){
            $('#submit_form').click(function(){
                $("[name=content]").val(CKEDITOR.instances.ck_content.getData());
                $("#form").submit();
                return false;
            });
        });

        var error_placment = function (error, element){
            $(element).after(error);
            $(error).addClass('error_message help-inline');
        }

        $(function(){
            //表单验证
            var vali = $('#form').validate({
                errorElement:"span",
                errorClass:"err",
                errorPlacement:error_placment,
                rules : {
                    cid : {
                        required : true,
                    },
                    title : {
                        required : true,
                    },
                },
                messages : {
                    cid : {
                        required : '请指定文章分类',
                    },
                    title : {
                        required : '文章标题不能为空',
                    },
                },
                submitHandler : function(){
                    ajaxPost($('#form'));
                },
            });

        });

    </script>
@endsection
