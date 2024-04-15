/*!
* Start Bootstrap - Simple Sidebar v6.0.3 (https://startbootstrap.com/template/simple-sidebar)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-simple-sidebar/blob/master/LICENSE)
*/
// 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});


/*
* Custom script  =================================================================
*/

function swalForDashboardDomain ( title , content ) {

    Swal.fire({
        title: title ,
        icon: "error",
        text: content,
        showDenyButton: false,
        showCancelButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        // confirmButtonText: 'Close',
        showCloseButton: true,
    }).then((result) => {
        if (result.isConfirmed) {}
    }) ; 

}

$(document).ready(function () {

    $(".past-due-alert").click(function(){

        var date = $(this).attr("data-date") ;

        Swal.fire({
            title: 'Subcription Past Due!',
            icon: 'info',
            // text: 'Our AI was unable to fetch Website speed data at the moment, Don’t worry You can reanalyse while installing.',
            html:'<h5>Your subscription payment could not be processed. Please complete your payment to ensure uninterrupted service.</h5><p>This speedy key script will work for 2 months (i.e.'+date+'), after which your account will be deleted.</p>',
            showDenyButton: true,
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            // confirmButtonText: 'Reload',
            denyButtonText: `Close`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                // $(".loader").show();
                // $(reloadSpeed).click();
            }
        }) ;
    });




    $(".dropdown-toggle").click();
    //--------------manger-settings-page--------------------//
    // $(".nav_btn").click(function () {
    //     var menu = $(this).data("select");

    //     console.log(menu);
    //     $(".nav_btn").removeClass("active");
    //     $(this).addClass("active");
    //     var tab_name = $('.nav_btn.active').attr("data-select");
    //     if (tab_name == "Profile") {

    //         $(".tab").addClass("d-none");
    //         $(".profile").removeClass("d-none");


    //     }
    //     if (tab_name == "Payment") {
    //         $(".tab").addClass("d-none");
    //         $(".Payment_method").removeClass("d-none");

    //     }
    //     if (tab_name == "Teams") {
    //         $(".tab").addClass("d-none");
    //         $(".teams_cover").removeClass("d-none");

    //     }
    //     if (tab_name == "Security") {
    //         $(".tab").addClass("d-none");
    //         $(".security_cover").removeClass("d-none");
    //     }

    //     if (tab_name == "Subscribe") {
    //         $(".tab").addClass("d-none");
    //         $(".subscribe_cover").removeClass("d-none");
    //     }
    //     if (tab_name == "Invoices") {
    //         $(".tab").addClass("d-none");
    //         $(".invoices_cover").removeClass("d-none");
    //     }
    // });

    // var tab_name = $('.nav_btn.active').attr("data-select");
    // if (tab_name == "Profile") {

    //     $(".tab").addClass("d-none");
    //     $(".profile").removeClass("d-none");


    // }
    // if (tab_name == "Payment") {
    //     $(".tab").addClass("d-none");
    //     $(".Payment_method").removeClass("d-none");

    // }
    // if (tab_name == "Teams") {
    //     $(".tab").addClass("d-none");
    //     $(".teams_cover").removeClass("d-none");

    // }
    // if (tab_name == "Security") {
    //     $(".tab").addClass("d-none");
    //     $(".security_cover").removeClass("d-none");
    // }
    // if (tab_name == "Subscribe") {
    //     $(".tab").addClass("d-none");
    //     $(".subscribe_cover").removeClass("d-none");
    // }
    // if (tab_name == "Invoices") {
    //     $(".tab").addClass("d-none");
    //     $(".invoices_cover").removeClass("d-none");
    // }
    $('#ccnum').on('keypress change blur', function () {
        $(this).val(function (index, value) {
            return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{17})/g, '$1 ');
        });
    });

    if ($("#expmonth").length > 0) {
        document.getElementById('expmonth').addEventListener('keypress', event => {
            if (!`${event.target.value}${event.key}`.match(/^[0-9]{0,2}$/)) {
                // block the input if result does not match
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        });
    }

    if ($("#expyear").length > 0) {
        document.getElementById('expyear').addEventListener('keypress', event => {
            if (!`${event.target.value}${event.key}`.match(/^[0-9]{0,4}$/)) {
                // block the input if result does not match
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        });
    }

    if ($("#cvv").length > 0) {
        document.getElementById('cvv').addEventListener('keypress', event => {
            if (!`${event.target.value}${event.key}`.match(/^[0-9]{0,3}$/)) {
                // block the input if result does not match
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        });
    }

    //--------------end manger-settings-page--------------------//

    if ($(".auto-scan-website").length > 0) {

        $(".auto-scan-website").change(function () {

            var autoscan = 0;
            var checked = $(this).prop("checked");
            if (checked) { autoscan = 1; }
            var project = $(this).data("project");

            $.ajax({
                url: "inc/update-csc.php",
                method: "POST",
                dataType: "JSON",
                data: { autoscan: autoscan, project: project }
            }).done(function (res) {

                if (res.status == 1) {
                    $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + res.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }
                else {
                    $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + res.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }

            }).fail(function () {
                console.log("error");
            });

        });
    }

    if ($(".reanalyze-btn").length > 0) {
        $(".reanalyze-btn").click(function () {

            var tbl = $("#page-speed-table");
            var project = tbl.attr("data-project");
            var type = tbl.attr("data-type");
            var addi = $(this).attr("data-additional");
            var website_name = $(this).attr("data-website_name");
            var ps_mobile = $(this).attr("data-ps_mobile");
            var ps_performance = $(this).attr("data-ps_performance");
            var ps_accessibility = $(this).attr("data-ps_accessibility");
            var ps_best_practices = $(this).attr("data-ps_best_practices");
            var ps_pwa = $(this).attr("data-ps_pwa");
            var website_url = $(this).attr("data-website_url");
            var ps_desktop = $(this).attr("data-ps_desktop");
            var ps_seo = $(this).attr("data-ps_seo");

            var website_url_core = $(this).attr("data-website_url_core");
            var fcp = $(this).attr("data-fcp");
            var lcp = $(this).attr("data-lcp");
            var mpf = $(this).attr("data-mpf");
            var cls = $(this).attr("data-cls");
            var ps_tbt = $(this).attr("data-ps_tbt");

            var speedtype = $(this).attr("data-speedtype");

            var tr = $(this).closest("tr");

            $.ajax({
                url: "inc/update-pagespeed.php",
                method: "POST",
                data: {
                    project: project, type: type, additional: addi,
                    ps_best_practices: ps_best_practices, ps_pwa: ps_pwa, ps_seo: ps_seo, website_url: website_url,
                    ps_desktop: ps_desktop, website_name: website_name, ps_mobile: ps_mobile, ps_performance: ps_performance, ps_accessibility: ps_accessibility,
                    website_url_core: website_url_core, fcp: fcp, lcp: lcp, mpf: mpf, cls: cls, ps_tbt: ps_tbt, speedtype: speedtype
                },
                dataType: "JSON",
                timeout: 0,
                beforeSend: function () {
                    $(".loader").show().html("<div class='loader_s'><h2>Getting Current speed score for Google PageSpeed Insights. This process typically takes between 2-3 minutes.</h2><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>-0.1 seconds of loading can result in +8% conversions.<p><div class='auto-type-three' ></div></div>");
                    function loaderestThree() {
                        var typed = new Typed('.auto-type-three', {   
                        strings: ['Webpages should take only 1 or 2 seconds to load to reduce bounce rate to 9% - Research by Pingdom', '1 in 3 consumers say they’ll leave a brand they love after just one bad experience - Research by PWC', '500 milliseconds of extra loading results in a traffic drop of 20% - Research by Google', '500 milliseconds of extra loading results in a traffic drop of 20% - Research by Google', 'Every extra 100 milliseconds of loading decreases sales by 1%  - Research by Amazon', 'With a 0.1s improvement in site speed, retail consumers spent almost 10% more - Research by Deloitte', 'An ecommerce site that loads within 1 converts 2.5x than a site that loads in 5 seconds - Research by Portent', '36.8% of shoppers are less likely to return if page loads slowly - Research by Google', '53% of mobile visitors will leave a page if it takes more than 3 seconds to load - Research by EY'],
                        typeSpeed: 20,
                        backSpeed: 20,
                        backDelay: 3000,
                        loop: true,
                      });
                    }
                    loaderestThree();


                },
                success: function (data) {

                    if (data.status == "done") {


                        var content = data.message;
                        console.log(content);
                                 if(content.requested_Url==0){
                            // console.log(content.requested_Url);

                                    // console.log("hi");
                                     $(".fa-arrows-rotate").show();
                                    $(".loader_icon").hide();

                                    Swal.fire({
                                    title: 'Something went wrong !',
                                    text: "Data is not gettng by out AI from Google Page Insight. Please try again!",
                                    icon: 'warning',
                                    showCancelButton: false,
                                    showDenyButton: true,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    denyButtonText: 'Contact Speddy Support',
                                    confirmButtonText: 'Try Again!'
                                    
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                   
                                    $(".reanalyze-btn").click();

                                    } else if (result.isDenied) {
                                        window.location.href="support-ticket.php";
                                        // Swal.fire('Query successfully saved', '', 'success')
                                      }
                                    });
                                    }else{
                                        if($(".page-speed").hasClass("btn-primary")==true){

                                        setTimeout(window.location.reload(), 250);
                                        $("tab-1").show();
                                        $("tab-2").hide();
                                    }else{
                                        $("tab-1").hide();
                                        $("tab-2").show();

                                       setTimeout(window.location.reload(), 250);

                                    }
                                    }
                    

                        if (type == "page-speed") {
                            $(tr).find(".desktop").text(content.desktop);
                            $(tr).find(".mobile").text(content.mobile);
                            $(tr).find(".performance").text(content.performance);
                            $(tr).find(".accessibility").text(content.accessibility);
                            $(tr).find(".best-practices").text(content.bestpractices);
                            $(tr).find(".seo").text(content.seo);
                            $(tr).find(".pwa").text(content.pwa);
                       
                            $(tr).find(".fcp").text(content.FCP);
                            $(tr).find(".lcp").text(content.LCP);
                            $(tr).find(".mpf").text(content.MPF);
                            $(tr).find(".cls").text(content.CLS);
                            $(tr).find(".tbt").text(content.TBT);
                        }

                        $(tr).find(".last-update").text(content.lastupdate);
                        $(".alert-status").html("");
                        if(content.requested_Url!=0){
                            location.reload();
                        }                        

                    }
                    else {
                        $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }

                },
                error: function (xhr) { // if error occured
                    console.error(xhr.statusText + xhr.responseText);
                },
                complete: function (data) {
                    $(".loader").hide().html("Please Wait...");
                        if (window.location.pathname == "/adminpannel/project-dashboard.php") {
                            // location.reload();
                        }
                        else if (window.location.pathname == "/adminpannel/dashboard.php") {
                            // location.reload();
                        }      
                                  }
            });

           
                var x = document.getElementById('loader_text');
            
                setTimeout(()=>{
                    x.innerHTML = "77% of smartphone users are more likely to purchase from a site that allows making purchases quickly.";
                },10000)
                setTimeout(()=>{
                    x.innerHTML = "The top 20 websites in the US have an average load time of 1.08 seconds.";
                },20000)
                setTimeout(()=>{
                    x.innerHTML = "Reducing loading times will increase the number of pages crawled by Google.";
                },30000) 
                setTimeout(()=>{
                    x.innerHTML = "Mobile site publishers whose sites load in 5 seconds generate 2x more ad revenue.";
                },40000) 
                setTimeout(()=>{
                    x.innerHTML = "Pages that load in less than 1 second have a 2.5x higher conversion rate.";
                },50000) 
                setTimeout(()=>{
                    x.innerHTML = "The first result on the Google results page has a 30% faster loading time than the 50th.";
                },60000) 
                setTimeout(()=>{
                    x.innerHTML = "The average speed of a page on the first page of Google results is 1.65 seconds.";
                },70000)  
                setTimeout(()=>{
                    x.innerHTML = "The average speed of a page on the first page of Google results is 1.65 seconds.";
                },80000) 
            
                $('.page_insight .tabber.tab-1 ').css('display', 'none');

        });


    }


    if ($(".url-monitoring").length > 0) {

        $(".url-monitoring").click(function () {

            var tbl = $("#page-speed-table");
            var project = tbl.attr("data-project");

            var btn = $(this);
            var addi = btn.attr("data-additional");
            var tr = btn.closest("tr");

            $.ajax({
                url: "inc/update-monitoring.php",
                method: "POST",
                data: { project: project, additional: addi },
                dataType: "JSON",
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {

                    if (data.status == "done") {
                        var msg = '';
                        if (data.message == 0) {
                            btn.removeClass("text-success").addClass("text-danger");
                            $(tr).find(".reanalyze-btn").attr("disabled", true);
                            msg = 'Monitoring stopped.';
                        }
                        else {
                            btn.addClass("text-success").removeClass("text-danger");
                            $(tr).find(".reanalyze-btn").attr("disabled", false);
                            msg = 'Monitoring started.';
                        }

                        $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + msg + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                    else {
                        $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }

                },
                error: function (xhr) { // if error occured
                    console.error(xhr.statusText + xhr.responseText);
                },
                complete: function () {
                    $(".loader").hide();
                }
            });

        });
    }



// $(document).ready(function () {
//     // datatable 
//     if ($(".speedy-table").length > 0) {
//         $(".speedy-table").DataTable();
//     }

//     // var total = 4000;
//     //     var view = 200;

//     //     var perc = ((view/total) * 100).toFixed(2);
//     //     console.log(perc);
//     //     $(".progress_per").html(perc+"%");
//     // console.log( $(".progress_per").html(perc));

//     $(".update-speed").click(function () {

//         $(this).text("Please Wait...");

//         var website = $(this).data("website");

//         var req = $.ajax({
//             method: "POST",
//             url: 'inc/update-speed.php',
//             data: { website: website },
//             dataType: "json",
//         });

//         req.done(function (response) {
//             console.log(response);
//             if (response != 0) {
//                 $("span#current-desktop").text(response.desktop);
//                 $('.cd-data').data('easyPieChart').update(response.desktop);

//                 $("span#current-mobile").text(response.mobile);
//                 $('.cm-data').data('easyPieChart').update(response.mobile);
//             }
//         });

//         req.fail(function (jqXHR, textStatus) {
//             console.log("jqXHR");
//             console.log(jqXHR);
//             console.log("textStatus" + textStatus);
//         });

//         req.always(function () {
//             $(".update-speed").text("Update Record");
//         });
//     });

//     // load chart
//     circleBarChart();

// });

// function circleBarChart() {
//     $('.chart').easyPieChart({
//         size: 160,
//         barColor: "#36e617",
//         scaleLength: 0,
//         lineWidth: 15,
//         trackColor: "#525151",
//         lineCap: "circle",
//         animate: 2000,
//     });
// }


function selected_url() {
    var id = window.location.href.split("project=")[1];



    $.ajax({
        url: "selected_url.php",
        method: "POST",
        dataType: "JSON",
        data: { id: id }
    }).done(function (res) {

        if (res.status == "done") {
            $(".project__dropdown").text(res.message);

            var website_id = $(".dropdown-menu li a");
            var url1 = res.message;
            for (let i = 0; i < website_id.length; i++) {

                var website__id = website_id[i];
                var url = $(website__id).text(); if (url == url1) { console.log(url); $(website__id).hide(); }


            }
        }
        else {
            console.log("hi");
        }

    }).fail(function () {
        console.log("error");
    });

}
selected_url();
$(document).ready(function () {
    $('.only_string').on('input', function () {
        if (!/[a-z]$/.test(this.value)) {
            this.value = this.value.slice(0, -1);
        }
    });
});



    
var reloadSpeed = null;

function try_again(website_id_main){

        $.ajax({
      type: "POST",
      url: "inc/maege_speed_remove.php",
      data: {id:website_id_main},
      dataType: "json",
      encode: true,
    }).done(function (data) {
      // console.log(data);
    }); 
            Swal.fire({
                  title: 'Someting Went Wrong!',
                  icon: 'error',
                  text: 'Our AI was unable to fetch Website speed data at the moment, Don’t worry You can reanalyse while installing.',
                  showDenyButton: true,
                  showCancelButton: false,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  confirmButtonText: 'Reload',
                  denyButtonText: `Cancel`,
                }).then((result) => {
                  /* Read more about isConfirmed, isDenied below */
                  if (result.isConfirmed) {
                   // $(".loader").show();
                   $(reloadSpeed).click();
                  }
                })

}

// var speedtype = "old";

// getUrlSpeedNoSpeedy("https://www.w3schools.com","desktop","AIzaSyDw2nckjNQeVLGw_BxcfIvLTw3NYONCuRE")
function getUrlSpeedNoSpeedy(url,plaform="desktop",apiKey) {

    if ( url.includes("?") ) {
        var request_url = url+"&nospeedy" ;
    }
    else {
        var request_url = url+"?nospeedy" ;
    }

    // Construct the API endpoint
    var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${apiKey}&strategy=${plaform}&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

    fetch(apiEndpoint).then(response => {
        console.log('test');
        if (!response.ok) {
            throw new Error('Please thoroughly review all your URLs within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
        }
        return response.json();
    })
    .then(data => {
        // Process your data here
        console.log(data) ;
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });

}



// for dashboard page to get domain speed.
var link1_desktop = link1_mobile = '' ;
$(".reanalyze-btn-new").click(function(){

    reloadSpeed = $(this);
    script_loading = 0;

    var website_url = $(this).attr("data-website_url");
    var speedtype = $(this).attr("data-speedtype");
    var website_id = $(this).attr("data-website_id");
    var table_id = $(this).attr("data-speedtype");


    var manager_id = $("#manager-id").val();
    var api_key = $("meta[name='google_pagespeed_api']").attr("content") ;

    if ( website_url.includes("?nospeedy") ) {
        var request_url = website_url ;
    }
    else {
        if ( website_url.includes("?") ) {
            var request_url = website_url+"&nospeedy" ;
        }
        else {
            var request_url = website_url+"?nospeedy" ;
        }
    }


    

    // loader
    $(".loader").show().html("<div class='loader_s'><h2>Getting Current speed score for Google PageSpeed Insights. This process typically takes between 2-3 minutes.</h2><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p><span class='auto-type-one'></span></p></div>");
    function loaderestOne() {
        var typed = new Typed('.auto-type-one', {   
        strings: ['Webpages should take only 1 or 2 seconds to load to reduce bounce rate ot 9% - Research by Pingdom', '1 in 3 consumers say they’ll leave a brand they love after just one bad experience - Research by PWC', '500 milliseconds of extra loading results in a traffic drop of 20% - Research by Google', '500 milliseconds of extra loading results in a traffic drop of 20% - Research by Google', 'Every extra 100 milliseconds of loading decreases sales by 1%  - Research by Amazon', 'With a 0.1s improvement in site speed, retail consumers spent almost 10% more - Research by Deloitte', 'An ecommerce site that loads within 1 converts 2.5x than a site that loads in 5 seconds - Research by Portent', '36.8% of shoppers are less likely to return if page loads slowly - Research by Google', '53% of mobile visitors will leave a page if it takes more than 3 seconds to load - Research by EY'],
        typeSpeed: 20,
        backSpeed: 20,
        backDelay: 3000,
        loop: true,
      });
    }
    loaderestOne();

    // get speed for desktop
    var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

    fetch(apiEndpoint).then(response => {
        console.log('test');
        if (!response.ok) {
            $(".loader").hide().html("");
            throw new Error('Please thoroughly review your domain URL to ensure it is correct.');
        }
        return response.json();
    })
    .then(data => {
        // Process your data here
        console.log(data) ;

        if ( data.hasOwnProperty("lighthouseResult") ) {

            var lighthouseResult = data.lighthouseResult ;

            var requestedUrl = lighthouseResult.requestedUrl ;
            var finalUrl = lighthouseResult.finalUrl ;
            var userAgent = lighthouseResult.userAgent ;
            var fetchTime = lighthouseResult.fetchTime ;
            var environment = JSON.stringify(lighthouseResult.environment) ;
            var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
            var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
            var audits = JSON.stringify(lighthouseResult.audits) ;
            var categories = JSON.stringify(lighthouseResult.categories) ;
            var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
            var i18n = JSON.stringify(lighthouseResult.i18n) ;

            var desktop = lighthouseResult.categories.performance.score ;
            desktop = Math.round(desktop * 100) ;

            if ( desktop > 0 ) {

                link1_desktop = desktop+"/100" ;

                $.ajax({
                    url: "inc/check-speed-fetch.php",
                    type: "post",
                    data: {
                        manager_id: manager_id,
                        website_url: website_url,
                        speedtype: speedtype,
                        website_id: website_id,
                        table_id:table_id,
                        // lighthouseResult:lighthouseResult,
                        requestedUrl:requestedUrl,
                        finalUrl:finalUrl,
                        userAgent:userAgent,
                        fetchTime:fetchTime,
                        environment:environment,
                        runWarnings:runWarnings,
                        configSettings:configSettings,
                        audits:audits,
                        categories:categories,
                        categoryGroups:categoryGroups,
                        i18n:i18n,
                        request_url:request_url,
                        action:"check-speed-fetch",
                    },
                    dataType: "JSON",
                    beforeSend: function () {},
                    success: function (obj) {

                        if (obj.status == 'done') {

                            link1_desktop = obj.message.desktop ;

                            // now get mobile speed ===========================
                            var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

                            fetch(apiEndpoint).then(response => {
                                if (!response.ok) {
                                    throw new Error('Please thoroughly review all your URLs within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Process your data here

                                if ( data.hasOwnProperty("lighthouseResult") ) {

                                    var lighthouseResult = data.lighthouseResult ;

                                    var requestedUrl = lighthouseResult.requestedUrl ;
                                    var finalUrl = lighthouseResult.finalUrl ;
                                    var userAgent = lighthouseResult.userAgent ;
                                    var fetchTime = lighthouseResult.fetchTime ;
                                    var environment = JSON.stringify(lighthouseResult.environment) ;
                                    var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
                                    var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
                                    var audits = JSON.stringify(lighthouseResult.audits) ;
                                    var categories = JSON.stringify(lighthouseResult.categories) ;
                                    var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
                                    var i18n = JSON.stringify(lighthouseResult.i18n) ;

                                    var mobile = lighthouseResult.categories.performance.score ;
                                    mobile = Math.round(mobile * 100) ;
                                    link1_mobile = mobile+"/100" ;

                                    $.ajax({
                                        url: "inc/check-speed-fetch.php",
                                        method: "POST",
                                        data: {
                                            manager_id: manager_id,
                                            website_url: website_url,
                                            speedtype: speedtype,
                                            website_id: website_id,
                                            table_id:table_id,
                                            // lighthouseResult:lighthouseResult,
                                            requestedUrl:requestedUrl,
                                            finalUrl:finalUrl,
                                            userAgent:userAgent,
                                            fetchTime:fetchTime,
                                            environment:environment,
                                            runWarnings:runWarnings,
                                            configSettings:configSettings,
                                            audits:audits,
                                            categories:categories,
                                            categoryGroups:categoryGroups,
                                            i18n:i18n,
                                            request_url:request_url,
                                            action:"check-speed-mobile-fetch",
                                        },
                                        dataType: "JSON",
                                        timeout: 0,
                                        success: function (obj) {

                                            if (obj.status == "done") {

                                                link1_mobile = obj.message.mobile ;

                                                // manage_additional_nospeedy_speed(boostId,obj.id,table_id);
                                                manage_speed($(".reanalyze-btn-new").attr("data-website_id"), $(".reanalyze-btn-new").attr("data-speedtype"),link1_desktop,link1_mobile);
                                            }
                                            else {

                                                $(".loader").hide().html("");
                                                swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+website_url+"!" , obj.message ) ;

                                            }

                                        },
                                        error: function (xhr) { // if error occured
                                            $(".loader").hide().html("");
                                            script_loading++;
                                            console.error(xhr.statusText + xhr.responseText);
                                            // setTimeout(function(){ $(".loader").hide().html('') ; },1000);
                                        },
                                        complete: function () { }

                                    });

                                }
                                else {
                                    
                                    $(".loader").hide().html("");

                                    var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                    swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+website_url+"!" , error ) ;
                                    
                                }
                            })
                            .catch(error => {

                                $(".loader").hide().html("");

                                var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+website_url+"!" , error ) ;

                                console.error('Fetch error:', error);

                            });

                        }
                        else {

                            $(".loader").hide().html("");

                            Swal.fire({
                                title: 'Error!',
                                icon: 'error',
                                text: obj.message,
                                showDenyButton: false,
                                showCancelButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: 'Close',
                            }).then((result) => {
                                if (result.isConfirmed) {}
                            }) ;

                        }

                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    },
                    complete:function() {}
                });

            }
            else {

                $(".loader").hide().html("");

                var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+website_url+"!" , error ) ;

                $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation timed out. Please raise your query for help.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

            }

        }
        else {

            $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation timed out. Please raise your query for help.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            $(".loader").hide().html("");
            var error = "Please thoroughly review your domain URL to ensure it is correct." ;
            swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+website_url+"!" , error ) ;
        
        }
    })
    .catch(error => {

        $(".loader").hide().html("");
        console.error('Fetch error:', error);
        swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+website_url+"!" , error ) ;
    });

});


function manage_speed(id, speedtype , link1_desktop='' , link1_mobile='') {
    $.ajax({
        type: "POST",
        url: "inc/manage_speed.php",
        data: {
            id: id,
            speedtype: speedtype
        },
        dataType: "json",
        encode: true,
    }).done(function (data) {

        setTimeout(function(){ $(".loader").hide().html(""); },1000);
        // window.location.reload();

        $(".link1-desktop-speed").html(link1_desktop);
        $(".link1-mobile-speed").html(link1_mobile);

        // Create a new Date object
        const currentDate = new Date();

        // Define options for formatting with 24-hour time
        const options = { 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric', 
          hour: 'numeric', 
          minute: 'numeric',
          hour12: false // Set to false for 24-hour format
        };

        // Format the date using toLocaleString
        const formattedDate = currentDate.toLocaleString('en-US', options);
        $(".link1-last-update").html(formattedDate);

    }).fail(function (jqXHR, textStatus) {

        setTimeout(function(){ $(".loader").hide().html(""); },1000);
        // window.location.reload();
    });
}


});


// // Form Button diabled
// $(document).ready(function() {
//     let form = document.getElementsByTagName('form');
    
//     for (let i = 0; i < form.length; i++) {
//         let btn = form[i].getElementsByTagName('button');

//         let inputBtns = form[i].getElementsByTagName('input');
//         let obj = [...inputBtns];
//         let inputBtn;
//         for (let k = 0; k < obj.length; k++) {
//             if (obj[k].hasAttribute('type', 'submit') ){
//                 inputBtn = obj[k]
//             }   
//         }

//         form[i].addEventListener('submit', ()=> {
//             setTimeout(() => {
//                 for (let j = 0; j < btn.length; j++) {
//                     btn[j].setAttribute('disabled', 'true');
//                     btn[j].classList.add('no-click');
//                     btn[j].innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" style="background:transparent; height:24px;width: auto;" viewBox="0 0 105 105" fill="#fff" style="&#10;    background: #000;&#10;"> <circle cx="12.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="0s" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5"> <animate attributeName="fill-opacity" begin="100ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="300ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="600ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="800ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="400ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="700ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="500ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="200ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> </svg>'

                    
                    
//                 }
//             }, 200);
//         })

//         if (typeof inputBtn != 'undefined') {
//             form[i].addEventListener('submit', ()=> {
//                 setTimeout(() => {
//                         inputBtn.setAttribute('disabled', 'true');
//                         inputBtn.classList.add('no-click');
//                         inputBtn.innerHTML = '<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>'
                        
//                 }, 200);
//             })
//         }
    
        
//     }
//     })


    
//     $(document).ready(function() { 
//         $('button').on('click', function(){
//             setTimeout(() => {
//                 if($(this).hasClass('dropdown-toggle') || $(this).hasClass('continue-to-step-2') || 
//                     $(this).hasClass('step-2-reanalyse') || $(this).hasClass('verification_btn') || 
//                     $(this).hasClass('i-need-help')  || $(this).hasClass('p-instruction') || $(this).hasClass('view-count-button') ||
//                      $(this).hasClass('goto-5') || $(this).hasClass('old-speed-btn') || $(this).hasClass('monthly-speed-btn') || $(this).hasClass('not__s_button')

//                      ) {

//                 }
//                 else if ($(this).hasClass('add__project__short')){
    
//                 }
//                 else if ($(this).hasClass('sidebarToggle')) {
                    
//                 }
//                 else if ($(this).hasClass('verification_btn')){
                    
//                 }
//                 else if($(this).attr('type')) {
                    
//                 }
//                 else {
//                     $(this).attr('disabled', 'true');
//                     $(this).addClass('no-click');
//                     $(this).html('<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>');
//                 }
//             }, 300);

//         })
//     })


    
//     // $(document).ready(function() { 
//     //     let allBtn = document.getElementsByTagName('a');
//     //     for (let n = 0; n < allBtn.length; n++) {
//     //         if (allBtn[n].classList.contains('old-speed-btn') || allBtn[n].classList.contains('monthly-speed-btn')) {

//     //         }
//     //         else if (allBtn[n].classList.contains('btn')){
//     //             allBtn[n].addEventListener('click', () => {
//     //                 allBtn[n].setAttribute('disabled', 'true');
//     //                 allBtn[n].classList.add('no-click');
//     //                 allBtn[n].innerHTML = '<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>'
//     //         })
//     //         }
            
//     //     }
//     // })


// Loader text 

function loaderest() {
    var typed = new Typed('.auto-type', {   
    strings: ['500 milliseconds of extra loading results in a traffic drop of 20% - Google', 'Every extra 100 milliseconds of loading decreases sales by 1%  - Amazon', 'With a 0.1s improvement in site speed, we observed that retail consumers spent almost 10% more - Deloitte', 'President Obama 2012 fundraising success strategy was based on his site loading instantly - Obama', '82% of B2B pages load in 5 seconds or less - Portent', 'An ecommerce site that loads within a second converts 2.5x than a site that loads in 5 seconds - portent', 'Facebook’s prefetching reduces the load time of a website by up to 25%. -  blogging wizard'],
    typeSpeed: 20,
    backSpeed: 20,
    backDelay: 3000,
    loop: true,
  });
}