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
    let country = document.getElementById("country").value;
    let adr = document.getElementById("adr").value;
    let city = document.getElementById("city").value;
    let zip = document.getElementById("zip").value;

    if(fname!="" && emailid!="" && country!="" && adr!="" && city!="" && zip!="")
    {    

    setLoading(true);
    
    let subscr_plan_id = document.getElementById("subscr_plan").value;
    let customer_name = document.getElementById("name").value;
    let customer_email = document.getElementById("email").value;
    let change_id = document.getElementById("change_id").value;
    let t_Price = document.getElementById("t_Price").value;
    let coupon_id = document.getElementById("coupon_id").value;

    let discount_amount = document.getElementById("discount_amount").value;

    let sid_id = document.getElementById("sid_id").value;

    let tax_price = document.getElementById("tax_price").value;
    let gst_number = document.getElementById("gstNumber").value;

    // for trial
    let with_trial = document.getElementById("with_trial").value;
    
    // Post the subscription info to the server-side script
    fetch("payment_init_ind.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_type:'create_customer_subscription', subscr_plan_id: subscr_plan_id, name: customer_name, email: customer_email, change_id: change_id,t_Price:t_Price,sid_id:sid_id,coupon_id:coupon_id,gst_number:gst_number,discount_amount:discount_amount,with_trial:with_trial }),
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
    let gst_number = document.getElementById("gstNumber").value;
    let discount_amount = document.getElementById("discount_amount").value;

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
            fetch("payment_init_ind.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ request_type:'payment_insert', subscription_id: subscriptionId, customer_id: customerId, subscr_plan_id: subscr_plan_id,payment_intent: result.paymentIntent ,change_id : change_id ,t_Price:t_Price ,tax_price:tax_price,sid_id:sid_id,coupon_id:coupon_id,gst_number:gst_number,discount_amount:discount_amount}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.payment_id) {
                    window.location.href = 'payment-status-ind.php?sid='+data.payment_id+"&change_id="+change_id;
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
        let country = document.getElementById("country").value;
        let adr = document.getElementById("adr").value;
        let city = document.getElementById("city").value;
        let zip = document.getElementById("zip").value;

        if (fname != "" && emailid != "" && country != "" && adr != "" && city != "" && zip != "") {

            setLoading(true);

            let subscr_plan_id = document.getElementById("subscr_plan").value;
            let customer_name = fname ;
            let customer_email = emailid ;
            let change_id = document.getElementById("change_id").value;
            let t_Price = document.getElementById("t_Price").value;
            let coupon_id = document.getElementById("coupon_id").value;

            let discount_amount = document.getElementById("discount_amount").value;

            let sid_id = document.getElementById("sid_id").value;

            let tax_price = document.getElementById("tax_price").value;
            let gst_number = document.getElementById("gstNumber").value;

            // for trial 
            let with_trial = document.getElementById("with_trial").value;
            let country_label = $("#country option:selected").text();
            let cancel_url = document.getElementById("cancel_url").value;

            // let t_Price = document.getElementById("price_cal").value;
            

            // Post the subscription info to the server-side script
            fetch("payment_init_ind.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        request_type: 'create_customer_trial',
                        subscr_plan_id: subscr_plan_id,
                        name: customer_name,
                        email: customer_email,
                        change_id: change_id,
                        t_Price: t_Price,
                        sid_id: sid_id,
                        coupon_id: coupon_id,
                        gst_number: gst_number,
                        discount_amount: discount_amount,
                        with_trial: with_trial,
                        cancel_url: cancel_url,
                        t_Price:t_Price,
                        tax_price:tax_price
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sessionId && data.sessionUrl) {
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


            $("#main-billing h3").append('<small class="required_billing" style="color:red;">Please fill all the required field.</small>');

            setTimeout(function () {
                $(".required_billing").remove();
            }, 5000);

        }
    }

}

/*** END code for trial subscription ***/