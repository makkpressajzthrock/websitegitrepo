<?php 
$uname = $_GET['username'];
$pass = $_GET['password'];
// $uname = "ssss";
?>
<?php 

// echo $uname; 

?>


<form id="helpForm" class="z-form" action="https://help.websitespeedy.com/actions/account/login" method="post" data-csrf="manual">
          <div class="response-message"></div>
          <input type="hidden" name="ci_csrf_token" value="">
          <div class="mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username or Email Address" required="" value="<?php echo $uname; ?>">
          </div>
          <!-- /.mb-3 -->
          <div class="mb-2">
            <input type="password" class="form-control" name="password" placeholder="Password" required="" value="<?=$pass?>">
          </div>
          <!-- /.mb-2 -->
          <div class="mb-3 form-check">
            <div class="float-sm-start">
              <input type="checkbox" class="form-check-input" id="remember-me" name="remember_me" checked="">
              <label class="form-check-label small" for="remember-me">Remember Me</label>
            </div>
            
          </div>
          <!-- /.mb-3 -->
                    <div class="d-grid">
            <button id = 'helpFormbtn' class="btn btn-sub" type="submit">Login</button>
          </div>
          <!-- /.d-grid -->
          
                    
</form>
<script>
 setTimeout(function(){
  document.getElementById('helpForm').submit();
  // document.getElementById("helpFormbtn").click();
 },1000); 

</script>
