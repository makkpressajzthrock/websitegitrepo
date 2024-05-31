<?php

require_once("/var/www/html/adminpannel/env.php") ;

require_once("adminpannel/config.php");
require_once("adminpannel/inc/functions.php");
 
$analyze = $_GET['ref'];

 $user_id = $_SESSION['user_id'];
    

if (isset($_POST['Continue'])) {

    // echo "<pre>"; print_r($_POST); die;
         
        extract($_POST);

             $sql = " UPDATE `admin_users` SET self_install = '$self_install', country = '$country', country_code = '$country_code', phone = '$phone'  WHERE `id` = '" . $user_id . "'; "; 

                if ($conn->query($sql) === TRUE) {
                        // echo 1; die;
                     header("location: ".HOST_URL."customize-flow.php") ;
                }

}
    
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <?php require_once('inc/style-script.php'); ?>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script> 

    
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->





</head>
<style type="text/css">
    /* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#message p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>
<body>
  <script>
  fbq('track', 'StartTrial', {
    value: 1,
    currency: 'INR',
  });
</script>  

    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



    <div class="singup_wrapper customize_wrapper">
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="container singup_container basic__details">
 

            <div class="signup">
                    <div class="signup_logo">
                            <a href="<?=HOST_URL?>signup.php" ><img src="./img/signup_logo.png"></a>
                    </div>
                

                <h2>Select Installation Mode</h2>

                <?php

                require_once('inc/alert-message.php');

                ?>
                    <form method="post" class="basic__details__form" autocomplete="off">

                        <div class="form-group">
                            <div class="row flex-col">

                                <div class="col-12">
                                    <label>Would you like Website Speedy team to do the installation on your website ?</label>
                                    
                                    <div class="form-check">
                                      <input type="radio" class="form-check-input" id="install1" name="self_install" value="yes" >
                                      <div class="check" ></div>
                                      <label class="form-check-label" for="install1">Yes, I want website Speedy team to do the installation</label>
                                    </div>
                                    <div class="form-check">
                                      <input type="radio" class="form-check-input" id="install2" name="self_install" value="no" checked>
                                      <div class="check" ></div>
                                      <label class="form-check-label" for="install2">No, I want to install myself</label>
                                    </div>
                                                                   
                                </div>

                            <div class="col-12">
                                    
                                        <div class="form-group">
                            <label for="Country">Country</label>
                            <div class="for__arrow country">
                                
                                
                                    
                                    <select id="Country" class="form-control" name="country" required>

                                        <?php
                                            $selected = "231";
                                                $list_countries = getTableData( $conn , " list_countries " , " 1 " , "" , 1 ) ;
                                                foreach ($list_countries as $key => $country_data) { 
                                        ?>          
                                                <option value='<?=$country_data["id"]?>' <?php if($country_data["id"]==$selected){echo "selected";} ?> ><?=$country_data["name"]?></option>
                                        <?php
                                                }
                                        ?>
                                    </select>
                                
                            </div>

                        </?div>
                                </div>
                            </div>
                        </div>

                    
                         <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="country_code">Contact Number</label>
                                    <div class="for__arrow county__code">

                                        <select id="country_code" class="form-control" name="country_code" required>
                                            <?php
                                                    $list_countries1 = getTableData( $conn , " list_countries " , "" , "" , 1 ) ;
                                                    foreach ($list_countries1 as $key => $country_data) { 
                                            ?>          
                                                    <option value='+<?=$country_data["phonecode"]?>'  <?php if($country_data["id"]==$selected){echo "selected";} ?>  ><?=$country_data["name"]?> +<?=$country_data["phonecode"]?></option>
                                            <?php
                                                    }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-8">
                                    <label for="phone"></label>
                                    <input type="number"  class="form-control" id="phone" placeholder="Enter your phone number" name="phone" required  autocomplete="off" />
                                </div>
                            </div>
                        </div>  
 
            
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" value="register" name="Continue">Continue</button>

                        </div>
                    </form>
                 


            </div>

        </div>
    </div>


</body>

</html>


 
 
<script>
    


// When the user clicks on the password field, show the message box

  document.getElementById("message").style.display = "block";


// When the user clicks outside of the password field, hide the message box
setInterval(abc, 1000);

// When the user starts to type something inside the password field
function abc() {
var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>

<script>
    (function($) {

$.fn.niceSelect = function(method) {

    // Methods
    if (typeof method == 'string') {
        if (method == 'update') {
            this.each(function() {
                var $select = $(this);
                var $dropdown = $(this).next('.nice-select');
                var open = $dropdown.hasClass('open');

                if ($dropdown.length) {
                    $dropdown.remove();
                    create_nice_select($select);

                    if (open) {
                        $select.next().trigger('click');
                    }
                }
            });
        } else if (method == 'destroy') {
            this.each(function() {
                var $select = $(this);
                var $dropdown = $(this).next('.nice-select');

                if ($dropdown.length) {
                    $dropdown.remove();
                    $select.css('display', '');
                }
            });
            if ($('.nice-select').length == 0) {
                $(document).off('.nice_select');
            }
        } else {
            console.log('Method "' + method + '" does not exist.')
        }
        return this;
    }

    // Hide native select
    this.hide();

    // Create custom markup
    this.each(function() {
        var $select = $(this);

        if (!$select.next().hasClass('nice-select')) {
            create_nice_select($select);
        }
    });

    function create_nice_select($select) {
        $select.after($('<div></div>')
            .addClass('nice-select')
            .addClass($select.attr('class') || '')
            .addClass($select.attr('disabled') ? 'disabled' : '')
            .addClass($select.attr('multiple') ? 'has-multiple' : '')
            .attr('tabindex', $select.attr('disabled') ? null : '0')
            .html($select.attr('multiple') ? '<span class="multiple-options"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search"/></div><ul class="list"></ul>' : '<span class="current"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search"/></div><ul class="list"></ul>')
        );

        var $dropdown = $select.next();
        var $options = $select.find('option');
        if ($select.attr('multiple')) {
            var $selected = $select.find('option:selected');
            var $selected_html = '';
            $selected.each(function() {
                $selected_option = $(this);
                $selected_text = $selected_option.data('display') ||  $selected_option.text();

                if (!$selected_option.val()) {
                    return;
                }

                $selected_html += '<span class="current">' + $selected_text + '</span>';
            });
            $select_placeholder = $select.data('js-placeholder') || $select.attr('js-placeholder');
            $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
            $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
            $dropdown.find('.multiple-options').html($selected_html);
        } else {
            var $selected = $select.find('option:selected');
            $dropdown.find('.current').html($selected.data('display') ||  $selected.text());
        }


        $options.each(function(i) {
            var $option = $(this);
            var display = $option.data('display');

            $dropdown.find('ul').append($('<li></li>')
                .attr('data-value', $option.val())
                .attr('data-display', (display || null))
                .addClass('option' +
                    ($option.is(':selected') ? ' selected' : '') +
                    ($option.is(':disabled') ? ' disabled' : ''))
                .html($option.text())
            );
        });
    }

    /* Event listeners */

    // Unbind existing events in case that the plugin has been initialized before
    $(document).off('.nice_select');

    // Open/close
    $(document).on('click.nice_select', '.nice-select', function(event) {
        var $dropdown = $(this);

        $('.nice-select').not($dropdown).removeClass('open');
        $dropdown.toggleClass('open');

        if ($dropdown.hasClass('open')) {
            $dropdown.find('.option');
            $dropdown.find('.nice-select-search').val('');
            $dropdown.find('.nice-select-search').focus();
            $dropdown.find('.focus').removeClass('focus');
            $dropdown.find('.selected').addClass('focus');
            $dropdown.find('ul li').show();
        } else {
            $dropdown.focus();
        }
    });

    $(document).on('click', '.nice-select-search-box', function(event) {
        event.stopPropagation();
        return false;
    });
    $(document).on('keyup.nice-select-search', '.nice-select', function() {
        var $self = $(this);
        var $text = $self.find('.nice-select-search').val();
        var $options = $self.find('ul li');
        if ($text == '')
            $options.show();
        else if ($self.hasClass('open')) {
            $text = $text.toLowerCase();
            var $matchReg = new RegExp($text);
            if (0 < $options.length) {
                $options.each(function() {
                    var $this = $(this);
                    var $optionText = $this.text().toLowerCase();
                    var $matchCheck = $matchReg.test($optionText);
                    $matchCheck ? $this.show() : $this.hide();
                })
            } else {
                $options.show();
            }
        }
        $self.find('.option'),
            $self.find('.focus').removeClass('focus'),
            $self.find('.selected').addClass('focus');
    });

    // Close when clicking outside
    $(document).on('click.nice_select', function(event) {
        if ($(event.target).closest('.nice-select').length === 0) {
            $('.nice-select').removeClass('open').find('.option');
        }
    });

    // Option click
    $(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {
        
        var $option = $(this);
        var $dropdown = $option.closest('.nice-select');
        if ($dropdown.hasClass('has-multiple')) {
            if ($option.hasClass('selected')) {
                $option.removeClass('selected');
            } else {
                $option.addClass('selected');
            }
            $selected_html = '';
            $selected_values = [];
            $dropdown.find('.selected').each(function() {
                $selected_option = $(this);
                var attrValue = $selected_option.data('value');
                var text = $selected_option.data('display') ||  $selected_option.text();
                $selected_html += (`<span class="current" data-id=${attrValue}> ${text} <span class="remove">X</span></span>`);
                $selected_values.push($selected_option.data('value'));
            });
            $select_placeholder = $dropdown.prev('select').data('js-placeholder') ||                                   $dropdown.prev('select').attr('js-placeholder');
            $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
            $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
            $dropdown.find('.multiple-options').html($selected_html);
            $dropdown.prev('select').val($selected_values).trigger('change');
        } else {
            $dropdown.find('.selected').removeClass('selected');
            $option.addClass('selected');
            var text = $option.data('display') || $option.text();
            $dropdown.find('.current').text(text);
            $dropdown.prev('select').val($option.data('value')).trigger('change');
        }
      console.log($('.mySelect').val())
    });
  //---------remove item
  $(document).on('click','.remove', function(){
    var $dropdown = $(this).parents('.nice-select');
    var clickedId = $(this).parent().data('id')
    $dropdown.find('.list li').each(function(index,item){
      if(clickedId == $(item).attr('data-value')) {
        $(item).removeClass('selected')
      }
    })
    $selected_values.forEach(function(item, index, object) {
      if (item === clickedId) {
        object.splice(index, 1);
      }
    });
    $(this).parent().remove();
    console.log($('.mySelect').val())
   })
  
    // Keyboard events
    $(document).on('keydown.nice_select', '.nice-select', function(event) {
        var $dropdown = $(this);
        var $focused_option = $($dropdown.find('.focus') || $dropdown.find('.list .option.selected'));

        // Space or Enter
        if (event.keyCode == 32 || event.keyCode == 13) {
            if ($dropdown.hasClass('open')) {
                $focused_option.trigger('click');
            } else {
                $dropdown.trigger('click');
            }
            return false;
            // Down
        } else if (event.keyCode == 40) {
            if (!$dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            } else {
                var $next = $focused_option.nextAll('.option:not(.disabled)').first();
                if ($next.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $next.addClass('focus');
                }
            }
            return false;
            // Up
        } else if (event.keyCode == 38) {
            if (!$dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            } else {
                var $prev = $focused_option.prevAll('.option:not(.disabled)').first();
                if ($prev.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $prev.addClass('focus');
                }
            }
            return false;
            // Esc
        } else if (event.keyCode == 27) {
            if ($dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            }
            // Tab
        } else if (event.keyCode == 9) {
            if ($dropdown.hasClass('open')) {
                return false;
            }
        }
    });

    // Detect CSS pointer-events support, for IE <= 10. From Modernizr.
    var style = document.createElement('a').style;
    style.cssText = 'pointer-events:auto';
    if (style.pointerEvents !== 'auto') {
        $('html').addClass('no-csspointerevents');
    }

    return this;

};

}(jQuery));

$(document).ready(function() {
    // $('#Country').niceSelect();
    // $('#country_code').niceSelect();
});


//Move with arrows
var ul = document.querySelectorAll('ul.list');
var liSelected;
var index = -1;

document.addEventListener('keydown', function(event) {
    for (let i = 0; i < ul.length; i++) {
    var len = ul[i].getElementsByTagName('li').length - 1;
  if (event.which === 40) {
    index++;
    //down 
    if (liSelected) {
      removeClass(liSelected, 'selected'); 
      removeClass(liSelected, 'focus');
      next = ul[i].getElementsByTagName('li')[index];
      if (typeof next !== undefined && index <= len) {

        liSelected = next;
      } else {
        index = 0;
        liSelected = ul[i].getElementsByTagName('li')[0];
      }
      addClass(liSelected, 'selected');
      addClass(liSelected, 'focus');
      console.log(index);
    } else {
      index = 0;

      liSelected = ul[i].getElementsByTagName('li')[0];
      addClass(liSelected, 'selected');
      addClass(liSelected, 'focus');
    }
  } else if (event.which === 38) {

    //up
    if (liSelected) {
      removeClass(liSelected, 'selected');
      removeClass(liSelected, 'focus');
      index--;
      console.log(index);
      next = ul[i].getElementsByTagName('li')[index];
      if (typeof next !== undefined && index >= 0) {
        liSelected = next;
      } else {
        index = len;
        liSelected = ul[i].getElementsByTagName('li')[len];
      }
      addClass(liSelected, 'selected');
      addClass(liSelected, 'focus');
    } else {
      index = 0;
      liSelected = ul[i].getElementsByTagName('li')[len];
      addClass(liSelected, 'selected');
      addClass(liSelected, 'focus');
    }
  }
}
        
    }
  , false);

function removeClass(el, className) {
  if (el.classList) {
    el.classList.remove(className);
  } else {
    el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
  }
};

function addClass(el, className) {
  if (el.classList) {
    el.classList.add(className);
  } else {
    el.className += ' ' + className;
  }
};

// $(".nice-select").click(function() {
//     setTimeout( function(){
//         $('.list').animate({
//             scrollTop: $(this).find('.selected.focus').offset().top
//         }, 200);
//     }, 200 )
// });


</script>

<script>
    let form = document.getElementsByTagName('form');

    for (let i = 0; i < form.length; i++) {
        
        let btn = form[i].getElementsByTagName('button');

        form[i].addEventListener('submit', ()=> {
            setTimeout(() => {
                for (let j = 0; j < btn.length; j++) {
                    btn[j].setAttribute('disabled', 'true');
                    btn[j].classList.add('no-click');
                    btn[j].innerHTML = '<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>'
                    
                }
            }, 200);
        })
        
    }

    

</script>