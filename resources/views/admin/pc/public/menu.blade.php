<div id="menu" class="sidebar">
    @foreach($frame_menus as $kid => $_leftMenus)
    @foreach($_leftMenus as $k => $menu)
    @if(is_array($menu))
                @foreach($menu as $_k => $_menu)

                    @php
                            $subNum = 0;
                            $li_html = '';
                            foreach($_menu['items'] as $_item) {
                            if ($_item['url'] != '__NOPERMISSION__') {
                            $subNum++;
                            $_class = '';
                            $_dis = '';
                            $_controllerName = getControllerNameByUrl($_item['_url']);

                            if (strtolower($controller) == strtolower($_controllerName)) $_class = 'class="cur"';
                            if ($_item['newsNum'] > 0) $_dis = ' style="display:block;"';
                            $li_html .= '<li '.$_class.'><a href="'.$_item['url'].'">'.$_item['title'].'</a><span class="badge news-num" '.$_dis.'><em>'.$_item['newsNum'].'</em></span><i></i></li>';
                            }
                            }
                            if ($subNum == 0) {
                            continue;
                            }

                            $style = '';
                            if(getLeftMenuId($controllers, $currentController, $kid)) {
                            $style = 'style="display:block;"';
                            }

                    @endphp

                    <div class="_left_menus left_{{$kid}}" {!! $style !!}>
                        <h3><span>{{$_menu['title']}}</span></h3>
                        <ul>
                            {!! $li_html !!}
                        </ul>
                    </div>
                @endforeach
    @endif
    @endforeach
    @endforeach
</div>

<script>
    $(function() {
        $('.topMenu').click(function(){
            var key = $(this).attr('data-key');
            $('._left_menus').css('display', 'none');
            $('.left_'+key).css('display', 'block');
        });
    });
</script>