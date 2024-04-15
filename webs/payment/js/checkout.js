// Get API Key
let STRIPE_PUBLISHABLE_KEY = document.currentScript.getAttribute('STRIPE_PUBLISHABLE_KEY');

// Create an instance of the Stripe object and set your publishable API key
const stripe = Stripe(STRIPE_PUBLISHABLE_KEY);


var cardElement = '' ;
if ( document.querySelector("#subscrFrm") ) {

    // Select subscription form element
    const subscrFrm = document.querySelector("#subscrFrm");

    // Attach an event handler to subscription form
    subscrFrm.addEventListener("submit", handleSubscrSubmit);

    let elements = stripe.elements();
    var style = {
        base: {
            lineHeight: "30px",
            fontSize: "16px",
            border: "1px solid #ced4da",
        }
    };
    
    cardElement = elements.create('card', { style: style });
    // cardElement = elements.create('p24Bank', { style: style });
    cardElement.mount('#card-element');

    cardElement.on('change', function (event) {
        displayError(event);
    });

}



function displayError(event) {
    if (event.error) {
        showMessage(event.error.message);
    }
}

async function handleSubscrSubmit(e) {
    e.preventDefault();

    let fname = document.getElementById("fname").value;
    let emailid = document.getElementById("emailId").value;
    // let phone = document.getElementById("contact_number").value; //123
    let country = document.getElementById("country").value;
    let adr = document.getElementById("adr").value;
    let city = document.getElementById("city").value;
    let zip = document.getElementById("zip").value;
    let vatNumber = document.getElementById("vatNumber").value;

    var phone = $("#contact_number").val().trim() ;


    let requested_views = document.getElementById("requested_views").value;
    let requested_price = document.getElementById("requested_price").value;

    var vatValidation = 0;

    if(vatNumber!="")
    {
        if(vatNumber.length <=4 || vatNumber.length >20 ){
                vatValidation = 1;
                $("#vatNumber").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;"> Please Enter a valid VAT number</small>');
        }

    }


    //123
    if(fname!="" && emailid!="" && country!="" && adr!="" && city!="" && zip!="" && phone!=""  && vatValidation == 0)
    {



    setLoading(true);
    let subscr_plan_id = document.getElementById("subscr_plan").value;
    let customer_name = document.getElementById("name").value;
    let customer_email = document.getElementById("email").value;
    let customer_phone =  phone; //123
    let change_id = document.getElementById("change_id").value;
    let t_Price = document.getElementById("t_Price").value;
    let coupon_id = document.getElementById("coupon_id").value;
    let vat_number = document.getElementById("vatNumber").value;


    let sid_id = document.getElementById("sid_id").value;

    let tax_price = document.getElementById("tax_price").value;
    /*** start metadata ***/
    let plan_name = document.getElementById("plan_name").value;
    let plan_type = document.getElementById("plan_type").value;
    /*** end metadata ***/
	
    // Post the subscription info to the server-side script //123
    fetch("payment_init.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ requested_views:requested_views, requested_price:requested_price, request_type:'create_customer_subscription', subscr_plan_id: subscr_plan_id, name: customer_name, email: customer_email, phone : customer_phone, change_id: change_id,t_Price:t_Price,sid_id:sid_id,coupon_id:coupon_id, vat_number:vat_number, plan_name:plan_name , plan_type:plan_type }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.subscriptionId && data.clientSecret) {
            paymentProcess(data.subscriptionId, data.clientSecret, data.customerId);
        } else {
            showMessage(data.error);
            if(data.error =="Inviled token please reload the page.")
            {
                location.reload();
            }
        }
		
        setLoading(false);
    })
    .catch(console.error);
    }
    else{


        if(fname==""){
            $("#fname").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(emailId==""){
            $("#emailId").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(country==""){
            $("#country").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(adr==""){
            $("#adr").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(city==""){
            $("#city").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(zip==""){
            $("#zip").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }  
        //123      
        if(phone=="" || phone==" " || phone == null || phone == undefined ){
            $("#contact_number").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }        

  
            $("#main-billing h3").append('<small class="required_billing" style="color:red;">Please fill all the required field.</small>');
        
        setTimeout(function(){
            $(".required_billing").remove();
        },5000);


    }
}

function paymentProcess(subscriptionId, clientSecret, customerId){
    setProcessing(true);
	
    let subscr_plan_id = document.getElementById("subscr_plan").value;
    let customer_name = document.getElementById("name").value;
    let change_id = document.getElementById("change_id").value;
    let t_Price = document.getElementById("price_cal").value;
    let tax_price = document.getElementById("tax_price").value;
	let sid_id = document.getElementById("sid_id").value;
    let coupon_id = document.getElementById("coupon_id").value;
    let vat_number = document.getElementById("vatNumber").value;

    let requested_views = document.getElementById("requested_views").value;
    let requested_price = document.getElementById("requested_price").value;

    // Create payment method and confirm payment intent.
    stripe.confirmCardPayment(clientSecret, {
        payment_method: {
            card: cardElement,
            billing_details: {
                name: customer_name,
            },
        }
    }).then((result) => {
        if(result.error) {
            showMessage(result.error.message);
			
            setProcessing(false);
            setLoading(false);
        } else {
            // Successful subscription payment
            console.log(result);
            // Post the transaction info to the server-side script and redirect to the payment status page
            fetch("payment_init.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ requested_views:requested_views , requested_price:requested_price , request_type:'payment_insert', subscription_id: subscriptionId, customer_id: customerId, subscr_plan_id: subscr_plan_id,payment_intent: result.paymentIntent ,change_id : change_id ,t_Price:t_Price ,tax_price:tax_price,sid_id:sid_id,coupon_id:coupon_id,vat_number:vat_number}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.payment_id) {
                    window.location.href = 'payment-status.php?sid='+data.payment_id+"&change_id="+change_id;
                } else {
                    showMessage(data.error);
					
                    setProcessing(false);
                    setLoading(false);
                }
            })
            .catch(console.error);
        }
    });
}

// Display message
function showMessage(messageText) {
    const messageContainer = document.querySelector("#paymentResponse");
	
    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;
	
    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageText.textContent = "";
    }, 5000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submitBtn").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#buttonText").classList.add("hidden");
    } else {
        // Enable the button and hide spinner
        document.querySelector("#submitBtn").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#buttonText").classList.remove("hidden");
    }
}


// Show a spinner on payment submission
function setLoading_(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submitBtn_lifetime").disabled = true;
        document.querySelector("#spinner_").classList.remove("hidden");
        document.querySelector("#buttonText_").classList.add("hidden");
    } else {
        // Enable the button and hide spinner
        document.querySelector("#submitBtn_lifetime").disabled = false;
        document.querySelector("#spinner_").classList.add("hidden");
        document.querySelector("#buttonText_").classList.remove("hidden");
    }
}
// Show a spinner on payment form processing
function setProcessing(isProcessing) {
    if (isProcessing) {
        subscrFrm.classList.add("hidden");
        document.querySelector("#frmProcess").classList.remove("hidden");
    } else {
        subscrFrm.classList.remove("hidden");
        document.querySelector("#frmProcess").classList.add("hidden");
    }
}


/*** code for trial subscription ***/

if ( document.querySelector("#subscrTrialFrm") ) {

    // Select subscription trial form element
    const subscrTrialFrm = document.querySelector("#subscrTrialFrm");

    // Attach an event handler to subscription form
    subscrTrialFrm.addEventListener("submit", handleSubscrTrialSubmit);

    async function handleSubscrTrialSubmit(e) {
        e.preventDefault();

        let fname = document.getElementById("fname").value;
        let emailid = document.getElementById("emailId").value;
        // let phoneNo = '9876543210'; //123
        // let phoneNo = document.getElementById("contact_number").value;
        let country = document.getElementById("country").value;
        let adr = document.getElementById("adr").value;
        let city = document.getElementById("city").value;
        let zip = document.getElementById("zip").value;

        var phoneNo = $("#contact_number").val().trim();

        //123
        if (fname != "" && emailid != "" && country != "" && adr != "" && city != "" && zip != "" && phoneNo !="") {

            setLoading(true);

            let subscr_plan_id = document.getElementById("subscr_plan").value;
            let customer_name = fname ;
            let customer_email = emailid ;
            let customer_phone = phoneNo ;//123
            let change_id = document.getElementById("change_id").value;
            let t_Price = document.getElementById("t_Price").value;
            let coupon_id = document.getElementById("coupon_id").value;

            let discount_amount = document.getElementById("discount_amount").value;

            let sid_id = document.getElementById("sid_id").value;

            let tax_price = document.getElementById("tax_price").value;
            // let gst_number = document.getElementById("gstNumber").value;
            let vatNumber = document.getElementById("vatNumber").value;

            // for trial 
            let with_trial = document.getElementById("with_trial").value;
            let country_label = $("#country option:selected").attr("data-sortname") ;
            let cancel_url = document.getElementById("cancel_url").value;

            // let t_Price = document.getElementById("price_cal").value;
            

            // Post the subscription info to the server-side script
            fetch("payment_init.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        request_type: 'create_customer_trial',
                        subscr_plan_id: subscr_plan_id,
                        name: customer_name,
                        email: customer_email,
                        phone : customer_phone, //123
                        change_id: change_id,
                        t_Price: t_Price,
                        sid_id: sid_id,
                        coupon_id: coupon_id,
                        vatNumber: vatNumber,
                        discount_amount: discount_amount,
                        with_trial: with_trial,
                        cancel_url: cancel_url,
                        t_Price:t_Price,
                        tax_price:tax_price,
                        country_label:country_label
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sessionId && data.sessionUrl) {
                        // alert(data.sessionUrl) ;
                        window.location.href = data.sessionUrl ;
                    }
                    else {
                        showMessage(data.error);
                        if (data.error == "Inviled token please reload the page.") {
                            location.reload();
                        }
                    }

                    setLoading(false);
                })
                .catch(console.error);
        }
        else {

            if (fname == "") {
                $("#fname").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }

            if (emailId == "") {
                $("#emailId").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }

            if (country == "") {
                $("#country").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }

            if (adr == "") {
                $("#adr").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }

            if (city == "") {
                $("#city").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }

            if (zip == "") {
                $("#zip").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }
            //123


            if (phoneNo == "" || phoneNo == " " || phoneNo == null || phoneNo == undefined) {
                $("#contact_number").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            }


            $("#main-billing h3").append('<small class="required_billing" style="color:red;">Please fill all the required field.</small>');

            setTimeout(function () {
                $(".required_billing").remove();
            }, 5000);

        }
    }

}

/*** END code for trial subscription ***/