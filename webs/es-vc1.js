
$.ajaxSetup({ async: false }); // async ajax request

// get browser details
var appbrowser = '' ;
$.getJSON("https://api.apicagent.com/?ua="+navigator.userAgent, function(data) { appbrowser = data; }) ;
console.log(appbrowser);

var user_ip = user_city = user_country = user_countryIso = user_latitude = user_longitude = user_timeZone = user_fingerprint = '' ;
var user_flag = 0 ;


$.getJSON('https://jsonip.com/', function(data) {
	user_ip = data.ip ;
});
// get details from user ip and other
$.getJSON('https://ipapi.co/json/', function(data) {
	user_city = data.city ;
	user_country = data.country_name ;
	user_countryIso = data.country_code ;
	user_latitude = data.latitude ;
	user_longitude = data.longitude ;
	user_timeZone = data.timezone ;
	user_flag = 1 ;
});

if ( user_flag == 0 ) {
	$.getJSON('https://ipinfo.io/json', function(data) {
		user_city = data.city ;
		user_country = data.country ;
		user_countryIso = data.country ;
		var loc = data.loc.trim().split(',') ;
		user_latitude = loc[0] ;
		user_longitude = loc[1] ;
		user_timeZone = data.timezone ;
		user_flag = 1 ;
	});
}

if ( (user_flag == 0) && (user_ip == '' || user_city == '' || user_country == '' || user_countryIso == '') ) { 
	$.get('https://www.cloudflare.com/cdn-cgi/trace', function(data) {
		data = data.trim().split('\n') ;
		if (data.length > 0 ) {

			data = data.reduce(function(obj, pair) {
				pair = pair.split('=');

				if ( pair[0] == 'ip' && user_ip != '' ) { user_ip = pair[1] }
				if ( pair[0] == 'colo' ) { user_city = pair[1] }
				if ( pair[0] == 'loc' ) { user_country = pair[1] }
				if ( pair[0] == 'loc' ) { user_countryIso = pair[1] }

				return obj[pair[0]] = pair[1], obj;
			}, {});

			user_flag = 1 ;
		}
	});

}

appUser = [] ;
appUser["ip"] = user_ip ;
appUser["city"] = user_city ;
appUser["country"] = user_country ;
appUser["countryIso"] = user_countryIso ;
appUser["latitude"] = user_latitude ;
appUser["longitude"] = user_longitude ;
appUser["timeZone"] = user_timeZone ;


if ( Object.keys(appUser).length > 0 ) {
	$.ajax({
		url:"https://websitespeedy.com/validate.php",
		method:"POST",
		data:{ store:domain ,
				ip:appUser.ip ,
				city : appUser.city ,
				country : appUser.country ,
				countryIso : appUser.countryIso ,
				latitude : appUser.latitude ,
				longitude : appUser.longitude, 
				timezone : appUser.timeZone ,
				browser_family : appbrowser.browser_family,
				client_name : appbrowser.client.name,
				client_type : appbrowser.client.type,
				device_type : appbrowser.device.type,
				os_family : appbrowser.os_family,
			} ,
		dataType:"json",
		async : false
	});
}


 