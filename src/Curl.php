<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/11/17
 * Time: 15:59
 */

namespace Seaon;

class Curl
{
    public $ch = null;
    public $timeout = 10;

    public function __construct()
    {
        if (!function_exists('curl_init')) {
            return false;
        }
        $this->ch = curl_init();
        if (!is_resource($this->ch)) {
            return false;
        }
    }

    /**
     * @param type $this->url 地址
     * @param type $fields 附带参数，可以是数组，也可以是字符串
     * @param type $httpHeaders header头部，数组形式
     * @return boolean
     */
    public function execute($fields = '', $userAgent = '', $httpHeaders = '')
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout); //设置curl超时秒数

        if ($username != '') {
            curl_setopt($this->ch, CURLOPT_USERPWD, $username . ':' . $password);
        }

        if ('post' == $this->method) {
            curl_setopt($this->ch, CURLOPT_POST, true);
            if (is_array($fields)) {
                $sets = array();
                foreach ($fields AS $key => $val) {
                    $sets[] = $key . '=' . urlencode($val);
                }
                $fields = implode('&', $sets);
            }
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields);
        } elseif ('put' == $this->method) {
            curl_setopt($this->ch, CURLOPT_PUT, true);
        }

        if (strlen($userAgent)) {
            curl_setopt($this->ch, CURLOPT_USERAGENT, $userAgent);
        }
        if (is_array($httpHeaders)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }

        return $this->exec();
    }

    public function checkHttps()
    {
        if (stripos($this->url, "https://") !== FALSE) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
    }

    private function exec()
    {
        $result = curl_exec($this->ch);
        $errno = curl_errno($this->ch);

        if ($errno !== 0) {
            $result = array($errno, curl_error($this->ch));
        }

        curl_close($this->ch);
        return $result;
    }

    public function sendData($data)
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
        ]);
    }

    public function setopt(array $opts)
    {
        foreach ($opts as $option => $value) {
//            curl_setopt($this->ch, $option, $value);
        }
    }

    public function __call($name, $args)
    {

        $method = strtolower($name);
        switch ($method) {
            case 'get':
            case 'post':
                $this->method = $name;
                break;

            default:
                return 'error method';
                break;
        }

        $this->setopt($args[0]);

        return $this->execute();
    }

}

$ch = new Curl();

$UA = [
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0',
    'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
    'Mozilla/5.0 (MSIE 9.0; qdesk 2.4.1266.203; Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.114 Safari/537.36 Vivaldi/1.9.818.50',
];

$data = [
    'url'       =>'http://www.baidu.com',
    'fields'    =>'{"arr":123}',
    'userAgent' =>array_rand($UA),
];

$result = $ch->get($data);

var_dump($result);
exit();
