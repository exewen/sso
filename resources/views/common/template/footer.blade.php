<div class="footer">
    <div class="pull-right">
        PatPat is a registered trademark of InterFocus Inc. All Rights Reserved.
    </div>
    <div>
        <strong>Copyright</strong> PatPat Community &copy; 2016-2020
    </div>
</div>
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<script>
function keyup_submit(e){
    var evt = window.event || e;
    if (evt.keyCode == 13){
        try{
            var head_pathname = window.location.pathname;
            
            var paramsData =  $('#search_form').serialize();
            var customPage = $('input[id=customPage]').val().trim();  //页码
            var customLimit = $('input[id=customLimit]').val().trim();  //每页个数

            if(customLimit>100){
                throw new Error('每页条数最大为100，请重新输入');
            }
            paramsData +='&page='+customPage;
            paramsData +='&limit='+customLimit;

            window.location.href = head_pathname+"?"+paramsData;
        } catch (e) {
                layer.closeAll();
                layer.msg(e.message, {icon: 2,time:2000});
            }
    }
}

function getJson(arr){
    var theRequest = new Object();
    for (var i = 0; i < arr.length; i++) {
        var kye = arr[i].split("=")[0];
        var value = arr[i].split("=")[1];
        // 给对象赋值
        theRequest[kye] = value;
    }
    return theRequest;
}

function checkNum(obj) {
       obj.value = obj.value.replace(/[^\d]/g,"");  //清除“数字”和“.”以外的字符 
    }

</script>