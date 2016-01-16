<?php

class Pocket_Action implements Widget_Interface_Do
{
    public $consumer_key;
    public $access_token;
    public $site_url;

    public function execute()
    {
        $options = Typecho_Widget::widget('Widget_Options');

        $this->site_url = $options->siteUrl;
        $this->consumer_key = $options->plugin('Pocket')->key;
        $this->access_token = $options->plugin('Pocket')->token;
    }

    public function action()
    {
        // todo
    }

    public function token()
    {
        $redirect_uri = $this->site_url.'/pocket/token-return';

        $data = [
            'consumer_key' => $this->consumer_key,
            'redirect_uri' => $redirect_uri
        ];

        $ch = curl_init('https://getpocket.com/v3/oauth/request');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $content = curl_exec($ch);
        curl_close($ch);
        parse_str($content, $content);

        $data = [
            'request_token' => $content['code'],
            'redirect_uri' => $redirect_uri.'?code='.$content['code'],
        ];

        header("Location: https://getpocket.com/auth/authorize?".http_build_query($data));
    }

    public function tokenReturn()
    {
        $data = [
            'consumer_key' => $this->consumer_key,
            'code' => $_REQUEST['code']
        ];

        $ch = curl_init('https://getpocket.com/v3/oauth/authorize');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $content = curl_exec($ch);
        curl_close($ch);
        parse_str($content, $content);

        echo $content['access_token'];
    }

}
