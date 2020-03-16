@extends('layouts.page')

@section('content')
    <link href="/static/admin/css/admin_role.css" rel="stylesheet" type="text/css">
    <!--右侧内容-->
    <div id="main">
        <div class="mainBox-header">
            <div class="page_h1 clearfix"><span class="action"><a href="{{route('admin.adminRole.index')}}" class="btn actionBtn">管理员角色列表</a></span> <span class="mainTitle"><?php echo isset($_GET['id']) ? '编辑管理员角色' : '添加管理员角色'?></span></div>
        </div>
        <div class="mainBox mainBox-form">
            <div class="frame-form">
                <form method="post" action="" name="form">
                    @csrf
                    <table width="100%" border="0" cellpadding="7" cellspacing="0" class="tableBasic frame-common-table-form frame-table-form" >
                        <tbody>
                        <tr>
                            <td align="right" class="form-item-name"><span>管理员角色名称：</span></td>
                            <td><input type="text" name="name" value="@isset($fields['name']){{$fields['name']}}@endisset" size="40" class="wx2">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="form-item-name"><span>权限列表：</span></td>
                            <td>
                                <div class="checkboxs">
                                    @foreach($permissions['tops'] as $group)
                                    <dl>
                                        <dt class="main-permission"><span class="ctrl-box"><label class="s-some"><input type="checkbox" name=""><i></i><u>{{$group['name']}}</u></label></span></dt>
                                        <dd>
                                            @foreach($permissions['permissions'][$group['id']] as $permission)
                                            <dl>
                                                <dt class="sub-permission"><span class="ctrl-box"><label><input type="checkbox"><i></i><u>{{$permission['name']}}</u></label></span></dt>
                                                <dd>
                                                    <ul>
                                                        @foreach($permission['permissions'] as $controller=>$_permission)
                                                        <li>
		                          <span class="ctrl-box"><label><input type="checkbox" name="permissions[]" value="{{$controller}}" <?php
                                              if(isset($fields['permissions'])) {
                                                  if (in_array_case($controller, $fields['permissions'])) {
                                                      echo ' checked="checked"';
                                                  }
                                              }?>><i></i><u>{{$_permission}}</u></label></span>
                                                        </li>
                                                            @endforeach
                                                    </ul>
                                                </dd>
                                            </dl>
                                            @endforeach
                                        </dd>
                                    </dl>
                                    @endforeach
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td align="right" class="form-item-name"><span>是否启用：</span></td>
                            <td><div class="radio-inline"><label for="state_enabled">
                                        <input type="radio" name="enabled" id="state_enabled" value="1" @isset($enabled)@if($enabled == 1) checked="checked"@endif @endisset>
                                        启用</label></div>
                                <div class="radio-inline"><label for="state_disable"><input type="radio" name="enabled" id="state_disable" value="0" @isset($enabled)@if($enabled == 0) checked="checked"@endif @endisset>
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
    <script>
        $(function (){
            checkboxSetter = {
                init: function (el){
                    var owner = this;
                    this.el = $(el);
                    this.el.find('dt input:checkbox').click(function (){
                        owner.selectAllSub(this);
                        label = $(this).closest('label').removeClass('s-some');
                    });
                    this.el.find('li input:checkbox').click(function (){
                        owner.update2State(this);
                    });
                    this.el.find('li').each(function (){
                        var chk = $('input:checkbox', this).eq(0);
                        owner.update2State(chk);
                    });
                },
                selectAllSub: function (chk){
                    var dl = $(chk).closest('dl');
                    dl.find('dd input:checkbox').prop('checked', chk.checked);
                    this.update1State(chk);
                },
                update2State: function (chk){
                    var allSub = $(chk).closest('ul').find('input:checkbox');
                    var box = $(chk).closest('dl');
                    var parent = box.children('dt').find('input:checkbox');
                    var label = parent.closest('label');
                    var ed = allSub.filter(':checked').length;
                    var isAll = ed == allSub.length;
                    var css = "s-some";
                    parent.prop('checked', isAll);
                    if(box.find(':checked').length && !isAll){
                        label.addClass(css);
                    }else{
                        label.removeClass(css);
                    }
                    this.update1State(parent);
                },
                update1State: function (chk){
                    var allSub = $(chk).closest('dd').find('dt input:checkbox');
                    var box = $(chk).closest('dd').closest('dl');
                    var parent = box.children('dt').find('input:checkbox');
                    var label = parent.closest('label');
                    var ed = allSub.filter(':checked').length;
                    var isAll = ed == allSub.length;
                    var css = "s-some";
                    parent.prop('checked', isAll);
                    if(box.find(':checked').length && !isAll){
                        label.addClass(css);
                    }else{
                        label.removeClass(css);
                    }
                }
            };
            checkboxSetter.init('div.mainBox form');
        });
    </script>


@endsection