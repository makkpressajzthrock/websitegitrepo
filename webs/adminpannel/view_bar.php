
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style type="text/css">
.progresslabel {
    font-family: "Arial";
    position: relative;
    left: -100px;
    font-size: small;
}

  </style>
</head>
<body>
<progress value="200" max="4000"></progress>
<span class="progresslabel"></span>

<script type="text/javascript">
var pPos = 4000;
    var pEarned = 700;
    
    var perc = ((pEarned/pPos) * 100).toFixed(2);
    alert(perc+"%");
</script>
</body>
</html>


