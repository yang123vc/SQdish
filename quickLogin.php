<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
	    <script type="text/javascript" src="/templates/theme/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="http://qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="101285136"  charset="utf-8"></script>
    </head>
    <body>
        <script>
            var user_info = {};
            window.onload = function () {
                if (QC.Login.check())
                {
                     QC.api("get_user_info", user_info)
                        .success(function (s) {//�ɹ��ص�  
                        })
                        .error(function (f) {//ʧ�ܻص�  
                        })
                        .complete(function (c) {
                            user_info.nickname = c.data.nickname;
                            user_info.figureurl = c.data.figureurl;
                            postUserInfo(user_info);
                        });
                } else {
                  window.top.location = '/login.php';
                }

            }
		        function postUserInfo(user_info) {

            $.ajax({

                type: "POST",

                async: true,

                url: "/register.php?action=qqLogin",

                contentType: "application/json", //������

                data: JSON.stringify(user_info), //�൱�� //data: "{'str1':'foovalue', 'str2':'barvalue'}",

                success: function (jsonResult) {

                    var result = eval("(" + jsonResult + ")");

                    if (result['flag'] === 1) {

                        window.top.location = "/index.php";

                    } else {

                        alert(result['msg']);

                    }

                }

            });

        }
        </script>
        <div>
	        ����Ϊ����¼��.....
	    </div>
    </body>
</html>