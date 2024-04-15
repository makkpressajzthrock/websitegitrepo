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

$(document).ready(function () {
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
                    $(".loader").show().html("<div class='loader_s'><h2>Getting Current speed score for Google PageSpeed Insights. This process typically takes between 2-3 minutes.</h2><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>-0.1 seconds of loading can result in +8% conversions.<p>     </div>");


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
       $(".reanalyze-btn-new").click(function () {
        console.log("sss");
        reloadSpeed = $(this);
        script_loading = 0;

            var website_url = $(this).attr("data-website_url");
            var speedtype = $(this).attr("data-speedtype");
            var website_id = $(this).attr("data-website_id");
            var table_id = $(this).attr("data-speedtype");
 

            $.ajax({
                url: "inc/check-speed.php",
                method: "POST",
                data: {
                    website_url: website_url,speedtype: speedtype,website_id:website_id,table_id:table_id
                },
                dataType: "JSON",
                timeout: 0,
                beforeSend: function () {
                    $(".loader").show().html("<div class='loader_s'><h2>Getting Current speed score for Google PageSpeed Insights. This process typically takes between 2-3 minutes.</h2><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>-0.1 seconds of loading can result in +8% conversions.<p>     </div>");


                },
                success: function (data) {
                    script_loading++;
                    if (data.status == "done") {


                        var content = data.message;
                        console.log(content);
                                if(content.desktop=="0/100"){
                                     script_loading = 0;
                                     $(".loader").hide().html("Please Wait...");
                                    try_again($(".reanalyze-btn-new").attr("data-website_id"));
                                }
  


                    }
                    else {
                        $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }

                },
                error: function (xhr) {  
                    script_loading++;
                    console.error(xhr.statusText + xhr.responseText);
                },
                complete: function () {
                    if(script_loading==2){
                        $(".loader").hide().html("Please Wait...");
                        
                        manage_speed($(".reanalyze-btn-new").attr("data-website_id"),$(".reanalyze-btn-new").attr("data-speedtype"));
                    }
                }
            });




            $.ajax({
                url: "inc/check-speed-mobile.php",
                method: "POST",
                data: {
                    website_url: website_url,speedtype: speedtype,website_id:website_id
                },
                dataType: "JSON",
                timeout: 0,
                beforeSend: function () {
                    $(".loader").show().html("<div class='loader_s'><h2>Getting Current speed score for Google PageSpeed Insights. This process typically takes between 2-3 minutes.</h2><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>-0.1 seconds of loading can result in +8% conversions.<p>     </div>");


                },
                success: function (data) {
                    script_loading++;
                    if (data.status == "done") {


                        var content = data.message;
                        console.log(content);
                        
                                if(content.desktop=="0/100"){
                                    script_loading = 0;
                                    $(".loader").hide().html("Please Wait...");
                                     try_again($(".reanalyze-btn-new").attr("data-website_id"));
                                }



                    }
                    else {
                        $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }

                },
                error: function (xhr) { // if error occured
                    script_loading++;
                    console.error(xhr.statusText + xhr.responseText);
                },
                complete: function () {
                    if(script_loading==2){
                        $(".loader").hide().html("Please Wait...");

                        manage_speed($(".reanalyze-btn-new").attr("data-website_id"),$(".reanalyze-btn-new").attr("data-speedtype"));
                    }
                    
                }
            });

        });



function manage_speed(id,speedtype){
    $.ajax({
      type: "POST",
      url: "inc/manage_speed.php",
      data: {id:id,speedtype:speedtype},
      dataType: "json",
      encode: true,
    }).done(function (data) {
      window.location.reload();
    }).fail(function (jqXHR, textStatus) {
        window.location.reload();
} ); 
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