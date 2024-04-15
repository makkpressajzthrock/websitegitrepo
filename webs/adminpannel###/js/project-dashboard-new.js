function extractDomain(url) {
    try {
        const domain = new URL(url).hostname;
        return domain.startsWith('www.') ? domain.replace('www.', '') : domain;
    } catch (error) {
        console.error('Invalid URL:', error.message);
        return null;
    }
}


function compareAndStoreSubdomain(previousUrl, newUrl) {
    var previousDomain = extractDomain(previousUrl);
    var newDomain = extractDomain(newUrl);

    console.log("newDomain :" + newDomain)
    console.log("previousDomain :" + previousDomain)


    if (previousDomain && newDomain && (previousDomain == newDomain)) {
        return previousDomain;
    } else {
        return false;
    }
}

function validatePageURL(url) {
    // Regular expression for a valid URL with protocol, domain, and path
    // Requires at least one character after the last slash in the path
    var urlRegex = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})(\/[^\/\s]+)$/;

    // Test the input URL against the regex
    return urlRegex.test(url);
}


$(document).ready(function () {


    // $.ajax({
    //     url: "inc/test-ajax.php",
    //     type: "POST",
    //     data: {
    //         action: "add-additional-url",
    //     },
    //     dataType: "JSON",
    //     success: function (response) {
    //         console.log(response);
    //     },
    //     error: function (xhr, status, error) {
    //         console.error(xhr.responseText);
    //         $(".loader").show().html("");
    //     }
    // });

    // get main domain speed
    if ($(".reanalyze-btn-new").length > 0) {
        $(".reanalyze-btn-new").click();
    }

    $(".additionalUrlForm").on("submit", function (e) {

        e.preventDefault();
        var is_valid = true;

        var managerId = $("#user_id").val() ;
        var boostId = $("#project_id").val();
        var websiteName = $("#website_name").val() ;
        var websiteUrl = $("#website_url").val();

        if ($(this).attr("id") == "addedNewUrl2") {
            var url_priority = 2;
            removeHashAndQueryParams('#newUrl2');
            var url = $('#newUrl2').val();
        } else {
            var url_priority = 3;
            removeHashAndQueryParams('#newUrl3');
            var url = $('#newUrl3').val();
        }

        $('.errroUrl' + url_priority).html('');
        $('.subDomainUrl' + url_priority + 'Error').html('');
        $('#sameEnterUrl' + url_priority).html('');

        if (url == '') {
            $('.errroUrl' + url_priority).html('Please enter valid url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
            is_valid = false;
        } else {

            var check_url = compareAndStoreSubdomain(websiteUrl, url);

            if (check_url) {

                var checkDomainVerification = checkDomainVerification1(websiteUrl, url);

                if (!checkDomainVerification) {

                    is_valid = true;

                    // if ( !validatePageURL(url2)) {
                    //    $('.subDomainUrl2Error').html('Please enter domain page urls').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                    //    is_valid = false
                    // }

                    // loader
                    $(".loader").show().html("<div class='loader_s 123 devdev'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p><span class='auto-type'></span></p></div>");
                    loaderest();


                    var additional_id = 0;
                    $.ajax({
                        url: "inc/dashboard-additional-url-fetch.php",
                        type: "POST",
                        data: {
                            user_id: managerId,
                            website_id: boostId,
                            additional_url: url,
                            website_name: websiteName,
                            website_url: websiteUrl,
                            url_priority: url_priority,
                            action: "add-additional-url",
                        },
                        dataType: "JSON",
                        success: function (response) {

                            if (response.status == "error") {
                                $(".loader").hide().html("");
                                var title = "Error in URL" + url_priority;
                                var content = response.message;
                                swalForDashboardDomain(title, content);
                                $('#sameEnterUrl' + url_priority).html(obj.message).css('color', 'red').delay(3000).fadeOut().css('display', 'block')
                            } else {

                                additional_id = response.message;

                                if (url_priority == 2) {
                                    // url2 new speed
                                    var link2Txt = 'Category page or services page or similar page URL';
                                } else if (url_priority == 3) {
                                    // url3 new speed
                                    var link2Txt = 'Product page or lead generation page or similar page URL';
                                }

                                $('#hideAddedNewUrl' + url_priority + 'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link ' + url_priority + '<span class="float-right">' + link2Txt + '</span></li><li class="list-group-item">URL ' + url_priority + '<span class="float-right"><a style="pointer-events: none; " id="websiteUrl' + url_priority + '" href="' + url + '" >' + url + '</a></span></li><li class="list-group-item">Desktop <span class="float-right">complete Installation</span></li><li class="list-group-item">Mobile <span class="float-right">complete Installation</span></li><li  id="lastUpdated' + url_priority + '" class="list-group-item">Last Updated <span class="float-right">complete Installation</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger"  style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=' + $("#project_id_encode").val() + '" >Complete Installation</a></span></div>');
                                // =======================================================================

                                var api_key = $("meta[name='google_pagespeed_api']").attr("content");

                                if (url.includes("?nospeedy")) {
                                    var request_url = url;
                                } else {
                                    if (url.includes("?")) {
                                        var request_url = url + "&nospeedy";
                                    } else {
                                        var request_url = url + "?nospeedy";
                                    }
                                }

                                // get speed for desktop
                                var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

                                fetch(apiEndpoint).then(response => {
                                        if (!response.ok) {
                                            $(".loader").hide().html("");
                                            throw new Error('Please thoroughly review your domain URL (' + url + ') to ensure it is correct.');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        // Process your data here

                                        if (data.hasOwnProperty("lighthouseResult")) {

                                            var lighthouseResult = data.lighthouseResult;

                                            var requestedUrl = lighthouseResult.requestedUrl;
                                            var finalUrl = lighthouseResult.finalUrl;
                                            var userAgent = lighthouseResult.userAgent;
                                            var fetchTime = lighthouseResult.fetchTime;
                                            var environment = JSON.stringify(lighthouseResult.environment);
                                            var runWarnings = JSON.stringify(lighthouseResult.runWarnings);
                                            var configSettings = JSON.stringify(lighthouseResult.configSettings);
                                            var audits = JSON.stringify(lighthouseResult.audits);
                                            var categories = JSON.stringify(lighthouseResult.categories);
                                            var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups);
                                            var i18n = JSON.stringify(lighthouseResult.i18n);

                                            var desktop = lighthouseResult.categories.performance.score;
                                            desktop = Math.round(desktop * 100);

                                            if (desktop > 0) {

                                                var additional_desktop = desktop + "/100";

                                                $.ajax({
                                                    // url: "inc/check-speed-fetch.php",
                                                    url: "inc/dashboard-additional-url-fetch.php",
                                                    type: "post",
                                                    data: {
                                                        manager_id: managerId,
                                                        additional_url: url,
                                                        website_id: boostId,
                                                        additional_id: additional_id,
                                                        // lighthouseResult:lighthouseResult,
                                                        requestedUrl: requestedUrl,
                                                        finalUrl: finalUrl,
                                                        userAgent: userAgent,
                                                        fetchTime: fetchTime,
                                                        environment: environment,
                                                        runWarnings: runWarnings,
                                                        configSettings: configSettings,
                                                        audits: audits,
                                                        categories: categories,
                                                        categoryGroups: categoryGroups,
                                                        i18n: i18n,
                                                        request_url: request_url,
                                                        action: "check-speed-fetch",
                                                    },
                                                    dataType: "JSON",
                                                    beforeSend: function () {},
                                                    success: function (obj) {

                                                        if (obj.status == 'done') {

                                                            var additional_desktop = obj.message.desktop;

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

                                                                    if (data.hasOwnProperty("lighthouseResult")) {

                                                                        var lighthouseResult = data.lighthouseResult;

                                                                        var requestedUrl = lighthouseResult.requestedUrl;
                                                                        var finalUrl = lighthouseResult.finalUrl;
                                                                        var userAgent = lighthouseResult.userAgent;
                                                                        var fetchTime = lighthouseResult.fetchTime;
                                                                        var environment = JSON.stringify(lighthouseResult.environment);
                                                                        var runWarnings = JSON.stringify(lighthouseResult.runWarnings);
                                                                        var configSettings = JSON.stringify(lighthouseResult.configSettings);
                                                                        var audits = JSON.stringify(lighthouseResult.audits);
                                                                        var categories = JSON.stringify(lighthouseResult.categories);
                                                                        var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups);
                                                                        var i18n = JSON.stringify(lighthouseResult.i18n);

                                                                        var mobile = lighthouseResult.categories.performance.score;
                                                                        mobile = Math.round(mobile * 100);
                                                                        var additional_mobile = mobile + "/100";

                                                                        $.ajax({
                                                                            url: "inc/dashboard-additional-url-fetch.php",
                                                                            method: "POST",
                                                                            data: {
                                                                                manager_id: managerId,
                                                                                additional_url: url,
                                                                                website_id: boostId,
                                                                                additional_id: additional_id,
                                                                                // lighthouseResult:lighthouseResult,
                                                                                requestedUrl: requestedUrl,
                                                                                finalUrl: finalUrl,
                                                                                userAgent: userAgent,
                                                                                fetchTime: fetchTime,
                                                                                environment: environment,
                                                                                runWarnings: runWarnings,
                                                                                configSettings: configSettings,
                                                                                audits: audits,
                                                                                categories: categories,
                                                                                categoryGroups: categoryGroups,
                                                                                i18n: i18n,
                                                                                request_url: request_url,
                                                                                action: "check-speed-mobile-fetch",
                                                                            },
                                                                            dataType: "JSON",
                                                                            timeout: 0,
                                                                            success: function (obj) {

                                                                                if (obj.status == "done") {

                                                                                    additional_mobile = obj.message.mobile;

                                                                                    // manage_additional_nospeedy_speed(boostId,obj.id,table_id);
                                                                                    // manage_speed($(".reanalyze-btn-new").attr("data-website_id"), $(".reanalyze-btn-new").attr("data-speedtype"),link1_desktop,link1_mobile);

                                                                                    $.ajax({
                                                                                            type: "POST",
                                                                                            url: "inc/dashboard-additional-url-fetch.php",
                                                                                            data: {
                                                                                                manager_id: managerId,
                                                                                                additional_url: url,
                                                                                                website_id: boostId,
                                                                                                additional_id: additional_id,
                                                                                                url_priority: url_priority,
                                                                                                action: "manage-speed-fetch",
                                                                                            },
                                                                                            dataType: "JSON",
                                                                                            encode: true,
                                                                                        })
                                                                                        .done(function (data) {

                                                                                            // Create a new Date object
                                                                                            const currentDate = new Date();

                                                                                            // Define options for formatting with 24-hour time
                                                                                            const options = {
                                                                                                year: 'numeric',
                                                                                                month: 'long',
                                                                                                day: 'numeric',
                                                                                                hour: 'numeric',
                                                                                                minute: 'numeric',
                                                                                                hour12: false
                                                                                            };

                                                                                            // Format the date using toLocaleString
                                                                                            const formattedDate = currentDate.toLocaleString('en-US', options);

                                                                                            $('#hideAddedNewUrl' + url_priority + 'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link ' + url_priority + '<span class="float-right">' + link2Txt + '</span></li><li class="list-group-item">URL ' + url_priority + '<span class="float-right"><a style="pointer-events: none; " id="websiteUrl' + url_priority + '" href="' + url + '" >' + url + '</a></span></li><li class="list-group-item">Desktop <span class="float-right">' + additional_desktop + '</span></li><li class="list-group-item">Mobile <span class="float-right">' + additional_mobile + '</span></li><li  id="lastUpdated' + url_priority + '" class="list-group-item">Last Updated <span class="float-right">' + formattedDate + '</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger" style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=' + $("#project_id_encode").val() + '" >Complete Installation</a></span></div>');

                                                                                            $.ajax({
                                                                                                type: "POST",
                                                                                                url: "update_meta.php",
                                                                                                data: {
                                                                                                    user_id: managerId,
                                                                                                    action: 'meta_value_update',
                                                                                                },
                                                                                                dataType: "JSON",
                                                                                                encode: true,
                                                                                            });

                                                                                        })
                                                                                        .fail(function (jqXHR, textStatus) {
                                                                                            console.error(jqXHR);
                                                                                            console.error(textStatus);
                                                                                        })
                                                                                        .always(function () {
                                                                                            $(".loader").hide().html("");
                                                                                        });

                                                                                } else {

                                                                                    $(".loader").hide().html("");
                                                                                    swalForDashboardDomain("Unable to Retrieve Speed for Domain: " + url + "!", obj.message);

                                                                                }

                                                                            },
                                                                            error: function (xhr) { // if error occured
                                                                                $(".loader").hide().html("");
                                                                                script_loading++;
                                                                                console.error(xhr.statusText + xhr.responseText);
                                                                                // setTimeout(function(){ $(".loader").hide().html('') ; },1000);
                                                                            },
                                                                            complete: function () {}

                                                                        });

                                                                    } else {

                                                                        $(".loader").hide().html("");

                                                                        var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                                                        swalForDashboardDomain("Unable to Retrieve Speed for Domain: " + url + "!", error);

                                                                        removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);

                                                                    }
                                                                })
                                                                .catch(error => {

                                                                    $(".loader").hide().html("");

                                                                    var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                                                    swalForDashboardDomain("Unable to Retrieve Speed for Domain: " + url + "!", error);

                                                                    console.error('Fetch error:', error);
                                                                    removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);

                                                                });

                                                        } else {

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
                                                            });

                                                        }

                                                    },
                                                    error: function (xhr, status, error) {
                                                        console.error(xhr.responseText);
                                                    },
                                                    complete: function () {}
                                                });

                                            } else {
                                                $(".loader").hide().html("");
                                                var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                                swalForDashboardDomain("Unable to Retrieve Speed for Page URL: " + url + "!", error);
                                                removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);
                                            }

                                        } else {
                                            $(".loader").hide().html("");
                                            var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                            swalForDashboardDomain("Unable to Retrieve Speed for Page URL: " + url + "!", error);
                                            removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);
                                        }
                                    })
                                    .catch(error => {
                                        $(".loader").hide().html("");
                                        console.error('Fetch error:', error);
                                        removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);
                                        swalForDashboardDomain("Unable to Retrieve Speed for Page URL: " + url + "!", error);
                                    });


                            }


                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            $(".loader").show().html("");
                        }
                    });

                } else {
                    var expurl2 = websiteUrl + 'abc';
                    $('.subDomainUrl' + url_priority + 'Error').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                    is_valid = false;
                }

            } else {
                var expurl2 = websiteUrl + 'abc';
                $('.subDomainUrl' + url_priority + 'Error').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                is_valid = false;
            }

        }

    });

});

function reassignSubmitFuntionality() {

    $(".additionalUrlForm").on("submit", function (e) {

        e.preventDefault();
        var is_valid = true;

        var managerId = $("#user_id").val();
        var boostId = $("#project_id").val();
        var websiteName = $("#website_name").val();
        var websiteUrl = $("#website_url").val();

        if ($(this).attr("id") == "addedNewUrl2") {
            var url_priority = 2;
            var url = $('#newUrl2').val();
        } else {
            var url_priority = 3;
            var url = $('#newUrl3').val();
        }

        $('.errroUrl' + url_priority).html('');
        $('.subDomainUrl' + url_priority + 'Error').html('');
        $('#sameEnterUrl' + url_priority).html('');

        if (url == '') {
            $('.errroUrl' + url_priority).html('Please enter valid url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
            is_valid = false;
        } else {

            var check_url = compareAndStoreSubdomain(websiteUrl, url);

            if (check_url) {
                is_valid = true;


                // if ( !validatePageURL(url2)) {
                //    $('.subDomainUrl2Error').html('Please enter domain page urls').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                //    is_valid = false
                // }

                // loader
                $(".loader").show().html("<div class='loader_s 123 devdev'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p><span class='auto-type'></span></p></div>");
                loaderest();


                var additional_id = 0;
                $.ajax({
                    url: "inc/dashboard-additional-url-fetch.php",
                    type: "POST",
                    data: {
                        user_id: managerId,
                        website_id: boostId,
                        additional_url: url,
                        website_name: websiteName,
                        website_url: websiteUrl,
                        url_priority: url_priority,
                        action: "add-additional-url",
                    },
                    dataType: "JSON",
                    success: function (response) {

                        if (response.status == "error") {
                            $(".loader").hide().html("");
                            var title = "Error in URL" + url_priority;
                            var content = response.message;
                            swalForDashboardDomain(title, content);
                            $('#sameEnterUrl' + url_priority).html(obj.message).css('color', 'red').delay(3000).fadeOut().css('display', 'block')
                        } else {

                            additional_id = response.message;

                            if (url_priority == 2) {
                                // url2 new speed
                                var link2Txt = 'Category page or services page or similar page URL';
                            } else if (url_priority == 3) {
                                // url3 new speed
                                var link2Txt = 'Product page or lead generation page or similar page URL';
                            }

                            $('#hideAddedNewUrl' + url_priority + 'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link ' + url_priority + '<span class="float-right">' + link2Txt + '</span></li><li class="list-group-item">URL ' + url_priority + '<span class="float-right"><a style="pointer-events: none; " id="websiteUrl' + url_priority + '" href="' + url + '" >' + url + '</a></span></li><li class="list-group-item">Desktop <span class="float-right">complete Installation</span></li><li class="list-group-item">Mobile <span class="float-right">complete Installation</span></li><li  id="lastUpdated' + url_priority + '" class="list-group-item">Last Updated <span class="float-right">complete Installation</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger"  style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=' + $("#project_id_encode").val() + '" >Complete Installation</a></span></div>');
                            // =======================================================================

                            var api_key = $("meta[name='google_pagespeed_api']").attr("content");

                            if (url.includes("?nospeedy")) {
                                var request_url = url;
                            } else {
                                if (url.includes("?")) {
                                    var request_url = url + "&nospeedy";
                                } else {
                                    var request_url = url + "?nospeedy";
                                }
                            }

                            // get speed for desktop
                            var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

                            fetch(apiEndpoint).then(response => {
                                    if (!response.ok) {
                                        $(".loader").hide().html("");
                                        throw new Error('Please thoroughly review your domain URL (' + url + ') to ensure it is correct.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // Process your data here

                                    if (data.hasOwnProperty("lighthouseResult")) {

                                        var lighthouseResult = data.lighthouseResult;

                                        var requestedUrl = lighthouseResult.requestedUrl;
                                        var finalUrl = lighthouseResult.finalUrl;
                                        var userAgent = lighthouseResult.userAgent;
                                        var fetchTime = lighthouseResult.fetchTime;
                                        var environment = JSON.stringify(lighthouseResult.environment);
                                        var runWarnings = JSON.stringify(lighthouseResult.runWarnings);
                                        var configSettings = JSON.stringify(lighthouseResult.configSettings);
                                        var audits = JSON.stringify(lighthouseResult.audits);
                                        var categories = JSON.stringify(lighthouseResult.categories);
                                        var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups);
                                        var i18n = JSON.stringify(lighthouseResult.i18n);

                                        var desktop = lighthouseResult.categories.performance.score;
                                        desktop = Math.round(desktop * 100);

                                        if (desktop > 0) {

                                            var additional_desktop = desktop + "/100";

                                            $.ajax({
                                                // url: "inc/check-speed-fetch.php",
                                                url: "inc/dashboard-additional-url-fetch.php",
                                                type: "post",
                                                data: {
                                                    manager_id: managerId,
                                                    additional_url: url,
                                                    website_id: boostId,
                                                    additional_id: additional_id,
                                                    // lighthouseResult:lighthouseResult,
                                                    requestedUrl: requestedUrl,
                                                    finalUrl: finalUrl,
                                                    userAgent: userAgent,
                                                    fetchTime: fetchTime,
                                                    environment: environment,
                                                    runWarnings: runWarnings,
                                                    configSettings: configSettings,
                                                    audits: audits,
                                                    categories: categories,
                                                    categoryGroups: categoryGroups,
                                                    i18n: i18n,
                                                    request_url: request_url,
                                                    action: "check-speed-fetch",
                                                },
                                                dataType: "JSON",
                                                beforeSend: function () {},
                                                success: function (obj) {

                                                    if (obj.status == 'done') {

                                                        var additional_desktop = obj.message.desktop;

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

                                                                if (data.hasOwnProperty("lighthouseResult")) {

                                                                    var lighthouseResult = data.lighthouseResult;

                                                                    var requestedUrl = lighthouseResult.requestedUrl;
                                                                    var finalUrl = lighthouseResult.finalUrl;
                                                                    var userAgent = lighthouseResult.userAgent;
                                                                    var fetchTime = lighthouseResult.fetchTime;
                                                                    var environment = JSON.stringify(lighthouseResult.environment);
                                                                    var runWarnings = JSON.stringify(lighthouseResult.runWarnings);
                                                                    var configSettings = JSON.stringify(lighthouseResult.configSettings);
                                                                    var audits = JSON.stringify(lighthouseResult.audits);
                                                                    var categories = JSON.stringify(lighthouseResult.categories);
                                                                    var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups);
                                                                    var i18n = JSON.stringify(lighthouseResult.i18n);

                                                                    var mobile = lighthouseResult.categories.performance.score;
                                                                    mobile = Math.round(mobile * 100);
                                                                    var additional_mobile = mobile + "/100";

                                                                    $.ajax({
                                                                        url: "inc/dashboard-additional-url-fetch.php",
                                                                        method: "POST",
                                                                        data: {
                                                                            manager_id: managerId,
                                                                            additional_url: url,
                                                                            website_id: boostId,
                                                                            additional_id: additional_id,
                                                                            // lighthouseResult:lighthouseResult,
                                                                            requestedUrl: requestedUrl,
                                                                            finalUrl: finalUrl,
                                                                            userAgent: userAgent,
                                                                            fetchTime: fetchTime,
                                                                            environment: environment,
                                                                            runWarnings: runWarnings,
                                                                            configSettings: configSettings,
                                                                            audits: audits,
                                                                            categories: categories,
                                                                            categoryGroups: categoryGroups,
                                                                            i18n: i18n,
                                                                            request_url: request_url,
                                                                            action: "check-speed-mobile-fetch",
                                                                        },
                                                                        dataType: "JSON",
                                                                        timeout: 0,
                                                                        success: function (obj) {

                                                                            if (obj.status == "done") {

                                                                                additional_mobile = obj.message.mobile;

                                                                                // manage_additional_nospeedy_speed(boostId,obj.id,table_id);
                                                                                // manage_speed($(".reanalyze-btn-new").attr("data-website_id"), $(".reanalyze-btn-new").attr("data-speedtype"),link1_desktop,link1_mobile);

                                                                                $.ajax({
                                                                                        type: "POST",
                                                                                        url: "inc/dashboard-additional-url-fetch.php",
                                                                                        data: {
                                                                                            manager_id: managerId,
                                                                                            additional_url: url,
                                                                                            website_id: boostId,
                                                                                            additional_id: additional_id,
                                                                                            url_priority: url_priority,
                                                                                            action: "manage-speed-fetch",
                                                                                        },
                                                                                        dataType: "JSON",
                                                                                        encode: true,
                                                                                    })
                                                                                    .done(function (data) {

                                                                                        // Create a new Date object
                                                                                        const currentDate = new Date();

                                                                                        // Define options for formatting with 24-hour time
                                                                                        const options = {
                                                                                            year: 'numeric',
                                                                                            month: 'long',
                                                                                            day: 'numeric',
                                                                                            hour: 'numeric',
                                                                                            minute: 'numeric',
                                                                                            hour12: false
                                                                                        };

                                                                                        // Format the date using toLocaleString
                                                                                        const formattedDate = currentDate.toLocaleString('en-US', options);

                                                                                        $('#hideAddedNewUrl' + url_priority + 'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link ' + url_priority + '<span class="float-right">' + link2Txt + '</span></li><li class="list-group-item">URL ' + url_priority + '<span class="float-right"><a style="pointer-events: none; " id="websiteUrl' + url_priority + '" href="' + url + '" >' + url + '</a></span></li><li class="list-group-item">Desktop <span class="float-right">' + additional_desktop + '</span></li><li class="list-group-item">Mobile <span class="float-right">' + additional_mobile + '</span></li><li  id="lastUpdated' + url_priority + '" class="list-group-item">Last Updated <span class="float-right">' + formattedDate + '</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger" style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=' + $("#project_id_encode").val() + '" >Complete Installation</a></span></div>');

                                                                                        $.ajax({
                                                                                            type: "POST",
                                                                                            url: "update_meta.php",
                                                                                            data: {
                                                                                                user_id: managerId,
                                                                                                action: 'meta_value_update',
                                                                                            },
                                                                                            dataType: "JSON",
                                                                                            encode: true,
                                                                                        });

                                                                                    })
                                                                                    .fail(function (jqXHR, textStatus) {
                                                                                        console.error(jqXHR);
                                                                                        console.error(textStatus);
                                                                                    })
                                                                                    .always(function () {
                                                                                        $(".loader").hide().html("");
                                                                                    });

                                                                            } else {

                                                                                $(".loader").hide().html("");
                                                                                swalForDashboardDomain("Unable to Retrieve Speed for Domain: " + url + "!", obj.message);

                                                                            }

                                                                        },
                                                                        error: function (xhr) { // if error occured
                                                                            $(".loader").hide().html("");
                                                                            script_loading++;
                                                                            console.error(xhr.statusText + xhr.responseText);
                                                                            // setTimeout(function(){ $(".loader").hide().html('') ; },1000);
                                                                        },
                                                                        complete: function () {}

                                                                    });

                                                                } else {

                                                                    $(".loader").hide().html("");

                                                                    var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                                                    swalForDashboardDomain("Unable to Retrieve Speed for Domain: " + url + "!", error);

                                                                    removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);

                                                                }
                                                            })
                                                            .catch(error => {

                                                                $(".loader").hide().html("");

                                                                var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                                                swalForDashboardDomain("Unable to Retrieve Speed for Domain: " + url + "!", error);

                                                                console.error('Fetch error:', error);
                                                                removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);

                                                            });

                                                    } else {

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
                                                        });

                                                    }

                                                },
                                                error: function (xhr, status, error) {
                                                    console.error(xhr.responseText);
                                                },
                                                complete: function () {}
                                            });

                                        } else {
                                            $(".loader").hide().html("");
                                            var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                            swalForDashboardDomain("Unable to Retrieve Speed for Page URL: " + url + "!", error);
                                            removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);
                                        }

                                    } else {
                                        $(".loader").hide().html("");
                                        var error = "Please thoroughly review your domain URL to ensure it is correct.";
                                        swalForDashboardDomain("Unable to Retrieve Speed for Page URL: " + url + "!", error);
                                        removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);
                                    }
                                })
                                .catch(error => {
                                    $(".loader").hide().html("");
                                    console.error('Fetch error:', error);
                                    removeAdditionalUrlRecord(boostId, additional_id, url, url_priority);
                                    swalForDashboardDomain("Unable to Retrieve Speed for Page URL: " + url + "!", error);
                                });


                        }


                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        $(".loader").show().html("");
                    }
                });


            } else {
                var expurl2 = websiteUrl + 'abc';
                $('.subDomainUrl' + url_priority + 'Error').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                is_valid = false;
            }

        }

    });

}

function removeAdditionalUrlRecord(website_id, additional_id, additional_url, url_type = 2) {

    $.ajax({
            type: "POST",
            url: "inc/dashboard-additional-url-fetch.php",
            data: {
                website_id: website_id,
                additional_id: additional_id,
                action: "remove-additional-url",
            },
            dataType: "JSON",
            encode: true,
        })
        .done(function (data) {
            console.log(data);
        })
        .fail(function (jqXHR, textStatus) {
            console.error(jqXHR);
            console.error(textStatus);
        })
        .always(function () {

            if (url_type == 2) {
                $("#hideAddedNewUrl2Form").html('<div class="" style="position: relative; z-index:1"><form id="addedNewUrl2" class="additionalUrlForm" method="post"><div class="web_dts"><ul class="list-group"><li class="list-group-item">Link 2<span class="float-right">Category page or services page or similar page URL</span></li><li class="list-group-item">URL 2<span class="float-right com_inst"><input type="text" name="newUrl2" id="newUrl2" onblur="removeHashAndQueryParams(this);" placeholder="Enter Url 2" value="' + additional_url + '"><span class="btn_con_w"><button type="submit" name="addedNewUrl2Btn" id="addedNewUrl2Btn" class="btn btn-danger">OK</button></span></span></li><li class="list-group-item">Desktop <span class="float-right">Enter Url 2</span></li><li class="list-group-item">Mobile <span class="float-right">Enter Url 2</span></li><li class="list-group-item">Last Updated  <span class="float-right">Enter Url 2</span></li></ul><div class="errors"><span class="errroUrl2"></span><span class="subDomainUrl2Error"></span><span id="sameEnterUrl2"></span></div></div></form></div>');
            } else if (url_type == 3) {
                $("#hideAddedNewUrl3Form").html('<div class="" style="position: relative; z-index:1"><form id="addedNewUrl3" class="additionalUrlForm" method="post"><div class="web_dts"><ul class="list-group"><li class="list-group-item">Link 3<span class="float-right">Product page or lead generation page or similar page URL</span></li><li class="list-group-item">URL 3<span class="float-right com_inst"><input type="text" name="newUrl3" id="newUrl3" onblur="removeHashAndQueryParams(this);" placeholder="Enter Url 3" value="' + additional_url + '"><span class="btn_con_w"><button type="submit" name="addedNewUrl3Btn" id="addedNewUrl3Btn" class="btn btn-danger">OK</button></span></span></li><li class="list-group-item">Desktop <span class="float-right">Enter Url 3</span></li><li class="list-group-item">Mobile <span class="float-right">Enter Url 3</span></li><li class="list-group-item">Last Updated  <span class="float-right">Enter Url 3</span></li></ul><div class="errors"><span class="errroUrl3"></span><span class="subDomainUrl3Error"></span><span id="sameEnterUrl3"></span> </div></div></form></div>');
            }

            reassignSubmitFuntionality();

        });

}