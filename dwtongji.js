var js_url="";
var kw_uId="";
var kw_ref="";
var kw_url="";
var kw_gurl="";
var is_get="";
var copy_content="";
var copy_type="";

try{
	js_url=document.getElementById("dwtongji").src;
	}catch(e){}

	try{
		kw_uId=getURLParam("kw_sign_id",js_url);
		}catch(e){}

	try{
		kw_url=window.parent.location;
		}catch(e){}

	try{
		kw_ref=window.parent.document.referrer;
		}catch(e){}

	try {
	    var js_erwei =document.getElementsByClassName('whimg');
	    var timeOutEvent=0;
	    console.log(js_erwei.length);
	    for(var i = 0; i<js_erwei.length; i++){
	        var img = js_erwei[i];
	        //移除之前的监听
	        img.removeEventListener('touchstart',touchstart);
	        img.removeEventListener('touchend',touchend);
	        //添加监听
	        img.addEventListener('touchstart',touchstart);
	        img.addEventListener('touchend',touchend);

	    }
	} catch(e) {}

	tj(1);


	setInterval(function(){
		var str=encodeURIComponent(window.getSelection(0).toString());
		if(str!=""&&copy_content!=str){
			copy_content=str;
			copy_type=1;
			setTimeout(tj(2),1)}

		},1000);


	


		function touchstart(e){
		      timeOutEvent = setTimeout("longPress()",800);
		      console.log('触摸开始');
		     
		}

		function touchend(e){
		    clearTimeout(timeOutEvent);
		    console.log('触摸结束');
		    return false;
		}


		function longPress(){
		    timeOutEvent = 0;

		    if (stxlwx != "") {
			    copy_content = stxlwx;
			    copy_type = 3;
		   		 setTimeout(tj(2), 1)
		    }
			return false; 
		}


			function longPress(){
			    timeOutEvent = 0;
			    if (stxlwx != "") {

			    copy_content = stxlwx;
			    copy_type = 3;

			    setTimeout(tj(2), 1);
			    
			    }
			 
				return false; 
			}



		function session() {
		    var data = sessionStorage.getItem('whwl');
		    if(data==null||data==undefined||data==""){
		        sessionStorage.setItem('whwl', 'whwlvalue');
		        return 1;
		     }
		     else{
		        return 0;
		    }
		      
		}

		function showsession() {
		    var data = sessionStorage.getItem('whwls');
		    if(data==null||data==undefined||data==""){
		        sessionStorage.setItem('whwls', 'whwlvalues');
		        return 1;
		     }
		     else{
		        return 0;  
		    }
		      
		}

	document.addEventListener('copy',function(event){
		try{var str=encodeURIComponent(window.getSelection(0).toString());
				if(str!=""&&copy_content!=str){
					copy_content=str;
					copy_type=2;
					setTimeout(tj(2),1)
					}
			}
			catch(e){}}
        );

    function tj(type){
		if(type==1){
			if(is_get!="ok"){
				  dw_gurl="http://alile.yxykedu.com/grab/index/addurl?kw_sign_id="+kw_sign_id+"&kw_url="+escape(kw_url)+"&kw_ref="+escape(kw_ref)+"&v="+unique();
				  setTimeout('is_get="ok";dw_img = new Image;dw_img.src=dw_gurl;',1);
				}
		    }else{

			   if(type==2){
				   dw_gurl="http://alile.yxykedu.com/grab/index/addcopy?kw_sign_id="+kw_uId+"&type="+copy_type+"&kw_url="+escape(kw_url)+"&kw_ref="+escape(kw_ref)+"&c="+copy_content+"&v="+unique();
				   setTimeout("dw_img = new Image;dw_img.src=dw_gurl;",1);
			   }

		}
	}






    function dwtj(auname){
		dw_gurl="http://alile.yxykedu.com/grab/index/addcopy?kw_sign_id="+kw_uId+"&type="+3+"&kw_url="+escape(kw_url)+"&kw_ref="+escape(kw_ref)+"&c="+auname+"&v="+unique();
		setTimeout("dw_img = new Image;dw_img.src=dw_gurl;",1)

	}

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

    function unique(){
		var time=(new Date()).getTime()+"-",i=0;return time+(i++)
	};
