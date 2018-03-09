<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/3/8
 * Time: 16:33
 */
namespace oauth;

abstract class oauth{

    protected $AppKey;   //appkey
    protected $AppSecret; //appsecret
    protected $Token; //token

    protected $getAccessTokenUrl = '';  //获取accessToken地址
    protected $getRequestCodeURL = '';  //获取request_code请求的URL
    protected $callBackUrl = '';  //回调地址url
    protected $config;  //配置

    public function __construct($config)
    {
        //获取应用配置
        if(empty($config['APP_KEY']) || empty($config['APP_SECRET'])){
            throw new Exception('请配置您申请的APP_KEY和APP_SECRET');
        } else {
            $this->AppKey    = $config['APP_KEY'];
            $this->AppSecret = $config['APP_SECRET'];
            $this->Token     = isset($config['TOKEN'])?$config['TOKEN']:''; //设置获取到的TOKEN
        }
    }

    public function getAccessToken($code)
    {
        $params = [
            'app_key'=>$this->AppKey,
            'app_secret'=>$this->AppSecret,
            'code'=>$code
        ];
        $data = $this->send($this->getAccessTokenUrl,$params);
        try{
            $this->Token = $this->parseToken($data);
            return $this->Token;
        } catch (Exception $e) {
            die($e->getMessage());
        }

    }

    /**
     * 获取用来跳转第三方登录的地址
     * @return string
     */
    public function getRequestCodeURL()
    {
        $params = [
            'app_key'=>$this->AppKey,
            'callback_url'=>$this->callBackUrl
        ];
        return $this->getRequestCodeURL.'?'.http_build_query($params);
    }


    protected function send($url,$data = array()) {
        //创建一个新cURL资源
        $curl = curl_init($url);
        //设置URL和相应的选项
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //抓取URL并把它传递给浏览器
        $result = curl_exec($curl);
        //关闭cURL资源，并且释放系统资源
        curl_close($curl);
        $result = json_decode($result,true);
        return $result;
    }

    /**
     * 格式化数据 获取token
     * @param $data
     * @return mixed
     */
    abstract protected function parseToken($data);
}