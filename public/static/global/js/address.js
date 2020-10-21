function address(_url, _selects, _saveInput){
    var noopts = '<option value="-1">请选择</option>';
    function getOptsArray(id, selectId, fn){
        $.ajax({
            url: _url + '?pid=' + id, 
            dataType: 'html',
            success: function (data){
                var opts = [noopts];
                var indexData = {};
                data = $.parseJSON(data);
                for (var i = 0, j = data.length; i < j; i++) {
                    var item = data[i];
                    indexData[item.id] = item;
                    opts.push('<option' +(item.id==selectId?" selected":"")+ ' value="'+item.id+'" pid="'
                        +item.pid+'" eng_name="'+item.eng_name+'" ch_name="'+item.name+'">'
                        +item.name.replace(/自治区|特别行政区|省|.族|维吾尔|自治州/g, '')+'</option>');
                }
                fn(opts.join(''), indexData);
            }
        });
    }
    var selects = _selects;         // 三个下拉的
    var saveInput = _saveInput;     // 隐藏域 
    var indexs = [];
    function updateValue(){
        var retVal = [];
        selects.each(function (i){
            var val = this.value;
            var data = indexs[i];
            if (val != '-1' && data) {
                retVal.push(val);
            }
        });
        saveInput.val(retVal.length > 2 ? retVal.join(',') : '');
    }
    function fillSelect(queryId, index, selectId){
        if (queryId == -1) {
            selects.slice(index).html(noopts);
        }else{
            getOptsArray(queryId, selectId, function (html, indexData){
                indexs[index] = indexData;
                selects.eq(index).html(html);
                if (!selectId) {
                    selects.slice(index + 1).html(noopts);
                }            
                updateValue();
            });            
        }
    }
    var olds = [1];
    var oldAddress = $.trim(saveInput.val());
    //oldAddress = '350000,350500,350526';
    if (oldAddress) {
        olds = olds.concat(oldAddress.split(','));
    }
    $.each(olds, function (i, id){
        fillSelect(id, i, olds[i+1]);
    });
    selects.each(function (i, el){
        $(this).change(function (){
            fillSelect(this.value, i + 1);
        });
    });
}