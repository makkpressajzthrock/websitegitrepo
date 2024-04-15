$(document).ready(function(){

    // get-timezone-country
    setTimeout(function(){
        $.ajax({
          type: "POST",
          url: "inc/get-timezone-country.php",
          data: {action:"get-timezone-country"},
          dataType: "json",
        }).done(function (data) {
            // console.log(data);

            var country_code_list = '<option value="">Select</option>' ;
            for ( var i in data.country_codes ) {
                var country = data.country_codes[i] ;
                country_code_list += '<option value="+'+country.phonecode+'" '+country.selected+'>'+country.name+'</option>' ;
            }
            $(".country-code-list").html(country_code_list);
            
            var timezone_list = '' ;
            for ( var i in data.timezones ) {
                var timezone = data.timezones[i] ;
                timezone_list += '<option value="+'+timezone.label+'">'+timezone.label+'</option>' ;
            }
            $(".timezone-list").html(timezone_list);

            $('.country-code-list , .timezone-list').niceSelect();

        }).fail(function(status){
            console.error("Unable to get country & timezones");
        }).always(function(){
        });
    },3000);


});