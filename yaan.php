<?php
header("Content-type: text/html; charset=utf-8");

/**
*
* 微信demo文件
*
*
*作者：王俊锋泽 
*
*博客：http://www.xn--7tqt52e1pef42b.cn/
*
*邮箱:b4n@foxmail.com
*
*
*/
define("TOKEN", "yaan");
$wjfzObj = new wechatCallbackapi();

if($_GET["echostr"]){
$wjfzObj->valid();
}else{
$wjfzObj->responseMsg();
}

class wechatCallbackapi{

	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
	
    public function responseMsg()
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr))
		{
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$MsgType = $postObj->MsgType;
			$time = time();
			$label = $postObj->Label;
			$Location_X = $postObj->Location_X;
			$Location_Y = $postObj->Location_Y;
			$PicUrl = $postObj->PicUrl;
			$Event = $postObj->Event;
			
			$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";
			
			//如果要加星标请用这个模板
			$xingbiaoTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>1</FuncFlag>
						</xml>";
						
					if($MsgType == 'text')
					{
						$url = "http://opendata.baidu.com/api.php?resource_id=6109&format=json&ie=utf-8&oe=utf-8&query=".urlencode($keyword)."&from_mid=1&cb=bd__cbs__v29lzb";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$data = curl_exec($ch);
						curl_close($ch);
						$data = str_replace(array('bd__cbs__v29lzb(',');'),array(''), $data);
						$a = json_decode($data,true);
						
						$str = "获取某人信息请只发送姓名。如果要发具体的寻人信息请大家上百度填写寻人信息，微信暂不支持。\n";
						foreach ($a[data][0][disp_data] as $value) {
							$str .= $value[name]."\n".$value[age]."岁\n".$value[sex]."\n".$value[desc]."。电话：".$value[phone]."\n".$value[remarks]."\n\n";
						}
						$str .= "\n如果要发具体的寻人信息请大家上百度填写寻人信息，微信暂不支持。";
						
						$msgType = "text";
						$contentStr = trim($str);
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
						exit;
					}elseif($MsgType == 'image'){
						$msgType= "text";
						$contentStr= "不支持";
						$resultStr= sprintf( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr); 
						echo $resultStr;
						exit;
					}elseif($MsgType == 'location'){
						$msgType = "text";
						// $contentStr = "你的纬度是北纬：".$Location_X."，你的经度是东经：".$Location_Y;
						$contentStr = "不支持";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
						exit;
					}elseif($MsgType == 'voice'){
						$msgType = "text";
						$contentStr = "不支持语音";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
						exit;
					}elseif($MsgType == 'video'){
						$msgType = "text";
						$contentStr = "不支持";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
						exit;
					}elseif($MsgType == 'event'){
						$msgType = "text";
						if($Event == 'subscribe'){
						$contentStr = "直接发送姓名查询";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
						exit;
                        }
						
						$contentStr = "无法识别。";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
						exit;
					}else{
						$msgType= "text";
						$contentStr= "我不知道你在做什么~~";
						$resultStr= sprintf( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr); 
						echo $resultStr;
						exit;
					}
		}else{
		echo "如果想让你的微信成为地震搜人微信，请在微信开发模式中填入本网页地址：http://yaan.sinaapp.com/index.php   在token处填写：yaan";
		exit;
		}
	}
	
	
	private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
}
?>
