function isMobile(value) {
    var _d =/^1[3578][01379]\d{8}$/g;                            // 电信手机号码
    var _l =/^1[34578][01256]\d{8}$/g;                           // 联通手机号码
    var _y =/^(134[012345678]\d{7}|1[34578][012356789]\d{8})$/g; // 移动手机号码
    if(_d.test(value) || _l.test(value) || _y.test(value)){
        return true; 
    } 
}

function isEmail(value) {
    reg = /^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/gi;
    if(reg.test(value))
    {
        return true;
    }
    return false;
}

function isAccount(value) {
    if (isMobile(value)) {
        return true;
    }else if(isEmail(value)) {
        return true;
    }
    return false;
}


function checkpwd(value){
    return /^((?=.*\d.*[a-z])|(?=.*[a-z].*\d))\w{6,18}$/i.test(value);
}