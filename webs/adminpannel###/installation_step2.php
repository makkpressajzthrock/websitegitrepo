<h2>Request WebsiteSpeedy team to Update code parameters for AI to work - 
<div class="form_h_submit">  
<button class="alert-pop btn btn-primary">Generate Request</button>
</div>
</h2>

<script>
	$('.alert-pop').click(function(){
var days = 7;
var date = new Date();
var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

var afterDate = new Date(res);
 var month = afterDate.getUTCMonth()+ 1; //months from 1-12
var day = afterDate.getUTCDate();
var year = afterDate.getUTCFullYear();

// Plus 3 month after adding 7
var dayss = 3;

var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
var afterDatess = new Date(ress);
 var months = afterDatess.getUTCMonth()+ 1; //months from 1-12
var days = afterDatess.getUTCDate();
var years = afterDatess.getUTCFullYear();
 

    Swal.fire({
      title: 'We need access on support@websitespeedy.com', 
    text:	'we will complete this task between '+day+'-'+month+'-'+year+' to '+days+'-'+months+'-'+years
}).then((value) => {
  var traffic=$("#traffic").val();
  var country_id=$("#country_id").val();
 var platform= $("#website-platform").val();
    var url_web = $("#website-url").val();
   var start_date=day+'-'+month+'-'+year;
    var end_date=days+'-'+months+'-'+years;

        
      $.ajax({
       url: " generate-script-save.php",
        type: "POST",
        dataType: "json",
      data: {
        start_date:start_date,
        end_date:end_date,
        platform:platform,
        traffic:traffic,
        country_id:country_id, 
        script:'<?=serialize($script_url)?>',   
        website_url:'<?=$website_data['website_url']?>',    
        id: '<?=$_GET["project"] ?>'

        },
      success: function (data) {

        
      }
    });

});

});

 
</script>