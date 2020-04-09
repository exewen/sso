/**
 * Created by Bruce.He on 15/11/4.
 */
/**
 * 生成一个显示错误信息的div
 *
 * @param errors: array
 * @returns {string}
 */
function errorsDiv(errors)
{
    var errormsg_div =  '<div class="alert alert-danger alert-dismissable" role="alert">';
    errormsg_div+='<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    errormsg_div+='<ul>';
    var key;
    for(key in errors){
        errormsg_div+='<li>'+errors[key]+'</li>'
    }
    errormsg_div+='</ul>'
    errormsg_div+='</div>'
    return errormsg_div;
}

//all button dimiss focus
function btnDismissFocus() {
    $(":button").blur();
}

//set cookie,name,value,day
function setCookie(c_name,value,expiredays)
{
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=c_name+ "=" +escape(value)+
        ((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}

//get cookie by name
function getCookie(c_name)
{
    if (document.cookie.length>0)
    {
        c_start=document.cookie.indexOf(c_name + "=")
        if (c_start!=-1)
        {
            c_start=c_start + c_name.length+1
            c_end=document.cookie.indexOf(";",c_start)
            if (c_end==-1) c_end=document.cookie.length
            return unescape(document.cookie.substring(c_start,c_end))
        }
    }
    return ""
}

//remove cookie
function removeCookie(name)
{
    setCookie(name,'随便什么值，反正都要被删除了',-1);
}

//check is email
function isEmail(obj)
{
    var reg = /\w+[@]{1}\w+[.]\w+/;
    if(!reg.test(obj)){
        return false;
    }else{
        return true;
    }
}

