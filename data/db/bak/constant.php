<?php
	//header("Location:http://".$_SERVER['SERVER_NAME'].'/stop.html');
	//exit();
	
    define("SMS_CODE", "sms_code"); //短信验证码session名

    define("CHECK_CODE_SESSION", "check_code_session"); // 图形验证码session名
    
    define("USERSESSION", "user_sesssion"); //用户登录所存session

    define("FIND_PWD_TAG", "find_pwd_tag"); //找回密码所用的session
    
    define("ADMIN_SESSION", "admin_session"); // 后台管理员session

    define("PARTNER_SESSION", "partner_session"); // 合作者session

    define("DESIGNER_SESSION", "designer_session"); // 设计师session

    define("TAOBAO", "taobao"); //淘宝用户购买时给他的链接会启用这个session
	
	define("MUID","muid");//喜帖id
	
	define("STEP","step");//喜帖创建步骤，session中(1,2,3,4)5为喜帖创建成功,数据库-->0：开始创建喜帖,1:第二步,2:第三步,3:第四步,4:完成创建
	
	define("MID","moduleId");//喜帖模板id
	
	define("PIC","picPath");//喜帖文件婚纱照存储路径

	define("PATH","path");//喜帖文件生成路径
	
	define("QRPIC","QRCodePic");//二维码生成路径
	
	define("FLAG_READ_PIC","flag_read_pic");//二维码生成路径
	
	define("XITIE","xitie");//登录查看是否已有喜帖
	
	define("URL","http://www.xitieba.com");//网站域名
	
	define("TAOBAO_PRICE","10");//淘宝价格
	
	define("PRE","pre_select");//登录前选择的模板，session
	
	define("HEAD_PATH","/wx_view/mobile_logo.jpg");//头像默认地址

	define("MIN_CODE_PRICE", 5); // 合作者激活码的最低(大批量)销售价格

	define("MIN_CODE_NUM", 400); // 合作者批量购买时的自定义最少个数

	define("PARTNER_ROOT", "/hzz_mg"); // 合作者根目录

	define("ADMIN_ROOT", "/xtb_mg"); // 管理员后台根目录

    define("DESIGNER_ROOT", "/sjs_mg"); // 设计师后台根目录

    define("ADMIN_TYPE", "admin_type"); // 管理员类型,登录的时候赋予的值，存到session

    define("CLIENT_BELONG", "client_belong"); //CPS时用，看客户属于谁(partner_id)

    define("INVITE_NOTE", "恭请 %s 莅临我们的婚礼，见证我们的幸福！"); // 指定宾客拼起来的话

    define("POST_PRICE", 10); // 默认邮费

    define("POST_PRICE_OTHER", 20); // 新疆，西藏地区邮费

    define("POST_PRICE_HK", 50); // 港澳台地区邮费

    define("WEDDING_KEYWORDS_URL", "http://www.xitieba.com/,http://www.xitieba.com/,http://www.xitieba.com/a/index/index.html"); // 新婚喜帖关键词链接

    define("BABY_KEYWORDS_URL", "http://www.xitieba.com/,http://www.xitieba.com/,http://www.xitieba.com/a/index/list2.html"); // 宝宝请柬关键词

    define("BUSINESS_KEYWORDS_URL","http://www.xitieba.com/,http://www.xitieba.com/,http://www.xitieba.com/a/index/list3.html"); //  商业邀请关键词