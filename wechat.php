<?php
/**
 * wechat php test
 */

//define your token
//和web界面的定义token要一致
define("TOKEN", "weiphp");
$wechatObj = new wechatCallbackapiTest();
// 验证方法
// $wechatObj->valid();

// 自动回复 注意：上面的验证只需要使用一次即可注释
$wechatObj->responseMsg();

class wechatCallbackapiTest {
    public function valid() {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        //数字签名
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg() {
        // 1. 接受腾讯传递参数
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if ( !empty($postStr)) {
            // 2. 不解析腾讯提交过来xml数据的实体，防止xxe攻击（xss）
            // https://security.tencent.com/index.php/blog/msg/69
            libxml_disable_entity_loader(true);
            // 3. 将xml数据转换一个xml对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            // 3. 获取xml数据里面的信息
            // 4. 手机端微信账号，不是我们理解的账号，提供对用户账号加密后一个叫openid信息
            $fromUsername = $postObj->FromUserName;
            // 5. 公众账号
            $toUsername = $postObj->ToUserName;
            // 6. 用户提交的字符
            $keyword = trim($postObj->Content);
            // 7. 生成一个时间戳
            $MsgType = $postObj->MsgType; // text
            $time = time();
            // 8. 返回给手机客户的模板
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            // %s 叫做占位符，等会使用spritf函数会把占位符的内容使用spritf函数里面的参数做替换
            //
            $imgTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Image>
						<MediaId><![CDATA[%s]]></MediaId>
						</Image>
						</xml>";

            $musicTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Music>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						<MusicUrl><![CDATA[%s]]></MusicUrl>
						<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
						</Music>
						</xml>";
            $imgarcTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>1</ArticleCount>
					<Articles>
					<item>
					<Title><![CDATA[%s]]></Title> 
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>
					</Articles>
					</xml>"; // 单条
            $videoTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Video>
					<MediaId><![CDATA[%s]]></MediaId>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					</Video> 
					</xml>";

            // 用户提交的是文本
            if ($MsgType == 'text') {
                if ( !empty($keyword)) {

                    if ($keyword == '想念熊') {
                        // 返回用户一张图片信息
                        $msgType = 'image';
                        // 图片的地址，只是这个地址是保持在腾讯服务器上
                        $MediaId = 'nQPVPgYLIw1nSjURgdRvr3dK_WcnV2U39ZT1wMZ47UFxYFnnM8f57Ln5ZD5Q9TpZ'; // 图片ID
                        $resultStr = sprintf($imgTpl, $fromUsername, $toUsername, $time, $msgType, $MediaId);
                        echo $resultStr;


                    } else {
                        if ($keyword == 'gate') {
                            $msgType = 'music';
                            $title = 'gate';
                            $description = '命运石之门';
                            $url = "http://wechat.sinsea.cn/ec/gate.mp3";
                            $hqurl = "http://wechat.sinsea.cn/ec/gate.mp3"; // 无损音乐地址
                            $MediaId = "Ovh9LGXUVpEderba_jyV0SudeJTYenKMCZrI3BRLa4S2vd8FBNsuE8ZugE6tIUJI"; // 音乐回复的封面
                            $resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType, $title, $description, $url, $hqurl, $MediaId);
                            echo $resultStr;


                        } else {
                            if ($keyword == 'miss') {
                                $msgType = 'news';
                                $title = 'Eric-047';
                                $description = '=.=!  0.0  小小博客';
                                $imgurl = 'http://wechat.sinsea.cn/ec/1.jpg';
                                $url = 'http://www.eric047.com'; // 点击图文后跳转的地址
                                $resultStr = sprintf($imgarcTpl, $fromUsername, $toUsername, $time, $msgType, $title, $description, $imgurl, $url);
                                echo $resultStr;


                            } else {
                                if ($keyword == 'How') {
                                    $msgType = 'video';
                                    $title = 'How to Love';
                                    $description = '比起说出口的我爱你，如何做才更重要';
                                    $MediaId = '_NhCGy085fZhxU04MLKjWeY4-RC3nNOJyKjTgExoKgb-Z_rb3kfeZ27AVmyLOP5n';
                                    $resultStr = sprintf($videoTpl, $fromUsername, $toUsername, $time, $msgType, $MediaId, $title, $description);
                                    echo $resultStr;


                                } else {
                                    // exit;
                                    $url = 'http://www.tuling123.com/openapi/api?info=' . $keyword . '&key=b30f497542784d12ed7e0873812bedf9';

                                    $data = file_get_contents($url);

                                    $data = json_decode($data);

                                    $msgType = "text";

                                    $contentStr = $data->text;

                                    // 9. sprintf 主要是做格式化
                                    // http://www.w3school.com.cn/php/func_string_sprintf.asp
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;

                                }
                            }
                        }
                    }


                } else {
                    echo "Input something...";
                }

            } else {
                if ($MsgType == 'image') {
                    $msgType = "text"; // 返回数据类型，默认现在使用文本
                    $contentStr = "您输入的是一张图片！";
                    // 9. sprintf 主要是做格式化
                    // http://www.w3school.com.cn/php/func_string_sprintf.asp
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                } else {
                    if ($MsgType == 'voice') {
                        $msgType = "text"; // 返回数据类型，默认现在使用文本
                        $contentStr = "您的声音很不错，动听！！";
                        // 9. sprintf 主要是做格式化
                        // http://www.w3school.com.cn/php/func_string_sprintf.asp
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    } else {
                        if ($MsgType == 'video' || $MsgType == 'shortvideo') {
                            $msgType = "text"; // 返回数据类型，默认现在使用文本
                            $contentStr = "视频内容很精彩，悠着点！";
                            // 9. sprintf 主要是做格式化
                            // http://www.w3school.com.cn/php/func_string_sprintf.asp
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            echo $resultStr;

                        } else {
                            if ($MsgType == 'location') {
                                // 获取用户的手机的维度和经度
                                $X = $postObj->Location_X; // 维度
                                $Y = $postObj->Location_Y; // 经度

                                $msgType = "text"; // 返回数据类型，默认现在使用文本
                                $contentStr = "您当前所处的维度是：{$X}， 经度是：{$Y}";
                                // 9. sprintf 主要是做格式化
                                // http://www.w3school.com.cn/php/func_string_sprintf.asp
                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;


                            } else {
                                if ($MsgType == 'link') {
                                    $msgType = "text"; // 返回数据类型，默认现在使用文本
                                    $contentStr = "您输入的网址有毒，请慎重";
                                    // 9. sprintf 主要是做格式化
                                    // http://www.w3school.com.cn/php/func_string_sprintf.asp
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;

                                }
                            }
                        }
                    }
                }
            }


        } else {
            echo "";
            exit;
        }
    }

    private function checkSignature() {
        // you must define TOKEN by yourself
        // 如果没有定义token
        if ( !defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        // 接受腾讯公众服务器发送过来的信息
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce,
        );
        // use SORT_STRING rule
        // 自然规则排序a-z A_Z
        sort($tmpArr, SORT_STRING);
        // zu cheng yi ge zi fu chuan
        $tmpStr = implode($tmpArr);
        // shi yong sha1 hanshu jiami
        $tmpStr = sha1($tmpStr);
        // he  tengxun chuandi zuo bijiao
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

?>
