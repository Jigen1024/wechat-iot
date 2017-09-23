<?php
/**
 * JSSDK
 *
 * @version     1.0.0 2017/09/19 19:49
 * @author      森林生灵 <admin@lanseyujie.com>
 * @link        https://lanseyujie.com
 * @copyright   Copyright(c) 2014-2018, lanseyujie.com
 */

class JSSDK
{
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    private function get_php_file($filename) {
        return trim(substr(file_get_contents($filename), 16));
    }

    private function set_php_file($filename, $content) {
        $fp = fopen($filename, 'w');
        fwrite($fp, '<?php exit(); ?>'. $content);
        fclose($fp);
    }

    private function createNonceStr($length = 16) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    private function httpRequest($url, $method = 'get', $data = '') {
        $curl = curl_init();
        // 设置获取的信息以文件流的形式返回而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        if ('post' == $method && !empty($data)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file('jsapi_ticket.php'));
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            // 获取 ticket
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token='. $accessToken;
            $res = json_decode($this->httpRequest($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $this->set_php_file('jsapi_ticket.php', json_encode($data));
            }
        }
        else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file('access_token.php'));
        if ($data->expire_time < time()) {
            // 获取access_token
            // 如果是企业号用以下URL获取access_token
            // $url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='. $this->appId .'&corpsecret='. $this->appSecret;
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='. $this->appId .'&secret='. $this->appSecret;
            $res = json_decode($this->httpRequest($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file('access_token.php', json_encode($data));
            }
        }
        else {
            $access_token = $data->access_token;
        }

        return $access_token;
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode
        $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = 'jsapi_ticket='. $jsapiTicket .'&noncestr='. $nonceStr .'&timestamp='. $timestamp .'&url='. $url;
        $signature = sha1($string);
        $signPackage = array(
            'appId' => $this->appId,
            'nonceStr' => $nonceStr,
            'timestamp' => $timestamp,
            'url' => $url,
            'signature' => $signature,
            'rawString' => $string
        );

        return $signPackage;
    }

}