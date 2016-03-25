// JavaScript Document

function selectall()//表单全选
{
	var obj = document.getElementsByName("ck[]"); 
	for(i=0;i<obj.length;i++)
	{
		if(obj[i].type=="checkbox")   //如果该集合元素是一个单选框，就选择它
		{
			if(obj[i].checked==false)
			{
			obj[i].checked=true;
			}
			else
			{
			obj[i].checked=false;
			}
		//obj[i].checked = true;
		}
	}
}

function ajaget(url,showdiv)
{
	var xmlHttp
	var method="get";
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==1)
		{
		document.getElementById(showdiv).innerHTML='<font color="red">加载..</font>';
		}
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
		document.getElementById(showdiv).innerHTML=xmlHttp.responseText;
		}
	}
	xmlHttp.open(method,url,true);
	xmlHttp.send(null);
}
function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
 	{
	 // Firefox, Opera 8.0+, Safari
	 xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		//Internet Explorer
		try
		{
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
 	}
return xmlHttp;
}