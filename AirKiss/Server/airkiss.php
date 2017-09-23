<?php
/**
 * Call AirKiss
 *
 * @version     1.0.0 2017/09/19 20:20
 * @author      森林生灵 <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */
 
require_once 'jssdk.class.php';
$jssdk = new JSSDK('yourappid', 'yourappsecret');
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>WeChat AirKiss Test</title>
        <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    </head>
    <body>
        <h2>WeChat AirKiss Test</h2>
        <script type="text/javascript">
        wx.config({
            beta : true,
            // debug: true,
            appId: '<?php echo $signPackage['appId']; ?>',
            timestamp: '<?php echo $signPackage['timestamp']; ?>',
            nonceStr: '<?php echo $signPackage['nonceStr']; ?>',
            signature: '<?php echo $signPackage['signature']; ?>',
            jsApiList: ['configWXDeviceWiFi']
        });

        wx.ready(function() {
            wx.checkJsApi({
                jsApiList: ['configWXDeviceWiFi'],
                // 需要检测的 JS 接口列表
                success: function(res) {
                    var err_msg = res.err_msg;
                    console.log(err_msg);
                    // 以键值对的形式返回，可用的 api 值为 true，不可用为 false
                    // 如：{"checkResult":{"configWXDeviceWiFi":true},"errMsg":"checkJsApi:ok"}
                    wx.invoke('configWXDeviceWiFi', {}, function(res) {
                        err_msg = res.err_msg;
                        console.log(err_msg);
                    });
                }
            });
        });
        </script>
    </body>
</html>