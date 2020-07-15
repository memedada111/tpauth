<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use org\Upload;

/**
 * 
 */
class Send extends Common
{
// 信息列表
	public function sow(){

        $m=M('message');
        $data=$m->field('id,mes,created_at')->order('created_at desc')->select();
        $this->assign('data',$data);

       return $this->fetch();

	}
	
	// 接收消息
	public function index(){
        $msg = $_POST['msg'];
        // var_dump($msg);die;
        $msg = (isset($msg)&&$msg!="")?$msg:null;
          
          $m=M('message');

          $data['mes'] = $msg;

            $res=$m->add($data);
        // if (!$msg){
        //     return $this->error("未填写信息",'product_category',"",2);
        // }
        // todo指明给谁推送，为空表示向所有在线用户推送
        $to_uid = "";
        // 推送的url地址，使用自己的服务器地址，此处使用的是虚拟域名
        $push_api_url = "http://www.tpauth.com:2121";
        $post_data = array(
            "type" => "publish",
            "content" => $msg,//json_encode($arrTest),
            "to" => $to_uid,
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        var_export($return);
        // if ($msg){
        //     return showMsg(1,"发送信息：'$msg'---".$return);
        // }else{
        //     return showMsg(0,"未填写信息");
        // }
}
}