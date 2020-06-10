var js_url="";
var uid = "";
var gid = "";

try{
	js_url=document.getElementById("a2bc").src;
}catch(e){
	//TODO handle the exception
}

try{
	uid=getURLParam("uid",js_url);
}catch(e){
	//TODO handle the exception
}
try{
	gid=getURLParam("gid",js_url);
}catch(e){
	//TODO handle the exception
}

//展示微信号
function showWx(wxData){
	var wx_index = Math.floor(Math.random()*wxData.length);
	var stxlwx = wxData[wx_index]['wx'];
	var wxname = wxData[wx_index]['name'];
	$(".wuk_weixin").text(stxlwx);
	$(".wuk_name").text(wxname);
}

function myajax(uid,gid){
	$.ajax({
		url:"http://127.0.1.0/weixin/cdnwx/rediswx?uid="+uid+"&gid="+gid,
		type:"get",
		dataType:"json",
		success:function(msg){			
			if(msg.code == 200){
				console.log(msg.data);
				showWx(msg.data);
			}
		}
	});
	
	
}
 myajax(uid,gid);


// console.log(uid);
// console.log(gid);
// console.log(js_url);
function getURLParam(strParamName,url){
		var strReturn="";
		var strHref=url.toLowerCase();
		if(strHref.indexOf("?")>-1){

			var strQueryString=strHref.substr(strHref.indexOf("?")+1).toLowerCase();
			var aQueryString=strQueryString.split("&");
			for(var iParam=0;iParam<aQueryString.length;iParam++){

				if(aQueryString[iParam].indexOf(strParamName.toLowerCase()+"=")>-1){
					var aParam=aQueryString[iParam].split("=");
					strReturn=aParam[1];
					break
				}

			}

		}
		return strReturn
	}