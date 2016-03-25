//------------------账户设置-基本信息保存start--------------------//
$("#save_info").on("click",function(){
	var nickname = $.trim($("#nickname").val());
	var sex =  $("input[name='gender']:checked").val();
	var birthday = $.trim($("#birthday").val());
	var city = $.trim($("#city").val());
	$.post("/ajax_userinfo.php",{nickname:encodeURIComponent(nickname),gender:sex,birthday:encodeURIComponent(birthday),city:encodeURIComponent(city),flag:1},function(data){
	    alert(data.msg)
		if(data.id == 4) {
	    	window.location.reload();
	    }else{
	    	return false;
	    }
	},"json");
});
//------------------账户设置-基本信息保存end--------------------//
//---------------------极验证 代码start----------------------//
var gtFailbackFrontInitial = function (result) {
    var s = document.createElement('script');
    s.id = 'gt_lib';
    s.src = 'http://static.geetest.com/static/js/geetest.0.0.0.js';
    s.charset = 'UTF-8';
    s.type = 'text/javascript';
    document.getElementsByTagName('head')[0].appendChild(s);
    var loaded = false;
    s.onload = s.onreadystatechange = function () {
        if (!loaded && (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete')) {
            loadGeetest(result);
            loaded = true;
        }
    };
}
//get  geetest server status, use the failback solution


var loadGeetest = function (config) {

    //1. use geetest capthca
    window.gt_captcha_obj = new window.Geetest({
        gt: config.gt,
        challenge: config.challenge,
        product: 'embed',
        offline: !config.success
    });

    gt_captcha_obj.appendTo("#div_id_embed");
}

s = document.createElement('script');
s.src = 'http://api.geetest.com/get.php?callback=gtcallback';
$("#div_geetest_lib").append(s);

var gtcallback = (function () {
    var status = 0, result, apiFail;
    return function (r) {
        status += 1;
        if (r) {
            result = r;
            setTimeout(function () {
                if (!window.Geetest) {
                    apiFail = true;
                    gtFailbackFrontInitial(result)
                }
            }, 1000)
        }
        else if (apiFail) {
            return
        }
        if (status == 2) {
            loadGeetest(result);
        }
    }
})()

$.ajax({
    url: "/StartCaptchaServlet.php?rand=" + Math.round(Math.random() * 100),
    type: "get",
    dataType: 'JSON',
    success: function (result) {
        //console.log(result);
        gtcallback(result);
    }
})
//-----------------------极验证 代码end---------------------//
//------------------获取手机短信验证码start-------------------//
function getCode(obj,phone) {
	if(phone == 'bindphone') {//绑定手机页面获取验证码
		phone = $.trim($('#phone').val());
		if(!phone){
			alert("请先输入要绑定的手机号码");
			return false;
		}
		var preg_phone = /^1\d{10}$/;
		if(!preg_phone.test(phone)) {
			alert('请输入正确的手机号码');
	        return false;
		}
	}
	$.post("/ajax_userinfo.php",{phone:phone,flag:'code'},function(data){
		  obj.disabled = true;
          if (data.id  == "error") {
              alert("验证码已生成失败!请联系管理员");
              return false;
          }
          if (data.id  == "ok") {
              alert("短信验证码已发送成功!请注意查收");
              return false;
          }
	},"json");
}
//------------------获取手机短信验证码end-----------------------//
//------------------已绑定手机后修改密码start--------------------//
$("#edit_verifyphone").on("click",function(){
	var password = $.trim($("#password").val());
	var new_pwd = $.trim($("#new_pwd").val());
	var confirm_pwd = $.trim($("#confirm_pwd").val());
	var user_code = $.trim($("#user_code").val());
	var preg = /^[0-9a-zA-Z-+_!@#$%^&*()]{6,16}$/;
	if(!password) {
		alert("请输入当前使用密码");
		return false;
	}
	if(!new_pwd) {
		alert("请输入新密码");
		return false;
	}
	if(!preg.test(new_pwd)){
		alert("新密码格式不对，请重新输入");
		return false;
	}
	if(!confirm_pwd) {
		alert("请输入确认密码");
		return false;
	}
	if(new_pwd != confirm_pwd) {
		alert("两次输入密码不一致，请重新输入");
		return false;
	}
	value = gt_captcha_obj.getValidate();
	if (!value){
        alert('滑块验证未通过');
        return false;
    }
	if(!user_code) {
		alert("请输入验证码");
		return false;
	}
	$.post("/ajax_userinfo.php",{password:password,new_pwd:new_pwd,confirm_pwd:confirm_pwd,user_code:user_code,flag:3},function(data){
	    alert(data.msg)
		if(data.id == 2) {
	    	window.location.href='/login.php';
	    }else{
	    	return false;
	    }
	},"json"); 
});
//------------------已绑定手机后修改密码end--------------------//
//------------------账号安全-未绑定手机页面start---------------//
$("#account_phone").on("click",function(){
	var phone = $.trim($("#phone").val());
	if(!phone){
		alert('请输入手机号码');
        return false;
	}
	var preg_phone = /^1\d{10}$/;
	if(!preg_phone.test(phone)) {
		alert('请输入正确的手机号码');
        return false;
	}
	value = gt_captcha_obj.getValidate();
	if (!value){
        alert('滑块验证未通过');
        return false;
    }
	var user_code = $.trim($("#user_code").val());
	if(!user_code) {
		alert("请输入短信验证码");
		return false;
	}
	var verify_from = $('#account').val();
	$.post("/ajax_userinfo.php",{phone:phone,user_code:user_code,verify_from:verify_from,flag:'bindphone'},function(data){
	    alert(data.msg)
	    if(verify_from == 1) {
	    	if(data.id == 2) {
		    	window.location.href = '/account_security.php?action=bindsuccess';
		    }else{
		    	return false;
		    }
	    } else if(verify_from == 2) {
	    	if(data.id == 2) {
		    	window.location.href = '/account_security.php?action=updatesuccess';
		    }else{
		    	return false;
		    }
	    } else if(verify_from == 3) {
	    	if(data.id == 2) {
		    	window.location.href = '/account_security.php?action=unbindsuccess';
		    }else{
		    	return false;
		    }
	    } else {
	    	window.location.href = '/account_security.php';
	    }		
	},"json"); 
});
//------------------账号安全-未绑定手机页面end-----------------//
//------------------账户安全-绑定邮箱start-------------------//
$("#bindemailnext").on("click",function(){
	var phone = $.trim($("#phone").val());
	value = gt_captcha_obj.getValidate();
	if (!value){
        alert('滑块验证未通过');
        return false;
    }
	var user_code = $.trim($("#user_code").val());
	if(!user_code) {
		alert("请输入短信验证码");
		return false;
	}
	$.post("/ajax_userinfo.php",{phone:phone,user_code:user_code,flag:'bindemailone'},function(data){
	    if(data.id == 2) {
	    	$('#one_step').hide();
	    	$('#two_step').show();
	    	$('#three_step').hide();
	    }else{
	    	alert(data.msg)			
	    	return false;
	    }
	},"json"); 
});
$('#repeat_send_email').on("click",function(){
	var email = $.trim($('#repeat_email').val());
	$.post("/ajax_userinfo.php",{email:email,flag:'bindemailtwo'},function(data){
		if(data.id == 4) {
	    	alert('发送成功，请查收邮件');
	    	$('#repeat_email').val(email);
	    }else{
	    	alert(data.msg)			
	    	return false;
	    }
	},"json"); 
})
$("#bind_send_email").on("click",function(){
	var email = $.trim($('#email').val());
	if(!email){
		alert('请输入邮箱');
		return false;
	}
	var preg = /^([a-zA-Z0-9]+[\-|_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    if(!preg.test(email)){
    	alert('邮箱格式不正确，请重新输入');
    	return false;
    }
    $.post("/ajax_userinfo.php",{email:email,flag:'bindemailtwo'},function(data){
	    if(data.id == 4) {
	    	$('#one_step').hide();
	    	$('#two_step').hide();
	    	$('#three_step').show();
	    	$('#sfverify').html(data.msg);
	    	$('#repeat_email').val(email);
	    }else{
	    	alert(data.msg)			
	    	return false;
	    }
	},"json"); 
});
//------------------账户安全-绑定邮箱end---------------------//