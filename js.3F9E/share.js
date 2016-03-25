
$(function(){
	var signPackage;
	
	$.ajaxSetup({
		async:false
	});
	
	var path = location.href.split('#')[0];
	
	$.post("/share/getShareSignPackage.php",{"url":path},function(data){
		signPackage = eval('(' + data + ')');
		
		wx.config({
			debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
			appId: signPackage.appId, // 必填，公众号的唯一标识
			timestamp: signPackage.timestamp, // 必填，生成签名的时间戳
			nonceStr: signPackage.nonceStr, // 必填，生成签名的随机串
			signature: signPackage.signature,// 必填，签名，见附录1
			jsApiList: [
				'onMenuShareTimeline',
				'onMenuShareAppMessage'
			] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		});
		
		wx.ready(function (){				
			wx.onMenuShareAppMessage({
				title: _title,
				desc: _desc,
				link: path,
				imgUrl: _imgUrl,
				trigger: function (res) {
					//alert('用户点击发送给朋友');
				},
				success: function (res) {
					//alert('已分享');
				},
				cancel: function (res) {
					//alert('已取消');
				},
				fail: function (res) {
					//alert(signPackage.timestamp);
					//alert(signPackage.nonceStr);
					//alert(signPackage.signature);
					//alert(JSON.stringify(res));
				}
			});

			wx.onMenuShareTimeline({
				title: _title,
				link: path,
				imgUrl: _imgUrl,
				trigger: function (res) {
					//alert('用户点击分享到朋友圈');
				},
				success: function (res) {
					//alert('已分享');
				},
				cancel: function (res) {
					//alert('已取消');
				},
				fail: function (res) {
					//alert(JSON.stringify(res));
				}
			});
		});
		
		wx.onMenuShareQQ({
			title: _title, // 分享标题
			desc: _desc, // 分享描述
			link: path, // 分享链接
			imgUrl: _imgUrl, // 分享图标
			success: function () { 
			   // 用户确认分享后执行的回调函数
			},
			cancel: function () { 
			   // 用户取消分享后执行的回调函数
			}
		});
		
		wx.onMenuShareWeibo({
			title: _title, // 分享标题
			desc: _desc, // 分享描述
			link: path, // 分享链接
			imgUrl: _imgUrl, // 分享图标
			success: function () { 
			   // 用户确认分享后执行的回调函数
			},
			cancel: function () { 
				// 用户取消分享后执行的回调函数
			}
		});
		
	});
});
