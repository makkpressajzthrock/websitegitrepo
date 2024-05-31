<?php
// session_start();
include('adminpannel/config.php');
require_once("adminpannel/inc/functions.php");
require 'adminpannel/smtp-send-grid/vendor/autoload.php';

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

if(isset($_POST['contact_submit2'])){

foreach ($_POST as $post_key => $post_value) {

$_POST[$post_key] = $conn->real_escape_string($post_value);


}

extract($_POST);

if(trim($website_url)==""){
    $_SESSION['error'] = "Website URL Is required.";
    header("location: ".HOST_URL."contact-us.php") ;
    die;
}

if($customer_status == 'yes'){

$customer_status = 1;

}else{

$customer_status = 0;

}
 $insert =  "INSERT INTO `contact_info`(`contact_person`, `name`, `email`, `phone`, `country`, `customer_status`, `website_url`, `category`, `message`) VALUES ('$contact_person', '$name', '$email', '$phone', '$country', '$customer_status', '$website_url','Contact Us', '$message')";

$insert = $conn->query($insert);

if($insert === TRUE){

// echo 'Done!';

    $email_sender = "support@websitespeedy.com";
        // $email = "ajay.makkpress@gmail.com";


    $emailContent = "
        <div>
            <label><b>Who do you want to talk to? :</b> $contact_person</label><br>
            <label><b>Name :</b> $name</label><br>
            <label><b>Email :</b> $email</label><br>
            <label><b>Phone Number :</b> $phone</label><br>
            <label><b>Country/Region :</b> $country</label><br>
            <label><b>Are u already a customer :</b> $customer_status</label><br>
            <label><b>Website URL :</b> $website_url</label><br>
            <label><b>Message :</b> $message</label><br>
        </div>
    ";

$smtpDetail = getSMTPDetail($conn);


$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject("Website Speddy New Contact");
$emailss->addTo($email_sender,"Website Speddy");
$emailss->addContent("text/html",$emailContent);
$sendgrid = new \SendGrid($smtpDetail["password"]);

                    if (!$sendgrid->Send($emailss)) {
                     $_SESSION['error'] = "Something went wrong please try again.";
                       
                    }else{
                      $_SESSION['success'] = "<span>Thank You</span> for submitting your request, Our team will contact you soon.";
                                            
                    }


}
}
 // $_SESSION['success'] = "Request Sent our team will contact you soon.";
?>


<?php require_once 'include/header.php'; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>


<div class="contact-new">
    <div class="contact-header">
        <div class="section__wrapper">
            <h1> Contact Us, ask us anything</h1>
            <p>If you require any assistance or encounter any issues, have suggestions for improving our tool, or would like to request new features, we are here to help and welcome your feedback.</p>
        </div>
    </div>
    <div class="address-info-wrap">
        <div class="section__wrapper">
            <div class="details address-list">
                <div class="address">
                    <div class="title">Reach US</div>
                    <p>MAKKPRESS TECHNOLOGIES PRIVATE LIMITED 
                    <br>
                    28/6-A, DOUBLE STOREY, ASHOK NAGAR, North West Delhi, Delhi-110018</p>
                </div>

                <div class="address">
                    <div class="title">USA</div>
                    <p>30 N Gould St Ste R, Sheridan, WY 82801</p> 
                    <div class="contact__links">
                        <a href="mailto:sales@makkpress.com"><span>sales@makkpress.com</span></a>
                        <a href="tel:307-212-6877"><span>307-212-6877</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-form">
        <div class="section__wrapper">
            
            
            <div class="contact__inner__wrapper">
               
    
                <div class="main__content">
    
                    <div class="alert-status">
                        <?php
                if ( !empty($_SESSION['error']) ) {
                    echo '<div class="alert alert-danger alert-dismissible fade show thank__you__message" role="alert"><div>'.$_SESSION['error'].'</div></div>' ;
                    unset($_SESSION['error']) ;
                    echo '<script type="text/JavaScript"> 
                            $("body").addClass("blur");
                        </script>';
                }
    
                if ( !empty($_SESSION['success']) ) {
                    echo '<div class="alert alert-success alert-dismissible fade show thank__you__message" role="alert"><div>'.$_SESSION['success'].'</div></div>' ;
                    unset($_SESSION['success']) ;
                    echo '<script type="text/JavaScript"> 
                            $("body").addClass("blur");
                        </script>';
                }
                        ?>
                    </div>
    
                    <form method="POST" id="contactForms" class="contactpage_form"
                        onsubmit="return validations();">
    
                        <div class="field__wrapper full__width ">
                            <label for="selectR">What do you want to talk about?</label>
                            <select name="contact_person" id="selectR" required>
                                <option value="">Please Select</option>
                                <option value="Website Speedy Installation">Website Speedy Installation</option>
                                <option value="Feature Request">Feature Request</option>
                                <option value="Complaint">Complaint</option>
                                <option value="Support">Support</option>
                                <option value="Report a Bug">Report a Bug</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="field__wrapper">
                            <label for="name1">Name</label>
                            <input type="text" id="name1" name="name" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="email1">Email</label>
                            <input type="email" id="email1" name="email" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="phoneno1">Phone Number</label>
                            <input type="text" id="phoneno1" name="phone" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="country1">Country/Region</label>
                            <select name="country" id="country1" required="">
                                <option value="">Please Select</option>
                                <?php
    
                                $sql =  "SELECT * FROM list_countries";
    
                                $query = $conn->query($sql);
    
                                while($row = $query->fetch_assoc()){
    
                                ?>
    
                                <option value="<?=$row['sortname']?>">
                                    <?=$row['name']?>
                                </option>
    
                                <?php
    
                                }
    
                                // print_r($query_data);
    
                                ?>
                            </select>
                        </div>
                        <div class="field__wrapper">
                            <label for="selectC">Are u already a customer</label>
                            <select name="customer_status" id="selectC" required="">
                                <option value="">Please Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <!--      <div class="field__wrapper">
                                <label for="phoneno">Phone Number</label>
                                <input type="number" id="phoneno" name="phoneno">
                            </div> -->
                        <div class="field__wrapper">
                            <label for="url">Website URL*</label>
                            <input type="url" id="url1" name="website_url" required="">
                        </div>
                        <div class="field__wrapper full__width">
                            <label for="que">Message</label>
                            <textarea name="message" id="que" required=""></textarea>
                        </div>
    
                        <!-- <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6Leoz1gkAAAAAH_zR0uTCDhMlnWFnzGXFPWqvRXR"></div>  -->
    
                        <div class="field__wrapper full__width">
                            <label for="que">Captcha: <span class="captchaErr" style="display: none; color: red;">Incorrect
                                    Captcha Code</span></label>
                            <div class="group">
                                <p id="question"></p>
                                <input id="ans" type="text">
                                <label id="reset" class="btn">Reset</label>
                            </div>
                            <input type="hidden" id="checkFill">
    
                        </div>
    
                        <div class="field__wrapper full__width">
                            <button type="submit" class="btn" name="contact_submit2">SUBMIT</button>
                        </div>
    
                    </form>
    
    
    
                </div>
    
            </div>
    
            
        </div>
    </div>

    <div class="maps-view-wrap">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3501.793646726057!2d77.09475587508351!3d28.635946083926342!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d04a6d7fa206b%3A0x8e3077c40ebde2a9!2sMakkPress%20Technologies!5e0!3m2!1sen!2sin!4v1698921134590!5m2!1sen!2sin"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

</div>



<script>




    function validations() {

        var contact_person = $('#selectR').val();

        var name = $('#name').val();

        name.trim();

        var email = $('#email').val();

        email.trim();

        var phone = $('#phoneno').val();


        phone.trim();

        var country = $('#country').val();

        var customer_status = $('#selectC').val();

        var website_url = $('#url').val();

        website_url.trim();

        var message = $('#que').val();

        if (contact_person == null || contact_person == "Please Select") {

            alert('Select a contact person!');
            return false;


        }

        if (name == null || name == '') {

            alert('Enter a name!');
            return false;

        }

        if (email == null || email == '') {

            alert('Enter an email!');
            return false;
        }

        if (phone == null || phone == '') {

            alert('Enter a phone number');
            return false;

        }

        if (country == 'Please Select' || country == null) {

            alert("Select a country");
            return false;

        }

        if (customer_status == "Please Select" || customer_status == null || customer_status == '') {

            alert('Select whether you are a customer or not');
            return false;

        }

        if (website_url == null || website_url == '') {

            alert('Enter a website url');

            return false;

        }

        if (message == null || message == '') {

            alert('Enter a message');

            return false;

        }


    }


    setTimeout(() => {
        var msg = document.getElementsByClassName('thank__you__message');
        var body = document.getElementsByTagName('body');
        if (typeof msg[0] != 'undefined') {
            msg[0].style.display = "none";
            body[0].classList.remove('blur');
        }

    }, 5000);




</script>


<script>
    (function ($) {

        $.fn.niceSelect = function (method) {

            // Methods
            if (typeof method == 'string') {
                if (method == 'update') {
                    this.each(function () {
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
                    this.each(function () {
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
            this.each(function () {
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
                    $selected.each(function () {
                        $selected_option = $(this);
                        $selected_text = $selected_option.data('display') || $selected_option.text();

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
                    $dropdown.find('.current').html($selected.data('display') || $selected.text());
                }


                $options.each(function (i) {
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
            $(document).on('click.nice_select', '.nice-select', function (event) {
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

            $(document).on('click', '.nice-select-search-box', function (event) {
                event.stopPropagation();
                return false;
            });
            $(document).on('keyup.nice-select-search', '.nice-select', function () {
                var $self = $(this);
                var $text = $self.find('.nice-select-search').val();
                var $options = $self.find('ul li');
                if ($text == '')
                    $options.show();
                else if ($self.hasClass('open')) {
                    $text = $text.toLowerCase();
                    var $matchReg = new RegExp($text);
                    if (0 < $options.length) {
                        $options.each(function () {
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
            $(document).on('click.nice_select', function (event) {
                if ($(event.target).closest('.nice-select').length === 0) {
                    $('.nice-select').removeClass('open').find('.option');
                }
            });

            // Option click
            $(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function (event) {

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
                    $dropdown.find('.selected').each(function () {
                        $selected_option = $(this);
                        var attrValue = $selected_option.data('value');
                        var text = $selected_option.data('display') || $selected_option.text();
                        $selected_html += (`<span class="current" data-id=${attrValue}> ${text} <span class="remove">X</span></span>`);
                        $selected_values.push($selected_option.data('value'));
                    });
                    $select_placeholder = $dropdown.prev('select').data('js-placeholder') || $dropdown.prev('select').attr('js-placeholder');
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
            $(document).on('click', '.remove', function () {
                var $dropdown = $(this).parents('.nice-select');
                var clickedId = $(this).parent().data('id')
                $dropdown.find('.list li').each(function (index, item) {
                    if (clickedId == $(item).attr('data-value')) {
                        $(item).removeClass('selected')
                    }
                })
                $selected_values.forEach(function (item, index, object) {
                    if (item === clickedId) {
                        object.splice(index, 1);
                    }
                });
                $(this).parent().remove();
                console.log($('.mySelect').val())
            })

            // Keyboard events
            $(document).on('keydown.nice_select', '.nice-select', function (event) {
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

    // $(document).ready(function () {
    //     $('#selectR').niceSelect();
    //     $('#country').niceSelect();
    //     $('#selectC').niceSelect();
    // });



</script>

<script>


    // Stat Captcha code

    var randomNum1;
    var randomNum2;
    var maxNum = 20;
    var total;

    randomNum1 = Math.ceil(Math.random() * maxNum);
    randomNum2 = Math.ceil(Math.random() * maxNum);
    total = randomNum1 + randomNum2;

    $("#question").prepend(randomNum1 + " + " + randomNum2 + "=");

    $("#reset").on("click", function () {
        randomNum1 = Math.ceil(Math.random() * maxNum);
        randomNum2 = Math.ceil(Math.random() * maxNum);
        total = randomNum1 + randomNum2;
        $("#question").empty();
        $("#ans").val('');
        $("#question").prepend(randomNum1 + " + " + randomNum2 + "=");
    });



    // End Captcha code


    let form = document.getElementById('contactForms');

    let btn = form.getElementsByTagName('button');

    form.addEventListener('submit', (evt) => {



        var input = $("#ans").val();

        if (input != total) {
            $(".captchaErr").show();
            setTimeout(function () {
                $(".captchaErr").hide();
            }, 4000);
            $("#reset").click();
            evt.preventDefault();
            return false;
        }
        else if ($("#checkFill").val() != "") {
            evt.preventDefault();
            return false;
        }
        else {


            setTimeout(() => {
                for (let j = 0; j < btn.length; j++) {
                    btn[j].setAttribute('disabled', 'true');
                    btn[j].classList.add('no-click');
                    btn[j].innerHTML = '<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>'

                }
            }, 200);
        }
    })
</script>

<?php require_once 'include/footer.php';?>