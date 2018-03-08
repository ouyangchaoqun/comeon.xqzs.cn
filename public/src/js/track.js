(function(){
    var cookie = {
        set:function(name,value,minutes){
            var exp = new Date();
            exp.setTime(exp.getTime() + minutes*60*1000);
            document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
        },
        get:function(name){
            var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
            if(arr=document.cookie.match(reg)){
                return unescape(arr[2]);
            }
            else{
                return null;
            }
        },
        del:function(name){
            if(this.get(name)!=null){
            this.set(name,-1);
            }
        }
    }
    var utils = {
        date:{
            format:function(date,fmt){
                var o = {
                    "M+" : date.getMonth()+1,
                    "d+" : date.getDate(),
                    "h+" : date.getHours(),
                    "m+" : date.getMinutes(),
                    "s+" :date.getSeconds(),
                    "q+" : Math.floor((date.getMonth()+3)/3),
                    "S"  : date.getMilliseconds()
                };
                if(/(y+)/.test(fmt))
                    fmt = fmt.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));
                for(var k in o)
                    if(new RegExp("("+ k +")").test(fmt))
                        fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
                return fmt;
            }
        }
    }
    var _ckday = utils.date.format(new Date(),'yyyyMMdd');
    //visiter
    var visitkey = '_v'+_ckday;
    // {pv}   {uv}
    var channelOpenId = xqzs.localdb.get("channelopenid");;
    var pv = 1,uv = 1;
    if (cookie.get(visitkey)!=null){
        uv = 0;
    }else{
         cookie.set(visitkey,'1',1440);
     }

    var visiturl =web.API_PATH + 'channel/visit/track/'+channelOpenId+'?pv='+pv+'&uv='+uv;
    (new Image()).src = visiturl;
}())