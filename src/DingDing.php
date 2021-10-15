<?php

namespace Topfunction\Topfunction;

/**
 * 钉钉报警
 * Class DingDing
 * @package Topfunction\Topfunction
 */
class DingDing
{

    public $url   = "";
    public $title = "";
    public $debug = true;


    /**
     *
     * @param array  $data
     * @param string $msgtype text  link markdown actionCard
     * @return boolean
     */
    public function send($message, $msgtype = 'text')
    {
        if (is_array($message)) {
            $message = json_encode($message, 320);
        }

        // 1.标题
        if (empty($this->title)) {
            return [
                'errcode' => 1,
                'errmsg'  => 'title不能为空',
            ];
        }
        $title = '【' . $this->title . '】' . date('Y-m-d H:i:s');

        // 2.请求接口
        $title .= "\n" . '请求接口：' . "\n" . @$_SERVER['SERVER_NAME'] . @$_SERVER['REQUEST_URI'];

        // 3.请求参数
        $request = request()->param();
        if (is_array($request)) {
            $request = json_encode($request, 320);
        }
        $title .= "\n" . '请求参数：' . "\n" . $request;

        // 4.上一页
        $title .= "\n" . '上一页：' . "\n" . @urldecode($_SERVER['HTTP_REFERER']);

        $data = [
            'msgtype' => $msgtype,
            'text'    => [
                'content' => $title . "\n" . "\n" . "########### 信 息 ###########" . "\n" . $message
            ],
        ];

        $data_string = json_encode($data, 302);

        if (empty($this->url)) {
            return [
                'errcode' => 1,
                'errmsg'  => 'url不能为空',
            ];
        }

        return $this->request_by_curl($this->url, $data_string);
    }


    private function request_by_curl($remote_server, $post_string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json;charset=utf-8']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        if ($this->debug) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
