<?php
/**
 * 配置文件 demo
 * 阿里云后台每增加一种配置就需要创建一个对应的配置文件
 * 
 * @author songmw<songmingwei@100tal.com>
 * @since  18.08.22
 */
namespace Timerlau\AliyunAfs\Config;
use afs\Request\V20180112\AnalyzeNvcRequest;

class Demo extends Base implements ConfigInterface {

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * （服务端）将系统代码集成的文件粘贴进来，替换对应的配置变量，里面的逻辑根据需要进行调整
     * 
     * @param string nvc
     * @return int
     */
    public function validate($nvc)
    {
        $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", 
                                                      $this->app_key, 
                                                      $this->app_secret);
        $client = new \DefaultAcsClient($iClientProfile);
	    \DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "afs", "afs.aliyuncs.com");

        // 无痕模式
        $req = new AnalyzeNvcRequest();
        $req->setData($nvc);// 必填参数，从前端获取getNVCVal函数的值
        $req->setScoreJsonStr('{"200":"PASS","400":"NC","600":"SC","800":"BLOCK"}');// 根据自己需求设置，跟前端一致
        $response = $client->getAcsResponse($req);
        return $response->BizCode;
    }

    /**
     * （前端）将系统代码集成的文件粘贴进来，替换对应的配置变量，里面的逻辑根据需要进行调整
     * tips：如果前端有做修改，则以最终前端的html为主！
     * 
     * @return string
     */
    public function frontend()
    {
        $html =<<<HTML
<div id="aliyun_captcha"></div>
<input type="hidden" name="{$this->selector_nvc_name}" class="{$this->selector_nvc_class}">
<script>

    window.NVC_Opt = {
        //无痕配置 && 滑动验证、刮刮卡通用配置
        appkey:'FFFF0N00000000006908',
        scene:'nvc_bbs',
        isH5:false,
        popUp:false,
        renderTo:'#aliyun_captcha',
        nvcCallback:function(data){
            // data为getNVCVal()的值，此函数为二次验证滑动或者刮刮卡通过后的回调函数
            // data跟业务请求一起上传，由后端请求AnalyzeNvc接口，接口会返回100或者900
            // 正常情况下，可以不用做特殊处理，如果前端处理了，则后端也需要处理
            // console.log('二次滑动后的vnc[orgi]:' + data)
            data = encodeURI(decodeURI(data).replace(new RegExp('\"','g'),"'"))
            document.querySelector('.{$this->selector_nvc_class}').value = data
            // console.log('二次滑动后的vnc[encode]:' + document.querySelector('.{$this->selector_nvc_class}').value)

            // 为了不改变js的情况下，在回调中，重新定义submit事件
            // 目前只处理使用 fastpostvalidate 提交的页面
            var form = document.querySelector('.{$this->selector_form_class}')
            var submit_function = form.attributes["onsubmit"].value

            if (submit_function.indexOf('return fastpostvalidate') > -1) {
                document.querySelector('.{$this->selector_button_class}').onclick=function(event) {
                    if (document.all) {
                        window.event.returnValue = false;
                    } else {
                        event.preventDefault();
                    }
                    if (fastpostvalidate(form, 1)){
                        form.submit()
                    }
                }
            }
        },
        trans: {"key1": "code0","nvcCode":400},
        language: "cn",
        //滑动验证长度配置
        customWidth: 300,
        //刮刮卡配置项
        width: 300,
        height: 100,
        elements: [
            '//img.alicdn.com/tfs/TB17cwllsLJ8KJjy0FnXXcFDpXa-50-74.png',
            '//img.alicdn.com/tfs/TB17cwllsLJ8KJjy0FnXXcFDpXa-50-74.png'
        ], 
        bg_back_prepared: '//img.alicdn.com/tps/TB1skE5SFXXXXb3XXXXXXXXXXXX-100-80.png',
        bg_front: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABQCAMAAADY1yDdAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAADUExURefk5w+ruswAAAAfSURBVFjD7cExAQAAAMKg9U9tCU+gAAAAAAAAAIC3AR+QAAFPlUGoAAAAAElFTkSuQmCC',
        obj_ok: '//img.alicdn.com/tfs/TB1rmyTltfJ8KJjy0FeXXXKEXXa-50-74.png',
        bg_back_pass: '//img.alicdn.com/tfs/TB1KDxCSVXXXXasXFXXXXXXXXXX-100-80.png',
        obj_error: '//img.alicdn.com/tfs/TB1q9yTltfJ8KJjy0FeXXXKEXXa-50-74.png',
        bg_back_fail: '//img.alicdn.com/tfs/TB1w2oOSFXXXXb4XpXXXXXXXXXX-100-80.png',
        upLang:{"cn":{
            _ggk_guide: "请摁住鼠标左键，刮出两面盾牌",
            _ggk_success: "恭喜您成功刮出盾牌<br/>继续下一步操作吧",
            _ggk_loading: "加载中",
            _ggk_fail: ['呀，盾牌不见了<br/>请', "javascript:noCaptcha.reset()", '再来一次', '或', "http://survey.taobao.com/survey/QgzQDdDd?token=%TOKEN", '反馈问题'],
            _ggk_action_timeout: ['我等得太久啦<br/>请', "javascript:noCaptcha.reset()", '再来一次', '或', "http://survey.taobao.com/survey/QgzQDdDd?token=%TOKEN", '反馈问题'],
            _ggk_net_err: ['网络实在不给力<br/>请', "javascript:noCaptcha.reset()", '再来一次', '或', "http://survey.taobao.com/survey/QgzQDdDd?token=%TOKEN", '反馈问题'],
            _ggk_too_fast: ['您刮得太快啦<br/>请', "javascript:noCaptcha.reset()", '再来一次', '或', "http://survey.taobao.com/survey/QgzQDdDd?token=%TOKEN", '反馈问题']
            }
        }
    }

    function yourRegisterRequest(url, params){
        var callbackName = ('jsonp_' + Math.random()).replace('.', '')
        params += '&callback=' + callbackName
        var o_scripts = document.getElementsByTagName("script")[0]
        var o_s = document.createElement('script')
        o_scripts.parentNode.insertBefore(o_s, o_scripts);
        //您注册请求的业务回调
        window[callbackName] = function(json) {
	        // console.log('人机校验[code]:' + json.result.code)
            if(json.result.code == 400) {
                // 校验的话, 需要清空 nvc 的值
		        document.querySelector('.{$this->selector_nvc_class}').value = ''
                //唤醒滑动验证
                getNC().then(function(){
                    _nvc_nc.upLang('cn', {
                        _startTEXT: "请按住滑块，拖动到最右边",
                        _yesTEXT: "验证通过",
                        _error300: "哎呀，出错了，点击<a href=\"javascript:__nc.reset()\">刷新</a>再来一次",
                        _errorNetwork: "网络不给力，请<a href=\"javascript:__nc.reset()\">点击刷新</a>",
                    })
                    _nvc_nc.reset()
                })
            } else if (json.result.code == 600) {
                // 校验的话, 需要清空 nvc 的值
		        document.querySelector('.{$this->selector_nvc_class}').value = ''
                //唤醒刮刮卡
                getSC().then(function(){})
            
            // 成功之后的逻辑，自己来写
            } else if (json.result.code == 100 || json.result.code == 200) {
                
		        // alert(json.result.code)
                //注册成功
		        // console.log('注册成功发送[encode]:' + document.querySelector('.{$this->selector_nvc_class}').value)
		        var form = document.querySelector('.{$this->selector_form_class}') 
	    	    var submit_function = form.attributes["onsubmit"].value

                if (submit_function.indexOf('return validate') > -1) {
                    validate(document.querySelector('.{$this->selector_form_class}'))

                } else if (submit_function.indexOf('return fastpostvalidate') > -1) {

                    if(fastpostvalidate(document.querySelector('.{$this->selector_form_class}'), 1)) {
			            document.querySelector('.{$this->selector_form_class}').submit()
		    	        // 提交成功后，需重置验证
		    	        document.querySelector('.{$this->selector_nvc_class}').value = ''
		            }
                } else {
                    document.querySelector('.{$this->selector_form_class}').submit()
                }

            } else if (json.result.code == 800 || json.result.code == 900) {
		        document.querySelector('.{$this->selector_nvc_class}').value = ''
                //直接拦截
                alert("fail")
            }
        }

        if (url.indexOf('?') > -1) {
            o_s.src = url + '&' + params
        } else {
            o_s.src = url + '?' + params
        }
    }

/**
 * 如页面由 jquery 才可以使用下面的方式
 */
var doc = document
jQuery(function(){
    doc.querySelector('.{$this->selector_button_class}').onclick = function(event) {
        // 防止多次提交校验，导致nvc失效
        // 第一次验证，可以走 yourRegisterRequest 
        if (doc.querySelector('.{$this->selector_nvc_class}').value == '') {
            nvc = getNVCVal()
            // console.log('第一次生成nvc[orig]:'+nvc)
            // 如果不需要，可以忽略转义处理
            var nvc = encodeURI(decodeURI(nvc).replace(new RegExp('\"','g'),"'"))
            var params = 'a=' + nvc 

            // 保存nvc第一次生成的nvc值
            doc.querySelector('.{$this->selector_nvc_class}').value = nvc
            // console.log('第一次赋值nvc[encode]:' + document.querySelector('.{$this->selector_nvc_class}').value)
            // 第一次验证，走该方法
            yourRegisterRequest('{$this->request_api}', params)
            // 任何点击后的事件，需放到 yourRegisterRequest 的回调函数中处理
            if (document.all) {
                window.event.returnValue = false;
            } else {
                event.preventDefault();
            }

        // 已经第一次验证过的话，就不再进行上面的验证，而是直接将 nvc 提交到服务端进行验证
        // 否则，nvc 会因为校验次数过多，导致认证失效
        } else {
            // console.log('滑动成功后点击nvc[encode]:' + document.querySelector('.{$this->selector_nvc_class}').value)
        }
    }
});

</script>
<script src="//g.alicdn.com/sd/nvc/1.1.112/guide.js?t={$this->t}"></script>
HTML;
        return trim($html);
    }
}
