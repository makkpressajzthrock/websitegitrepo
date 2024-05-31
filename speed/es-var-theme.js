function preloadFunc() { 
	alert("ll") ;

	var content = '<img width = "99999" height = "99999" style = "pointer-events: none; position: absolute; top: 0; left: 0; width: 96vw; height: 96vh; max-width: 99vw; max-height: 99vh;" src = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIHdpZHRoPSI5OTk5OXB4IiBoZWlnaHQ9Ijk5OTk5cHgiIHZpZXdCb3g9IjAgMCA5OTk5OSA5OTk5OSIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48ZyBzdHJva2U9Im5vbmUiIGZpbGw9Im5vbmUiIGZpbGwtb3BhY2l0eT0iMCI+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9Ijk5OTk5IiBoZWlnaHQ9Ijk5OTk5Ij48L3JlY3Q+IDwvZz4gPC9zdmc+">' ;
	var content = '{% assign kGxh = "if(window.attachEvent)" %} {% assign snOi = "document.addEventListener(\'asyncLazyLoad\',function(event){asyncLoad();});if(window.attachEvent)" %} {% assign rapp = ", asyncLoad" %} {% assign napp = ", function(){}" %} {% assign wjhx = "document.addEventListener(\'DOMContentLoaded\'" %} {% assign sSOd = "document.addEventListener(\'asyncLazyLoad\'" %} {%if template == \'cart\' %}{{content_for_header}} {% elsif content_for_header contains "Shopify.designMode" %} {{content_for_header}} {% else %} {{content_for_header | replace: wjhx, sSOd | replace: gedH, vNla | replace: rapp, napp | replace: kGxh, snOi}}{% endif %}' ;
	// content = document.createTextNode(content);
	var div = document.createElement("div") ; 
	// div.appendChild(content);
	document.head.appendChild(div);

	div.innerHTML = content ;
}


window.onpaint = preloadFunc();
