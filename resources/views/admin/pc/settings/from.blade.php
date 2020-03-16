@extends('layouts.page')

@section('content')
    <style>
        .frame-common-table-form .form-item-name {width:190px;}
    </style>
    <!--右侧设置内容-->
    <div id="main" class="frame-form">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="mainTitle">系统设置</span></div>
        </div>
        <div class="mainBox mainBox-form">
            <div class="mainBox-tools clearfix">
                <div class="">
                    <div class="idTabs">
                        <ul class="main-tab">
                            <li><a href="#first" class="selected">常规设置</a></li>
                            <li><a href="#second" class="">邮件服务器</a></li>
                            <li><a href="#third" class="">阿里大于短信</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <form action="" method="post" name="form" id="form">
                @csrf
                <div id="first" class="" style="display: block;">
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form" style="">
                        <tbody><tr>
                            <th style="text-align: right;">设置名称</th>
                            <th style="text-align:center">设置内容</th>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>网站名称：</span></td>
                            <td><input type="text" name="config_site_name" value="@isset($settings['site_name']){{$settings['site_name']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>网站关键字：</span></td>
                            <td><input type="text" name="config_site_keywords" value="@isset($settings['site_keywords']){{$settings['site_keywords']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>网站描述：</span></td>
                            <td>
                                <textarea name="config_site_description" class="wx2" style="height:95px;">@isset($settings['site_description']){{$settings['site_description']}}@endisset</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>ICP备案证书号：</span></td>
                            <td><input type="text" name="config_site_icp" value="@isset($settings['site_icp']){{$settings['site_icp']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>搜索关键字推荐：</span></td>
                            <td>
                                <input type="text" name="config_site_search_keywords" value="@isset($settings['site_search_keywords']){{$settings['site_search_keywords']}}@endisset" size="40" class="wx2">
                                <p class="help_message">请使用逗号分割多个搜索关键字</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>文章列表数量：</span></td>
                            <td>
                                <select name="config_article_paginate_rows">
                                    <option value="10" @isset($settings['article_paginate_rows']) @if($settings['article_paginate_rows'] == 10) selected="selected" @endif @endisset>10 / 每页</option>
                                    <option value="16" @isset($settings['article_paginate_rows']) @if($settings['article_paginate_rows'] == 16) selected="selected" @endif @endisset>16 / 每页</option>
                                    <option value="32" @isset($settings['article_paginate_rows']) @if($settings['article_paginate_rows'] == 32) selected="selected" @endif @endisset>32 / 每页</option>
                                </select>
                                <p class="help_message">文章列表分页时，每页显示多少个商品</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>文章评论自动审核：</span></td>
                            <td>
                                <div class="radio-inline"><label for="comment">
                                        <input type="radio" name="config_article_comment_check" id="comment" value="1" @isset($settings['article_comment_check']) @if($settings['article_comment_check'] == 1) checked="checked" @endif @endisset/>
                                        是</label></div>
                                <div class="radio-inline"><label for="comment_disable"><input type="radio" name="config_article_comment_check" id="comment_disable" value="0" @isset($settings['article_comment_check']) @if($settings['article_comment_check'] == 0) checked="checked" @endif @endisset/>
                                        否</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>文章分类默认排序方式：</span></td>
                            <td>
                                <div class="radio-inline"><label for="article_category_orderby_desc"><input type="radio" name="config_article_category_orderby" id="article_category_orderby_desc" value="desc" @isset($settings['article_category_orderby']) @if($settings['article_category_orderby'] == 'desc') checked="checked" @endif @endisset/>
                                        降序排序</label></div>
                                <div class="radio-inline"><label for="article_category_orderby_asc"><input type="radio" name="config_article_category_orderby" id="article_category_orderby_asc" value="asc" @isset($settings['article_category_orderby']) @if($settings['article_category_orderby'] == 'asc') checked="checked" @endif @endisset/>
                                        升序排序</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>价格显示规则：</span></td>
                            <td>
                                <select name="config_price_format">
                                    <option value="0" @isset($settings['price_format']) @if($settings['price_format'] == 0) selected="selected"@endif @endisset>不处理</option>
                                    <option value="1" @isset($settings['price_format']) @if($settings['price_format'] == 1) selected="selected"@endif @endisset>保留不为 0 的尾数</option>
                                    <option value="2" @isset($settings['price_format']) @if($settings['price_format'] == 2) selected="selected"@endif @endisset>不四舍五入，保留一位小数</option>
                                    <option value="3" @isset($settings['price_format']) @if($settings['price_format'] == 3) selected="selected"@endif @endisset>不四舍五入，不保留小数</option>
                                    <option value="4" @isset($settings['price_format']) @if($settings['price_format'] == 4) selected="selected"@endif @endisset>先四舍五入，保留一位小数</option>
                                    <option value="5" @isset($settings['price_format']) @if($settings['price_format'] == 5) selected="selected"@endif @endisset>先四舍五入，不保留小数</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>货币格式：</span></td>
                            <td><input type="text" name="config_currency_format" value="@isset($settings['currency_format']){{$settings['currency_format']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>默认时间显示格式：</span></td>
                            <td>
                                <input type="text" name="config_date_formate" value="@isset($settings['date_formate']){{$settings['date_formate']}}@endisset" size="40" class="wx2">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>统计代码：</span></td>
                            <td>
                                <textarea name="config_site_statistics" class="wx2" style="height:95px;">@isset($settings['site_statistics']){!! $settings['site_statistics'] !!}@endisset</textarea></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>是否关闭注册：</span></td>
                            <td>
                                <div class="radio-inline"><label for="radio_register_enabled"><input type="radio" name="config_site_register_enabled" id="radio_register_enabled" value="1" @isset($settings['site_register_enabled']) @if($settings['site_register_enabled'] == 1) checked="checked"@endif @endisset/>
                                        打开</label></div>
                                <div class="radio-inline"> <label for="radio_register_disable"> <input type="radio" name="config_site_register_enabled" id="radio_register_disable" value="0" @isset($settings['site_register_enabled']) @if($settings['site_register_enabled'] == 0) checked="checked"@endif @endisset/>
                                        关闭</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>启用访问统计：</span></td>
                            <td>
                                <div class="radio-inline">
                                    <label for="visit">
                                        <input type="radio" name="config_site_visit" id="visit" value="1" @isset($settings['site_visit']) @if($settings['site_visit'] == 1) checked="checked"@endif @endisset/>
                                        打开</label></div>
                                <div class="radio-inline"><label for="visit_disable"><input type="radio" name="config_site_visit" id="visit_disable" value="0" @isset($settings['site_visit']) @if($settings['site_visit'] == 0) checked="checked"@endif @endisset/>
                                        关闭</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>是否关闭网站：</span></td>
                            <td>
                                <div class="radio-inline"><label for="radio_enabled"><input type="radio" name="config_site_enabled" id="radio_enabled" value="1" @isset($settings['site_enabled']) @if($settings['site_enabled'] == 1) checked="checked"@endif @endisset/>
                                        打开</label></div>
                                <div class="radio-inline"> <label for="radio_disable"> <input type="radio" name="config_site_enabled" id="radio_disable" value="0" @isset($settings['site_enabled']) @if($settings['site_enabled'] == 0) checked="checked"@endif @endisset/>
                                        关闭</label></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="second" class="" style="display: none;">
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form" style="border-top: none;">
                        <tbody><tr>
                            <th style="text-align: right;">设置名称</th>
                            <th style="text-align:center">设置内容</th>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>SMTP服务器：</span></td>
                            <td><input type="text" name="config_email_smtp" value="@isset($settings['email_smtp']){{$settings['email_smtp']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>服务器端口：</span></td>
                            <td><input type="text" name="config_email_port" value="@isset($settings['email_port']){{$settings['email_port']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>是否SSL安全协议：</span></td>
                            <td>
                                <div class="radio-inline"> <label for="ssl_enabled">
                                        <input type="radio" name="config_email_ssl" id="ssl_enabled" value="1" @isset($settings['email_ssl'])@if($settings['email_ssl'] == 1 || $settings['email_ssl'] == '') checked="checked"@endif  @endisset/>
                                        是</label></div>
                                <div class="radio-inline"> <label for="ssl_disabled"> <input type="radio" name="config_email_ssl" id="ssl_disabled" value="0" @isset($settings['email_ssl'])@if($settings['email_ssl'] == 0) checked="checked"@endif @endisset/>
                                        否</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>发送邮箱：</span></td>
                            <td><input type="text" name="config_email_serverusername" value="@isset($settings['email_serverusername']){{$settings['email_serverusername']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>发送邮箱密码：</span></td>
                            <td><input type="password" name="config_email_serverpassword" value="@isset($settings['email_serverpassword']){{$settings['email_serverpassword']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div id="third" class="" style="display: none;">
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form" style="border-top: none;">
                        <tbody><tr>
                            <th style="text-align: right;">设置名称</th>
                            <th style="text-align:center">设置内容</th>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>APPKEY：</span></td>
                            <td><input type="text" name="config_dayu_appkey" value="@isset($settings['dayu_appkey']){{$settings['dayu_appkey']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>SECRETKEY：</span></td>
                            <td><input type="password" name="config_dayu_secretkey" value="@isset($settings['dayu_secretkey']){{$settings['dayu_secretkey']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>SIGNNAME：</span></td>
                            <td><input type="text" name="config_dayu_signname" value="@isset($settings['dayu_secretkey']){{$settings['dayu_secretkey']}}@endisset" size="40" class="wx2"></td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>身份验证模板ID：</span></td>
                            <td><input type="text" name="config_dayu_verifymobiletpl" value="@isset($settings['dayu_verifymobiletpl']){{$settings['dayu_verifymobiletpl']}}@endisset" size="40" class="wx2"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form" style="border-top: none;">
                    <tr>
                        <td align="right" class="form-item-name"></td>
                        <td>
                            <a href="javascript:void(0)" class="btn save"><span>保存设置</span></a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <script>
        // 设置触发表单事件
        $(function(){
            $('a.save').click(function(){
                $("#form").submit();
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
                ignore: "",
                onkeyup:false,
                errorClass:"err", // 这玩意是添加到input中的
                errorPlacement:error_placment,
                rules : {
                    config_site_name : {
                        required : true,
                    },
                },
                messages : {
                    config_site_name : {
                        required : '请输入站点设置名称',
                    },
                },
                submitHandler : function(){
                    ajaxPost($('#form'));
                },
                showErrors : function(errorMap, errorList) {
                    this.defaultShowErrors();
                    changeTab();
                }
            });
        });
        $('input:last').enterSumbit(); // 最后一个input元素，回车时提交表单
        function changeTab()
        {
            var a     = $('.idTabs li').find('a[class=selected]');
            var divId = a.attr('href').substr(1);
            var err   = $('#'+divId).find('span.err:visible').length;
            if (err > 0) {
                return false;
            } else {
                var lis   = $('.idTabs li').find('a');
                $.each(lis, function() {
                    var id       = $(this).attr('href').substr(1);
                    var labers   = $('#'+id).find('span.err');
                    $.each(labers, function(){
                        if (($(this).css('display')).indexOf('inline') === 0) {
                            $(".idTabs").idTabs(id);
                            return false;
                        }
                    });
                });
            }
        }
    </script>
@endsection