<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>文件上传</title>
    <script src="/static/global/js/jquery.js"></script>
    <script src="/static/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
    <link href="/static/admin/css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify.css">
    <link rel="stylesheet" type="text/css" href="/static/global/css/uploadify.css">
</head>
<body>
<style type="text/css">
    body {
        background-color: #fff;
    }
    .form-btns {
        text-align: center;
        border-top: 1px solid #D6D6D6;
        padding: 10px;
        position: fixed;
        bottom: 0;
        right: 0;
        left: 0;
        background: #fff;
    }

</style>

<div class="uploadify-up">
    <form>
        <div id="queue"></div>
        <input id="file_upload" name="file_upload" type="file" multiple="true">
    </form>
</div>

<div class="form-btns">
    <button type="button" class="btn" id="confirm">文件上传</button>
</div>

<script type="text/javascript">
    <?php $timestamp = time();?>
    $(function() {
        var fileTypeExts  = <?php echo json_encode($exts);?>;
        var fileSizeLimit = <?php echo isset($sizeLimit) ? json_encode($sizeLimit) : json_encode(0);?>;
        var btnText  = '请选择文件';
        var _close   = true;
        var is_multi = <?php echo isset($is_multi) ? $is_multi : false;?>;
        var multi_num = <?php echo isset($multi_num) ? $multi_num : false;?>;

        $('#file_upload').uploadify({
            'formData' : {
                'timestamp'     : '<?php echo $timestamp;?>',
                'token'         : '<?php echo md5('unique_salt' . $timestamp);?>',
                'upload_config' : '<?php echo $config;?>'
            },

            'multi'    : is_multi,
            'queueSizeLimit'       : is_multi ? multi_num : 1,
            'fileTypeExts'         : fileTypeExts,
            'fileSizeLimit'        : fileSizeLimit,
            'auto'                 : false,
            'swf'                  : '/static/uploadify/uploadify.swf',
            'uploader'             : '{{route('admin.upload.up')}}',
            'overrideEvents'       : ['onUploadSuccess'],
            'buttonText'           : btnText,
            'onQueueComplete'      : function(queueData) {
                if (_close) {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                }
            },

            'onSelectError'        : function (file, errorCode, errorMsg) {
                var msgText ="";
                switch (errorCode) {
                    case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                        msgText += "最多只能上传 " + this.settings.queueSizeLimit + "个文件";
                        break;
                    case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                        msgText += file.name+"文件大小超过限制( " + this.settings.fileSizeLimit + " )";
                        break;
                    case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                        msgText += file.name+"文件大小为0";
                        break;
                    case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                        msgText += file.name+"文件格式不正确，仅限 " + this.settings.fileTypeExts;
                        break;
                    default:
                        msgText += "错误代码：" + errorCode + "\n" + errorMsg;
                }
                alert(msgText);
            },

            'onUploadError'          : function (file, errorCode, errorMsg, errorString) {
                // 手工取消不弹出提示
                if (errorCode == SWFUpload.UPLOAD_ERROR.FILE_CANCELLED
                    || errorCode == SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED) {
                    return;
                }
                var msgText = "上传失败\n";
                switch (errorCode) {
                    case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                        msgText += "HTTP 错误\n" + errorMsg;
                        break;
                    case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
                        msgText += "上传文件丢失，请重新上传";
                        break;
                    case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                        msgText += "IO错误";
                        break;
                    case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                        msgText += "安全性错误\n" + errorMsg;
                        break;
                    case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                        msgText += "最多上传 " + this.settings.uploadLimit + "个";
                        break;
                    case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                        msgText += errorMsg;
                        break;
                    case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
                        msgText += "找不到指定文件，请重新操作";
                        break;
                    case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                        msgText += "验证错误";
                        break;
                    default:
                        msgText += "文件:" + file.name + "\n错误码:" + errorCode + "\n"
                            + errorMsg + "\n" + errorString;
                }
                alert(msgText);
            },

            // 成功选择文件后
            'onSelect' : function () {
            },

            'onUploadSuccess'      : function(file,data,response){
                var data = eval('(' + data + ')');
                if (data.status == false) {
                    _close = false;
                    alert(data.msg);
                }else {
                    _close = true;
                    parent.setUploadFiles(data, <?php echo json_encode($upload_id)?>, is_multi, <?php echo json_encode($config)?>);
                }
            },
        });
    });

    // 确定上传
    $('#confirm').click(function() {
        $('#file_upload').uploadify('upload','*');
    });

</script>