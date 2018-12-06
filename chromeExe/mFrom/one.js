//全局变量
var MInfo ;
//表单构建 方法
var MForm = function () {
    //私用的方法或者属性保存在that上
    var that = this ;
    //基础信息
    var init = {
        'name': 'form build' ,
        'version':'1.0.1',
        'author': 'sjm'
    } ;

    //更多提示信息
    init.more = function () {
        console.log("更多插件相关：https://developer.chrome.com/extensions （你将有机会变成插件高手!）");
    }

    //基础提示
    init.errorCode = function (httpCode) {
        var code = {
            "1xx": "1xx消息", "2xx": "2xx成功", "3xx": "3xx重定向", "4xx": "4xx客户端错误|请求错误", "5xx": "5xx服务器错误"
        };
        if (httpCode) {
            that.errorCodeBase(httpCode.toString());
        } else {
            console.log(code);
        }
    }

    //内部code提示
    that.errorCodeBase = function (httpCode) {
        var allCode =  {
            "1xx":{
                "100":"继续",
                "101":"交换协议",
                "102":"处理",
            },
            "2xx":{
                "200":"好的",
                "201":"创建",
                "202":"接受",
                "203":"非权威信息",
                "204":"没有内容",
                "205":"重置内容",
                "206":"部分内容",
                "207":"多状态",
                "208":"已经报告",
                "226":"IM已使用",
            },
            "3xx":{
                "300":"多种选择",
                "301":"永久移动",
                "302":"发现",
                "303":"见其他",
                "304":"未修改",
                "305":"使用代理",
                "307":"临时重定向",
                "308":"永久重定向",
            },
            "4xx":{
                "400":"错误请求",
                "401":"未经授权",
                "402":"需要付款",
                "403":"禁止",
                "404":"未找到",
                "405":"方法不允许",
                "406":"不可接受",
                "407":"需要代理验证",
                "408":"请求超时",
                "409":"冲突",
                "410":"已经过去了",
                "411":"所需长度",
                "412":"前提条件失败",
                "413":"有效载荷过大",
                "414":"Request-URI太长",
                "415":"不支持的媒体类型",
                "416":"请求的范围不满意",
                "417":"期望失败",
                "418":"我是一个茶壶",
                "421":"误导请求",
                "422":"不可处理的实体",
                "423":"已锁定",
                "424":"依赖关系失败",
                "426":"需要升级",
                "428":"必备前提条件",
                "429":"请求太多",
                "431":"请求标头字段太大",
                "444":"连接已关闭但没有响应",
                "451":"因法律原因不可用",
                "499":"客户关闭请求",
            },
            "5xx":{
                "500":"内部服务器错误",
                "501":"未实施",
                "502":"BadGateway",
                "503":"服务不可用",
                "504":"网关超时",
                "505":"不支持HTTP版本",
                "506":"Variant也谈判",
                "507":"存储空间不足",
                "508":"检测到环路",
                "510":"未扩展",
                "511":"需要网络验证",
                "599":"网络连接超时错误"
            }
        };
        var firstCode = httpCode[0] ;
        var msgCode = allCode[firstCode+'xx'][httpCode] ;
        if(msgCode) {
            var backCode ={};
            backCode[httpCode] = msgCode ;
            console.log(backCode)
        }else {
            console.log(allCode);
        }
    }

    //错误信息提示  外部代码需要return终止
    init.error = function (msg,code) {
        if(!code) code = 400 ;
        if(!msg)  msg  = 'error';
        console.log({"code":code,"message":msg});
    }
    
    //yii csrf验证
    init.frame = function (data,frame) {
        switch(frame) {
            case 'yii' :
                var length = document.getElementsByName('csrf-token').length ;
                if(length === 1) {
                    data['_csrf'] = document.getElementsByName('csrf-token')[0].content;
                }
                return data ;
            default :
                return data ;
        }
    };

    /**
     *  通过dom构建表单
     * @param hidData Object { 'alipay_account': 'show tables ;'} ;
     * @param url
     * @param frame
     * @param method
     */
    init.dom = function (hidData,url,frame,method) {
        if(!hidData) return ;
        if(!method) method = 'post' ;
        if(frame) {
            hidData = init.frame(hidData,frame) ;
        }
        var f = document.createElement("form");
        document.body.appendChild(f);
        for( var i in hidData ) {
            var temp = document.createElement("input");
            temp.type = "hidden";
            f.appendChild(temp);
            temp.value = hidData[i];
            temp.name = i ;
        }
        /*JSON.stringify()
        JSON.parse()*/
        f.action = url;
        f.method = method;
        f.submit();
    };

    //设置 header 表单
    init.herderDom = function (data,url,header,frame,method) {
        if(!data)   return ;
        if(!method) method = 'POST';
        //xml对象
        var xmlhttp = new XMLHttpRequest();
        //目标地址
        xmlhttp.open(method, url,true);
        //设置header
        if(header) {
            for( var i in header ) {
                xmlhttp.setRequestHeader(i, header[i]);
            }
        }
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        if(frame) {
            data = init.frame(data,frame) ;
        }
        //数据设置
        var content = '';
        for (var d in data) {
            content += '&'+d+'='+data[d] ;
        }
        xmlhttp.send(content) ; //内容格式根据Content-type设置的格式而定
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                //成功
                console.log(xmlhttp.responseText);
            }
        }
    };

    //自动登录 当设置了MInfo的值后 调用auto自动登录
    init.auto = function () {
        if(!MInfo) {
           init.error('无效的 MInfo');
           return ;
        }
        if(!MInfo['data'] || !MInfo['url']) {
            init.error('MInfo 必须拥有 data 和 url 属性');
            return ;
        }
        init.dom(MInfo['data'],MInfo['url']);
    }
    return init ;
}();

window.onload = function (ev) {
 function exT() {
   console.log(23);
 }
}

function exT() {
    console.log(223);
}