<?php
namespace app\index\controller;

use think\Loader;
use gmars\qiniu\Qiniu;
use Endroid\QrCode\QrCode;
use PHPMailer\PHPMailer\PHPMailer;
use PHPExcel_IOFactory;
use PHPExcel;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
	
	public function test() {
		$qiniu = new Qiniu();
		$result = $qiniu->upload();
		var_dump($result);
	}
	
	public function qrcode() {
		$qrCode=new QrCode();
		$url = 'https://www.baidu.com';//加http://这样扫码可以直接跳转url
		$qrCode->setText($url)
			->setSize(300)//大小
		->setLabelFontPath(VENDOR_PATH.'endroid\qrcode\assets\noto_sans.otf')
		->setErrorCorrectionLevel('high')
		->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
		->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
		->setLabel('推广码')
			->setLabelFontSize(16);
		header('Content-Type: '.$qrCode->getContentType());
		echo $qrCode->writeString();
		exit;
	}
	
	public function phpmailer() {
		//实例化
		$mail=new PHPMailer();
		try{
		    //邮件调试模式
			$mail->SMTPDebug = 2;  
			//设置邮件使用SMTP
			$mail->isSMTP();
			// 设置邮件程序以使用SMTP
			$mail->Host = 'smtp.ym.163.com';
			// 设置邮件内容的编码
			$mail->CharSet='UTF-8';
			// 启用SMTP验证
			$mail->SMTPAuth = true;
			// SMTP username
			$mail->Username = 'ylxt@lightfrog.cn';
			// SMTP password
			$mail->Password = 'peng253811';
			// 启用TLS加密，`ssl`也被接受
			$mail->SMTPSecure = 'tls';
			// 连接的TCP端口
			// $mail->Port = 587;
			//设置发件人
			$mail->setFrom('ylxt@lightfrog.cn', '测试iFeeder');
			// 添加收件人1
			$mail->addAddress('793073644@qq.com', 'qq');     // Add a recipient
			// $mail->addAddress('ellen@example.com');               // Name is optional
			// 收件人回复的邮箱
			// $mail->addReplyTo('fajian@aliyun.com', 'fajian');
			// 抄送
			// $mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');
			//附件
			// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			//Content
			// 将电子邮件格式设置为HTML
			$mail->isHTML(true);
			$mail->Subject = '邮件主题';
			$mail->Body    = '邮件正文部分';
//			$mail->AltBody = '这是非HTML邮件客户端的纯文本';
			$mail->send();
			echo 'Message has been sent';
			$mail->isSMTP();
		}catch (Exception $e){
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}
	
	public function phpexcel() {
		$path = dirname(__FILE__); //找到当前脚本所在路径
		$PHPExcel = new PHPExcel(); //实例化PHPExcel类，类似于在桌面上新建一个Excel表格
		$PHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
		$PHPSheet->setTitle('demo'); //给当前活动sheet设置名称
		$PHPSheet->setCellValue('A1','姓名')->setCellValue('B1','身份证号');//给当前活动sheet填充数据，数据填充是按顺序一行一行填充的，假如想给A1留空，可以直接setCellValue('A1','');
		$PHPSheet->setCellValue('A2','张三')->setCellValue('B2',' '.'131126199502192410');
		$PHPSheet->getColumnDimension('B')->setAutoSize(true);
		$PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//按照指定格式生成Excel文件，'Excel2007'表示生成2007版本的xlsx，'Excel5'表示生成2003版本Excel文件
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
		//header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
		header('Content-Disposition: attachment;filename="01simple.xlsx"');//告诉浏览器输出浏览器名称
		header('Cache-Control: max-age=0');//禁止缓存
		$PHPWriter->save("php://output");
	}
	
	public function feedparser(){
		Loader::import('FeedParser.FeedParser', EXTEND_PATH);
		$xml = file_get_contents("http://36kr.com/feed");
		$feed = new \FeedParser($xml);
		echo '<b>Type:</b>'.$feed->getFeedType()."<br/>";
		echo '<b>Title:</b>'.$feed->getTitle()."<br/>";
		echo '<b>Description:</b>'.$feed->getDescription()."<br/>";
		echo '<b>Feed link:</b>'.$feed->getFeedLink()."<br/>";
		echo '<b>Link:</b>'.$feed->getLink()."<br/>";
		
		$items = $feed->getItems();
		
		// Stuff in your items can be empty, so you should somehow handle it.
		// I've prepared is_empty function for you - enjoy.
		$i=1;
		foreach($items as $item)
		{
			//Because we have interface for items, we invoke interface methods
			echo "<h1>";
			if(is_empty($item->getLink()))
				echo '<a href="#">';
			else
				echo '<a href="'.$item->getLink().'">';
		
			if(is_empty($item->getTitle()))
				echo "No title";
			else
				echo "$i. ".$item->getTitle();
			echo "</a>";
		
			echo "</h1>";
		
			if(is_empty($item->getPubDate()))
				echo "<i>"."No date"."</i><br/>";
			else
				echo "<i>".$item->getPubDate()."</i><br/>";
		
			if(is_empty($item->getContent()))
				echo "<i>"."No content"."</i><br/>";
			else
				echo $item->getContent()."<hr/>";
		
			$i++;
		}
		
	}
}
