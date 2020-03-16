<div id="main">
    <div id="mainBox">
        <h3>信息提示</h3>
        <div id="cpmsg" class="cpmsg">
            <?php switch ($code) {?>
            <?php case 1:?>
            <h2>  <?php echo(strip_tags($msg));?></h2>
            <?php break;?>
            <?php case 0:?>
            <h2 class="fault"> <?php echo(strip_tags($msg));?></h2>
            <?php break;?>
            <?php } ?>
            <dl>
                <dt><a href="<?php echo $url; ?>">如果您不做出选择，将在 <b id="wait"><?php echo $wait; ?></b> 秒后跳转到上一个链接地址。</a></dt>
                <dd><a id="href" href="<?php echo $url; ?>">返回上一页</a></dd>
            </dl>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>
</body>
</html>