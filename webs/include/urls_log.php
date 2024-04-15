<?php require_once("/var/www/html/adminpannel/env.php") ; ?>
<script>
   
 function isUrlValid(userInput) {

console.log(userInput);
    var res = userInput.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
    if(res == null)
        return false;
    else
        return true;
}

    function urlbtn(ele) {

console.log(ele);
        var user_url = document.getElementById(ele).value;

        if(isUrlValid(user_url)){

            user_data['user_url'] = user_url; 
            user_data['source'] = window.location.href;
 
            $.ajax({

                type:'post',

                url: '<?=HOST_URL?>inc/ajax_log_url_request.php',

                data: {

                    details: user_data

                },
 

            });

        localStorage.setItem("website_urls", user_url);
        window.location.href = "<?=HOST_URL?>signup.php?ref=analyze";
    }
    else{

            $('#url_validate').html('Invalid url!');
            $('.url_validate').html('Invalid url!');
            setTimeout(function(){

                 $('#url_validate').html('');
                 $('.url_validate').html('');


            }, 3000);

    }

    }

</script>