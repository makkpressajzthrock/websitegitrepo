<?php 
require_once("/var/www/html/adminpannel/env.php") ;
	include('config.php');
	require_once("inc/functions.php");
?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<!-- Page content wrapper-->
			<div id="page-content-wrapper" class="appsuno_container_wrapper">
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
				<div class="appsuno_container">
				<div class="appsuno_left">
					<div class="logo">
						<a href="<?=HOST_URL?>" ><img src="../img/signup_logo.png" alt="Website Speedy Logo"></a>
					</div>
					<div class="logo_content">
						<h2>Welcome to Websitespeedy</h2>
						<p>Instantly reduce your
website loading time by 3X</p>
<br>
After registration please <a class="bold__link" style="text-transform: capitalize;" href="<?=HOST_URL?>adminpannel/">click here</a>.
<br>
<p>Once you log in, you can claim the lifetime deal.</p>
					</div>
				</div>	
				<div class="container-fluid  appsuno_right">
					<div class="alert-status"></div>
					<form class="registerform" method="post">
						
					   	<div class="heading text-center full__col__grid">
					      	<h1 class="text-dark">Lifetime access</h1>
					      	<div class="text-gray-400 fw-bold fs-4">Redeem your code and get your lifetime access.</div>
					   	</div>
					   	<!---->
					   	<div class="fv-row "><label class="form-label fw-bolder text-dark fs-6">First Name</label><input class="form-control form-control-solid" type="text" placeholder="Enter your first name" name="fname" required="" required></div>
					   	<div class="fv-row ">
					   		<label class="form-label fw-bolder text-dark fs-6">Last Name</label>
					   		<input class="form-control form-control-solid" type="text" placeholder="Enter your last name" name="lname" required="" required>
					   	</div>


						<div class="fv-row full__col__grid">
							<div class="row">
								<div class="col-4">
									<label for="country_code" class="form-label fw-bolder text-dark fs-6">Contact Number</label>
									<div class="for__arrow">
									<select id="country_code" name="country_code" class="form-control form-control-solid" >
										<option value="">Country Code</option>
										<?php
                                        $selected = "231";
										        $list_countries1 = getTableData( $conn , " list_countries " , "" , " group by sortname order by name" , 1);
												foreach ($list_countries1 as $key => $country_data) { 
										?>			
												<option value='+<?=$country_data["phonecode"]?>'  <?php if($country_data["id"]==$selected){echo "selected";} ?> ><?=$country_data["name"]?> +<?=$country_data["phonecode"]?></option>
										<?php
												}
										?>
									</select>
									</div>

								</div>
								<div class="col-8" style="padding: 0px 10px 0px 0px;">
								    <label for="phone" class="form-label fw-bolder text-dark fs-6" style="visibility:hidden;opacity:0;">Phone</label>
									<input type="number"  class="form-control form-control-solid" id="phone" placeholder="Enter your phone number" name="phone" >
								</div>
							</div>
						</div>					   	
					   	<div class="fv-row full__col__grid"><label class="form-label fw-bolder text-dark fs-6">Email</label><input class="form-control form-control-solid" type="email" placeholder="Enter your email" name="email" required="" required></div>
					   	<div class="mb-7 fv-row" data-kt-password-meter="true">
					      	<div class="">
					         	<label class="form-label fw-bolder text-dark fs-6">Password</label>
					         	<div class="position-relative "><input class="form-control form-control-solid" type="password" placeholder="Enter your password" name="password" required><span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility"><i class="bi bi-eye-slash fs-2"></i><i class="bi bi-eye fs-2 d-none"></i></span></div>
					         	<div class="d-flex align-items-center " data-kt-password-meter-control="highlight">
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
					         	</div>
					      	</div>
					      	<div class="text-muted"><!-- Use 8 or more characters with a mix of letters, numbers &amp; symbols. --></div>
					   	</div>
					   	<div class="fv-row"><label class="form-label fw-bolder text-dark fs-6">Confirm Password</label><input class="form-control form-control-solid" type="password" placeholder="Confirm password" name="confirm-password"required></div>


                        <div class="fv-row full__col__grid">
                            <div class="row">
                                <div class="col-12">
                                    <label for="country_code" class="form-label fw-bolder text-dark fs-6">Where did you buy the LTD from?</label>
                                    <div class="for__arrow">
                                        <select id="ltd_from" name="ltd_from" class="form-control form-control-solid" >
                                            <option value="">Select Option</option>
                                            <option value="AppSumo">AppSumo</option>
                                            <option value="Dealify">Dealify</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
					   	<div class="fv-row full__col__grid"><label class="form-label fw-bolder text-dark fs-6">LTD code</label><input class="form-control form-control-solid" type="text" placeholder="Enter your LTD code" 
					   		name="coupon-code" required></div>

					   	<!---->
					   	<div class="text-center full__col__grid"><button type="button" id="kt_free_trial_submit" class="btn btn-lg btn-primary fw-bolder formsubmit" name="submit"><span class="indicator-label">Create an Account</span><span class="indicator-progress" style="display: none;">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span></button></div>
					   	<div class="text-center full__col__grid"><a class="bold__link text-muted me-3 text-decoration-none" href="<?=HOST_URL?>adminpannel/">Already registered? Login Here</a></div>
					</form>							
				</div>

                </div>
			</div>
		</div>
	</body>
	<script>
	$('.formsubmit').click(function(){
        $.ajax({
            type: 'post',
            url: 'SaveSumoCode.php',
            data: $('form').serialize(),
            success: function (response) {
            	console.log("response="+response);
            	if(response == 4) 
            	{
                    $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid Sumo Code<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 5)
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid Email<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 3)
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Password And Confirm Password Should Have Same Value<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 2 || response == 1) 
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Error In Registration<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 0)
            	{
                    $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">Registered Successfully. Click Here to <a href="<?=HOST_URL?>adminpannel/" class="bold__link" style="text-transform: capitalize;">Login</a>. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;


					     $.ajax({
					      type: "POST",
					      url: "https://help.websitespeedy.com/actions/account/register",
					      data: {
						     first_name:$("input[name='fname']").val(),
						     last_name: $("input[name='lname']").val(),
						     email_address : $("input[name='email']").val(),
						     password : $("input[name='password']").val(),
						     retype_password : $("input[name='password']").val(),
						     terms : "on"			
						     },
					      dataType: "json",
					      encode: true,
					    }).done(function (data) {
					       window.location.href = "<?=HOST_URL?>adminpannel/";
					    }).fail(function(data){
					       window.location.href = "<?=HOST_URL?>adminpannel/";
						});

                    $("input,select").val("");
            	}
            	else if(response == 7)
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Please Fill all the field<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	} 
                 else if(response == 6) 
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Email already taken!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}   
				
				setTimeout(function(){
					//$('.alert').remove()
				}, 10000)
				
            }
        });
	});
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
    $('#country_code').niceSelect();
});



</script>

</html>