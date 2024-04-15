<?php require_once 'include/header.php';?>


<div class="banner__sec__agency banner__sec__agency_new" >
    <div class="section__wrapper">
        <div class="heading" >
            <h1><span>Become an Agency</span> Partner with Us</h1>
            <p>Enroll in our Partnership Plan today and earn a generous <span>30% commission!</span></p>
            <a href="#agency-partner-form" class="btn scroll_to">Apply Now</a>
        </div>
    </div>
    <div class="top_bg"></div>
</div>  


<div class="benifits__section benifits__section_new" >
    <div class="section__wrapper">
        <div class="heading">
            <h2>Website Speedy Agency <span>Partner Benefits</span></h2>
        </div>
        <div class="d-grid">
            <div class="agency_card">
                <h4>Up to 30% recurring commission</h4>
                <p>Earn up to 30% commission on a recurring basis for every successful referral.</p>
            </div>
            <div class="agency_card">
                <h4>Premium Support</h4>
                <p>You'll receive premium support from our dedicated team to ensure your success.</p>
            </div>
            <div class="agency_card">
                <h4>Generate more leads</h4>
                <p>You can generate more leads for your business, creating opportunities for growth and increased revenue.</p>
            </div>
            <div class="agency_card">
                <h4>Get listed as a partner</h4>
                <p>Opportunity to become our exclusive partner, increasing your visibility and credibility within the industry.</p>
            </div>
            <div class="agency_card">
                <h4>Agency dashboard</h4>
                <p>Access to your own personal agency dashboard, providing you with valuable insights to drive growth.</p>
            </div>
        </div>
    </div>
</div>



<div class="form__contact agency-partner-form" id="agency-partner-form">
    <div class="section__wrapper" >
        <iframe  src="https://cdn.forms-content.sg-form.com/8bc05f07-c3ee-11ed-b44e-065355863526"></iframe> 
    </div>
</div>

<script>
    const links = document.querySelectorAll(".scroll_to");
    for (const link of links) {
    link.addEventListener("click", scrollTop);
    }

    function scrollTop(e) {
        e.preventDefault();
        const href = this.getAttribute("href");
        const offsetTop = document.querySelector(href).offsetTop;
        scroll({
            top: offsetTop,
            behavior: "smooth"
        });
    }
</script>
 
<?php require_once 'include/footer.php';?>




