<div class="upload-images">
    <a href="{{route('admin.upload.uploadify')}}"

       @if(($is_multi == true) || empty($files))
       style="display: block;"
       @else
    style="display: none;"
    @endif
    onclick="window.openLayerUploadProxy('{{$upload_id}}', '{{route('admin.upload.uploadify',['upload_config'=>encrypt($config), 'is_multi'=>$is_multi, 'multi_num'=>$multi_num, 'upload_id'=>encrypt($upload_id)])}}', '文件上传', ['310px', '411px'],0.6);return false;" class="btnUpload"></a>
    <input id="{{$upload_id}}" name="{{$upload_name}}" value="{{$fids}}" class="fids" type="hidden" />
    <span class="{{$config}}" style="display:none"></span>
    <input name="multi_num" value="{{encrypt(($is_multi == 0) ? 1 : $multi_num)}}" type="hidden" />
    <span data-main="@if($setMain == true){{1}}@else{{0}}@endif" class="_setMain" style="display:none"></span>
    <span data-main="@if($is_multi == false){{0}}@else{{1}}@endif" class="_multi" style="display:none"></span>
    <div class="thumbs">
        @foreach($files as $vo)
        <div class="images-thumb">
            <img data-md5="{{$vo['md5']}}" src="{{$vo['path']}}" onload="fitUploadImageSize(this)" />
            @if(($setMain == true) && ($is_multi == true))
            <span data-id="{{$vo['id']}}" class="setMain">主图</span>
            @endif
            <span data-confirm="你确定移除该图片吗？" data-id="{{$vo['id']}}" class="remove">移除</span>
        </div>
        @endforeach
    </div>
</div>
