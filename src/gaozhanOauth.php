<?php
/**
 * Created by PhpStorm.
 * User: gaozhan
 * Date: 2018/3/8
 * Time: 16:51
 */
namespace oauth;

class gaozhanOauth extends oauth{
    protected $getAccessTokenUrl = 'http://localhost/oauthtest/oauthServer/accesstoken.php';  //获取accessToken地址
    protected $getRequestCodeURL = 'http://localhost/oauthtest/oauthServer/authorize.php';  //获取request_code请求的URL
    protected $callBackUrl = 'http://localhost/oauthtest/oauthClent/callback.php';  //回调地址url
    protected $getUserInfoUrl = 'http://localhost/oauthtest/oauthServer/userinfo.php';

    protected function parseToken($data)
    {
        if($data['code'] == 0 && !empty($data['data'])){
            return $data['data'];
        }else{
            throw new Exception("获取accessToken失败：{$data['message']}");
        }
    }

    public function getUserInfo()
    {
        $params = [
            'accesstoken'=>$this->Token
        ];
        $result = $this->send($this->getUserInfoUrl,$params);
        if($result['code'] == 0 && !empty($result['data'])){
           return $result['data'];
        }else{
            die('获取用户信息失败');
        }

    }
}