<?php
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
define("TOKEN", "token");
$wjfzObj = new wechatCallbackapi();

//下面是识别验证or消息回复
$wjfzObj->valid();//验证成功后请注释掉这句，并将下面一句的注释删掉
//$wjfzObj->responseMsg();


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
				$msgType = "text";
				$contentStr = '收到你的消息。';
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
			}elseif($MsgType == 'image'){
				$msgType= "text";
				$contentStr= "这张照片不错哟~";
				$resultStr= sprintf( $textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
			}elseif($MsgType == 'location'){
				$msgType = "text";
				$contentStr = "你的纬度是北纬：".$Location_X."，你的经度是东经：".$Location_Y;
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
			}elseif($MsgType == 'voice'){
				$msgType = "text";
				$contentStr = "大声点，听不见";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
			}elseif($MsgType == 'video'){
				$msgType = "text";
				$contentStr = "再来一段~ /::B";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
			}elseif($MsgType == 'event'){
				$msgType = "text";
				if($Event == 'subscribe'){
				$contentStr = "初次见面请多关照~\n回复 帮助 打开帮助\n回复 菜单 打开菜单";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
				}

				$contentStr = "无法识别的事件类型。";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
				exit;
			}
		}else{
			echo "By:wjfz E-mail:b4n@foxmail.com";
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
