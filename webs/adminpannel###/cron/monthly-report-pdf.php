<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../config.php');
//include('meta_details.php');

require_once('../inc/functions.php');
require_once('../smtp/PHPMailerAutoload.php');
$tem_arr = [];


require_once '../dompdf/autoload.inc.php';

// Reference the Dompdf namespace 
use Dompdf\Dompdf;

ob_clean();
function getIndicator($score)
{

    $score = $score * 100;
    $color = $score < 49 ? " indicator-red " : ($score < 89 ? " indicator-orange " : " indicator-green ");
    return $color;
}

function backgroundIndicator($score)
{

    $score = $score * 100;
    $color = $score < 49 ? " background-red " : ($score < 89 ? " background-orange " : " background-green ");
    return $color;
}

function send_report($conn, $name = "Akash Makkpress", $website_link = '1', $download_report = '1', $period = '1', $email)
{

    // $name = "Akash Makkpress";
    // // $subscribed_plan = $plans_link = HOST_URL."plan.php" ;

    // // get email content from database ----------
    // $emailContent = getEmailContent($conn, 'Report email');

    // // set email variable values ----------------
    // $emailVariables = array("name" => $name, "website-link" => $website_link, "download-report" => $download_report, "period" => $period);

    // // replace variable values from message body ------
    // foreach ($emailVariables as $key1 => $value1) {
    //     $emailContent["body"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["body"]);
    // }

    // // get SMTP detail ---------------
    // $smtpDetail = getSMTPDetail($conn);
    // // print_r($emailVariables) ; print_r($emailContent) ; die() ;
    // // ------------------------------------------------------------------------------------

    // // send mail ----------------------------------------------------------------
    // $mail = new PHPMailer();
    // // $mail->SMTPDebug=3;
    // $mail->IsSMTP();
    // $mail->SMTPAuth = true;
    // $mail->SMTPSecure = $smtpDetail["smtp_secure"];
    // $mail->Host = $smtpDetail["host"];
    // $mail->Port = $smtpDetail["port"];
    // $mail->IsHTML(true);
    // $mail->CharSet = 'UTF-8';
    // $mail->Username = $smtpDetail["email"];
    // $mail->Password = $smtpDetail["password"];
    // $mail->SetFrom($smtpDetail["from_email"], $smtpDetail["from_name"]);
    // $mail->addReplyTo($smtpDetail["from_email"], $smtpDetail["from_name"]);
    // $mail->Subject = $emailContent["subject"];
    // $mail->Body = $emailContent["body"];
    // // $mail->AddAttachment($send_file);   
    // $mail->AddAddress($email);
    // $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => false));
    // var_dump($mail->Send());
}

// function pdf_creat($conn,$value="",$manager_id="",$website_url="",$last_id="",$additional_website_url="")
// {
   //-------------------------------------pdf generate------------------------------------------------------



    $pdf_name = "Report" . $value['id'] . $value['manager_id'] . ".pdf";

    $user_id = $value['manager_id'];
    $project_id = $value['id'];


                        $lighthouseResult = $data["lighthouseResult"];
                        $requestedUrl = $lighthouseResult["requestedUrl"];
                        $finalUrl = $lighthouseResult["finalUrl"];
                        $fetchTime = $lighthouseResult["fetchTime"];
                        $environment = $conn->real_escape_string(serialize($lighthouseResult["environment"]));
                        $runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"]));
                        $configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"]));
                        $audits = $conn->real_escape_string(serialize($lighthouseResult["audits"]));
                        $categories = $conn->real_escape_string(serialize($lighthouseResult["categories"]));
                        $categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"]));
                        $i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"]));
    $desktop_categories =$lighthouseResult["categories"];
    $desktop_audits =$lighthouseResult["audits"];

    $ps_performance = round($desktop_categories["performance"]["score"] * 100, 2);
    $ps_accessibility = round($desktop_categories["accessibility"]["score"] * 100, 2);
    $ps_best_practices = round($desktop_categories["best-practices"]["score"] * 100, 2);
    $ps_seo = round($desktop_categories["seo"]["score"] * 100, 2);
    $ps_pwa = round($desktop_categories["pwa"]["score"] * 100, 2);

                    $output = '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Document</title>
                        <style type="text/css">
                        /* ----- The actual thing ----- */

                        /* Variables */

                        :root {
                            --rating-size: 5rem;
                            --bar-size: 0.3rem;
                            --background-color: #e7f2fa;
                            --rating-color-default: #2980b9;
                            --rating-color-background: #c7e1f3;
                            --rating-color-good: #27ae60;
                            --rating-color-meh: #f1c40f;
                            --rating-color-bad: #e74c3c;
                        }

                        /* Rating item */
                        .rating {
                            margin: auto;
                            position: relative;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border-radius: 100%;
                            overflow: hidden;

                            background: var(--rating-color-default);
                            color: var(--rating-color-default);
                            width: var(--rating-size);
                            height: var(--rating-size);

                            /* Basic style for the text */
                            font-size: calc(var(--rating-size) / 3);
                            line-height: 1;
                        }

                        /* Rating circle content */
                        .rating span {
                            position: relative;
                            display: flex;
                            font-weight: bold;
                            z-index: 2;
                        }

                        .rating span small {
                            font-size: 0.5em;
                            font-weight: 900;
                            align-self: center;
                        }

                        /* Bar mask, creates an inner circle with the same color as thee background */
                        .rating::after {
                            content: "";
                            position: absolute;
                            top: var(--bar-size);
                            right: var(--bar-size);
                            bottom: var(--bar-size);
                            left: var(--bar-size);
                            background: var(--background-color);
                            border-radius: inherit;
                            z-index: 1;
                        }

                        /* Bar background */
                        .rating::before {
                            content: "";
                            position: absolute;
                            top: var(--bar-size);
                            right: var(--bar-size);
                            bottom: var(--bar-size);
                            left: var(--bar-size);
                            border-radius: inherit;
                            box-shadow: 0 0 0 1rem var(--rating-color-background);
                            z-index: -1;
                        }

                        /* Classes to give different colors to ratings, based on their score */
                        .rating.good {
                            background: var(--rating-color-good);
                            color: var(--rating-color-good);
                        }

                        .rating.meh {
                            background: var(--rating-color-meh);
                            color: var(--rating-color-meh);
                        }

                        .rating.bad {
                            background: var(--rating-color-bad);
                            color: var(--rating-color-bad);
                        }

                        .indicator-red {
                            color: red !important;
                        }

                        .indicator-orange {
                            color: orange !important;
                        }

                        .indicator-green {
                            color: green !important;
                        }

                        .background-red {
                            background-color: red !important;
                        }

                        .background-orange {
                            background-color: orange !important;
                        }

                        .background-green {
                            background-color: green !important;
                        }

                        .t_img img {
                            width: 100%;
                            height: 100%;
                        }
                        </style>
                        </head>
                        <body style="font-family: sans-serif;">
                                            <div class="lh-scores-container" style="border-bottom:1x solid lightgray;">
                                                <div class="col-md-2" style="width:20%; float:left;">
                                                    <div class="rating"  style="font-size:24px;">
                                                        ' . $ps_performance . '
                                                    </div>
                                                    <h5>PERFORMANCE</h5>
                                                </div>
                                                <div class="col-md-2" style="width:20%; float:left;">
                                                    <div class="rating" style="font-size:24px;">
                                                         ' . $ps_accessibility . '
                                                    </div>
                                                    <h5>ACCESSIBILITY</h5>
                                                </div>
                                                <div class="col-md-2" style="width:25%; float:left;">
                                                    <div class="rating"  style="font-size:24px;">
                                                    ' . $ps_best_practices . '
                                                    </div>
                                                    <h5>BEST PRACTICES</h5>
                                                </div>
                                                <div class="col-md-2" style="width:10%; float:left; margin">
                                                    <div class="rating"  style="font-size:24px;">
                                                        ' . $ps_seo . '
                                                    </div>
                                                    <h5>SEO</h5>
                                                </div>
                                                <div class="col-md-2" style="width25%;">
                                                    <div class="rating"  style="font-size:24px;">
                                                        ' . $ps_pwa . '
                                                    </div>
                                                    <h5>Progressive Web App</h5>
                                                </div>

                                                <div class="col-md-2" style="width:30%; margin:30px auto; text-align:center; border:1px solid lightgray; padding:15px; border-radius:8px;">
                                                        <div class="col-md-4" style="display:inline-block; margin:0 10px;"><i class="fa-solid fa-triangle"></i>&nbsp;0-49</div>
                                                        <div class="col-md-4" style="display:inline-block; margin:0 10px;"><i class="fa-solid fa-square"></i>&nbsp;50-89</div>
                                                        <div class="col-md-4" style="display:inline-block; margin:0 10px;"><i class="fa-solid fa-circle"></i>&nbsp;90-100</div>
                                                </div>
                                            </div>



                                            <div class="text-center" style="width:100%; text-align:center; margin: 30px auto;">
                                                    <div class="rating" style="margin-bottom:15px; font-size:24px;">
                                                        ' . $ps_performance . '
                                                    </div>
                                                    <h5>PERFORMANCE</h5>
                                                </div>
                                                <div>
                                                    <h4>Metrics</h4>
                                                    <table style="width:100%;">
                                                    <tr>
                                                        <td class="yellow" style="width:30%; padding: 6px 12px 6px 0;">First Contentful Paint </td>
                                                        <td style="text-align:right; width:20%;"> <span class="' . $fcp_color . '">' . $desktop_audits["first-contentful-paint"]["displayValue"] . '</span></td>
                                                        <td class="green"  style="width:30%;padding: 6px 12px;">Speed Index </td>
                                                        <td style="text-align:right; width:20%;"><span class="' . $fcp_color . '">' . $desktop_audits["speed-index"]["displayValue"] . '</span></td>
                                                        </td>
                                                    </tr>   
                                                    <tr> 
                                                        <td class="yellow" style="width:30%; padding: 6px 12px 6px 0;">Largest Contentful Paint </td>
                                                        <td style="text-align:right; width:20%;"><span class="' . $fcp_color . '">' . $desktop_audits["largest-contentful-paint"]["displayValue"] .'</span></td>
                                                        <td class="red" style="width:30%; padding: 6px 12px;">Time to Interactive</td>
                                                        <td style="text-align:right; width:20%;"> <span class="' . $fcp_color . '"> ' . $desktop_audits["interactive"]["displayValue"] . '</span></td>
                                                    </tr> 
                                                    <tr>    
                                                        <td class="red" style="width:30%; padding: 6px 12px 6px 0;">Total Blocking Time</td>
                                                        <td style="text-align:right; width:20%;"> <span class="<?=$fcp_color?>"><?=$desktop_audits["total-blocking-time"]["displayValue"]?></span></td>
                                                        <td class="red" style="width:30%; padding: 6px 12px;   ">Cumulative Layout Shift </td>
                                                        <td style="text-align:right; width:20%;"><span class="' . $fcp_color . '">' . $desktop_audits["cumulative-layout-shift"]["displayValue"] . '</span></td>
                                                    </tr>    
                                                    </table>

                                                    <div class="lh-metrics__disclaimer" style="margin-top:30px;">
                                                    
                                                        <p>Values are estimated and may vary. The <a
                                                                href="https://web.dev/performance-scoring/" target="_blank">performance
                                                                score is calculated</a> directly from these metrics.See calculator<a
                                                                href="https://googlechrome.github.io/lighthouse/scorecalc/#FCP=5094&SI=14865&LCP=13135&CLS=0.3&device=undefined&version=7.1.0"
                                                                target="_blank">See calculator.</a>
                                                        <p>
                                                    </div>

                                            </div>
                                                <div class="lh-audit-group__header">
                                                    <p><b>Opportunities --</b>These suggestions can help your page load faster. They
                                                        do not <a href="https://web.dev/performance-scoring/" target="_blank">directly
                                                            affect</a> the Performance score.</p>
                                                </div>
                                                <div class="lh-audit-group__header">

                                                    <div class="audit-group__header_s">
                                                        <p>Opportunity</p>
                                                        <p>Estimated Savings</p>
                                                    </div>';


                        foreach ($desktop_categories["performance"]["auditRefs"]  as $performanceAuditRefs) {

                            if ((in_array($performanceAuditRefs["id"], ["first-contentful-paint", "speed-index", "largest-contentful-paint", "cumulative-layout-shift", "interactive", "total-blocking-time"]))) {
                                continue;
                            } elseif (in_array($performanceAuditRefs["id"], ["server-response-time", "unused-javascript", "offscreen-images", "uses-rel-preload", "render-blocking-resources", "modern-image-formats", "unused-css-rules", "uses-responsive-images", "uses-optimized-images"])) {



                                foreach ($desktop_audits as $da_key => $da_value) {
                                    if ($performanceAuditRefs["id"] == $da_key) {

                                        if ($da_value["score"] == 1) {
                                            continue;
                                        }



                                        $color_indicator = getIndicator($da_value["score"]);
                                        $background_indicator = backgroundIndicator($da_value["score"]);

                                        $progress_bar = 100 - ($da_value["score"] * 100);


                                        $output .= '<div class="accordion red">
                                                        ' . $da_value["title"] . '
                                                        <div class="progress_b"><span class="progress_bar ' . $background_indicator . '"
                                                                style="width: ' . $progress_bar . '%;"></span><span
                                                                class="' . $color_indicator . '">
                                                                ' . round($da_value["numericValue"] / 1000, 2) . ' s
                                                            </span></div>
                                                    </div>
                                                    <div class="panel">


                                                        <p>Consider lazy-loading offscreen and hidden images after all critical resources
                                                            have finished loading to lower time to interactive. <a href="#">Learn more.</a>
                                                        </p>

                                                        <div class="table_ss" style="width:100%;">
                                                            <table>
                                                                <thead>
                                                        <tr>';

                                        $col_count = 0;
                                        foreach ($da_value["details"]["headings"] as $da_table) {

                                            $output .= '<th style="width:200px; height:50px; text-align:left;">
                                                ' . $da_table["label"] . '
                                            </th>';

                                            $col_count++;
                                        }



                                        $output .= '</tr>
                        </thead>
                        <tbody>';
                                        foreach ($da_value["details"]["items"] as $da_table) {
                                            $output .= '<tr> ';
                                            if ($col_count == 4) {
                        ?>
                                            <?php
                                                $output .= '<td class="t_img" style="width:200px;height:20px;display:inline-block;">
                                                                        ' . $da_table["node"]["snippet"] . '
                                                                    </td>
                                                                    <td class="img_link" style="display:inline-block;width:200px;overflow-wrap:break-word;">
                                                                        ' . $da_table["url"] . '
                                                                    </td>
                                                                    <td class="Resource">
                                                                        ' . round($da_table["totalBytes"] / 1000, 2) . ' KiB
                                                                    </td>
                                                                    <td class="Potential">
                                                                        ' . round($da_table["wastedBytes"] / 1000, 2) . ' KiB
                                                                    </td>';
                                            } elseif ($col_count == 2) {
                                            ?>
                                                <?php
                                                $output .= '<td class="img_link">
                                                                        ' . $da_table["url"] . '
                                                                    </td>
                                                                    <td class="Resource">
                                                                        ' . $da_table["responseTime"] . '
                                                                    </td>';
                                                ?>
                                            <?php
                                            } else {
                                            ?>
                                                <?php
                                                $output .= '<td class="img_link">
                                                                        ' . $da_table["url"] . '
                                                                    </td>
                                                                    <td class="Resource">
                                                                        <?=round($da_table["totalBytes"]/1000,2)?> KiB
                                                                    </td>
                                                                    <td class="Potential">
                                                                        ' . round($da_table["wastedBytes"] / 1000, 2) . ' KiB
                                                                    </td>';
                                                ?>
                                        <?php
                                            }

                                            $output .= '</tr> ';
                                        }

                                        ?>
                                        <?php

                                        $output .= '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>';
                                        ?>
                        <?php

                                        break;
                                    }
                                }
                            }
                        }

                        ?> <?php

                        $output .= '<div class="lh-audit-group__header">
                                                        <p><b>Diagnostics --</b>
                                                            More information about the performance of your application. These numbers do not
                                                            <a href="https://web.dev/performance-scoring/" target="_blank">directly
                                                                affect</a> the Performance score.
                                                        </p>
                                                    </div>'; ?>
                        <?php

                        foreach ($desktop_categories["performance"]["auditRefs"]  as $performanceAuditRefs) {

                            if ((in_array($performanceAuditRefs["id"], ["first-contentful-paint", "speed-index", "largest-contentful-paint", "cumulative-layout-shift", "interactive", "total-blocking-time", "server-response-time", "unused-javascript", "offscreen-images", "uses-rel-preload", "render-blocking-resources", "modern-image-formats", "unused-css-rules", "uses-responsive-images", "uses-optimized-images"]))) {
                                continue;
                            } elseif (in_array($performanceAuditRefs["id"], ["unsized-images", "uses-long-cache-ttl", "dom-size", "critical-request-chains", "user-timings", "resource-summary", "largest-contentful-paint-element", "layout-shift-elements", "font-display", "total-byte-weight"])) {

                                // if ($performanceAuditRefs["id"]=="server-response-time") {
                                // print_r($performanceAuditRefs) ;
                                //  break;
                                // }

                                foreach ($desktop_audits as $da_key => $da_value) {
                                    if ($performanceAuditRefs["id"] == $da_key) {

                                        if ($da_value["score"] == 1) {
                                            continue;
                                        }

                                        // details
                                        // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                        $color_indicator = getIndicator($da_value["score"]);
                                        $background_indicator = backgroundIndicator($da_value["score"]);

                                        $progress_bar = 100 - ($da_value["score"] * 100);

                        ?>
                                        <?php

                                        $output .= '<div class="accordion red" style="margin-top:30px;">
                                                        ' . $da_value["title"] . '
                                                        <span class="' . $color_indicator . '" style="float:right;">
                                                            ' . $da_value["displayValue"] . '
                                                        </span>
                                                    </div>
                                                    <div class="panel">

                                                    

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';
                                        $col_count = 0;
                                        $col_array = [];

                                        foreach ($da_value["details"]["headings"] as $da_table) {
                                            $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                            $col_array[$label] = $da_table["key"];
                                        ?>
                                        <?php
                                            $output .= '<th style="text-align:left;">
                                                                            ' . $label . '
                                                                        </th>';

                                            $col_count++;
                                        }


                                        ?>

                        <?php $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                        foreach ($da_value["details"]["items"] as $da_table) {
                                            $output .= '<tr>';

                                            foreach ($col_array as $ca_key => $ca_val) {
                                                $td = $da_table[$ca_val];

                                                if ($ca_val == "cacheLifetimeMs") {
                                                    $td = round($td / 1000, 2) . ' s';
                                                } elseif ($ca_val == "totalBytes ") {
                                                    $td = round($td / 1000, 2) . ' Kb';
                                                } elseif ($ca_val == "node") {
                                                    $td = $td["snippet"];
                                                }

                                                $output .= '<td style="width:250px;">' . $td . '</td>';
                                            }

                                            $output .= '</tr>';
                                        }

                                        $output .= '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>';
                                        break;
                                    }
                                }
                            }
                        }

                        $output .= '</div>
                                            </div>

                                           
                                            <div class="lh-category-header accessibility_s">
                                                <div class="text-center">
                                                    <div class="rating">
                                                        ' . $ps_accessibility . '
                                                    </div>
                                                    <h4>Accessibility</h4>
                                                </div>

                                                <div class="lh-category-header__description">
                                                    These checks highlight opportunities to <a href="https://web.dev/accessibility/"
                                                        target="_blank">improve the accessibility of your
                                                        web app</a>. Only a subset of accessibility issues can be automatically detected so
                                                    manual testing is also encouraged.
                                                </div>

                                                <div class="lh-audit-group__header">
                                                    <p><span>NAMES AND LABELS</span></p>
                                                </div>

                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "a11y-names-labels") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value["details"]) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">
                                                        <div class="audit-group__header_s">
                                                            <p>Low-contrast text is difficult or impossible for many users to read. <a
                                                                    rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
                                                                    more</a></p>
                                                        </div>
                                                        <div class="audit-group__header_s">
                                                            <p>Failing Elements</p>
                                                        </div>
                                                        <div class="accessibility_lst">';


                                    // if (count($da_value["details"]) > 0) {

                                        foreach ($da_value["details"]["items"] as $key => $item) {

                                            $output .= ' <div class="accessibility_lst_item">
                                                                <h5>
                                                                    ' . $item["node"]["nodeLabel"] . '
                                                                </h5>
                                                                <p>
                                                                    ' . $item["node"]["explanation"] . '
                                                                </p>
                                                            </div>';
                                        }
                                    // }


                                    $output .= '</div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>
                                                    

                                                    <div class="lh-audit-group__header">
                                                    <p><span>BEST PRACTICES</span></p>
                                                </div>

                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "a11y-best-practices") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                         ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">
                                                        <div class="audit-group__header_s">
                                                            <p>Low-contrast text is difficult or impossible for many users to read. <a
                                                                    rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
                                                                    more</a></p>
                                                        </div>
                                                        <div class="accessibility_lst">';


                                    // if (count($da_value["details"]) > 0) {

                                        foreach ($da_value["details"]["items"] as $key => $item) {

                                            $output .= '<div class="accessibility_lst_item">
                                                                <h5>
                                                                    ' . $item["node"]["nodeLabel"] . '
                                                                </h5>
                                                                <p>
                                                                    ' . $item["node"]["explanation"] . '
                                                                </p>
                                                            </div>';
                                        }
                                    // }


                                    $output .= '</div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>
                                                    
                                                    <div class="lh-audit-group__header">
                                                    <p><span>NAVIGATION</span></p>
                                                </div>

                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "a11y-navigation") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">
                                                        <div class="audit-group__header_s">
                                                            <p>Low-contrast text is difficult or impossible for many users to read. <a
                                                                    rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
                                                                    more</a></p>
                                                        </div>
                                                        <div class="accessibility_lst">';


                                    // if (count($da_value["details"]) > 0) {

                                        foreach ($da_value["details"]["items"] as $key => $item) {

                                            $output .= '<div class="accessibility_lst_item">
                                                                <h5>
                                                                    ' . $item["node"]["nodeLabel"] . '
                                                                </h5>
                                                                <p>
                                                                    ' . $item["node"]["explanation"] . '
                                                                </p>
                                                            </div>';
                                        }
                                    // }


                                    $output .= ' </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div><div class="lh-audit-group__header">
                                                    <p><span>TABLES AND LISTS</span></p>
                                                </div>

                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "a11y-tables-lists") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                       ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">
                                                        <div class="audit-group__header_s">
                                                            <p>Low-contrast text is difficult or impossible for many users to read. <a
                                                                    rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
                                                                    more</a></p>
                                                        </div>
                                                        <div class="accessibility_lst">';


                                    // if (count($da_value["details"]) > 0) {

                                        foreach ($da_value["details"]["items"] as $key => $item) {

                                            $output .= '<div class="accessibility_lst_item">
                                                                <h5>
                                                                    ' . $item["node"]["nodeLabel"] . '
                                                                </h5>
                                                                <p>
                                                                    ' . $item["node"]["explanation"] . '
                                                                </p>
                                                            </div>';
                                        }
                                    // }

                                    $output .= '</div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div><div class="lh-audit-group__header">
                                                    <div class="accordion yellow Passed_audits">
                                                        Passed audits 
                                                    </div>
                                                    <div class="panel">';

                        foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] != 1) {
                                        continue;
                                    }


                                    $output .= '<div class="accordion yellow">
                                                            ' . htmlspecialchars($da_value["title"]) . '
                                                        </div>
                                                        <div class="panel">
                                                           ' . htmlspecialchars($da_value["description"]) . '
                                                        </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>
                                                    </div>
                                                    </div> 
                                                                        <div class="lh-category-header lh-gauge__percentage">
                                                <div class="text-center">
                                                    <div class="rating">
                                                        ' . $ps_best_practices . '
                                                    </div>
                                                    <h6>Best Practices</h6>
                                                </div>

                                                <div class="lh-audit-group__header">
                                                    <p><span>User Experience</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "best-practices-ux") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion yellow">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];

                                        $output .=    '<th>
                                                                                        ' . $label . '
                                                                                        </th>';

                                        $col_count++;
                                    }


                                    $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        echo '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }



                                    '$output .= </tbody>
                                                            </table>
                                                        </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>    <div class="lh-audit-group__header">
                                                    <p><span>Trust and Safety</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "best-practices-trust-safety") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }



                                    $output .= '<div class="accordion yellow">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">

                                                      

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];
                                        $output .= '
                                                                        <th>
                                                                            ' . $label . '
                                                                        </th>';

                                        $col_count++;
                                    }

                                    // echo "col_count : ".$col_count;

                                    // print_r($col_array) ;
                                    $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        $output .= '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }

                                    $output .= '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>';



                                    break;
                                }
                            }
                        }
                        $output .= ' </div>

                                                <div class="lh-audit-group__header">
                                                    <p><span>General</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';
                        foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "best-practices-general") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion yellow">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">

                                                        

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];

                                        $output .= '<th>
                                                                            ' . $label . '
                                                                        </th>';

                                        $col_count++;
                                    }

                                    // echo "col_count : ".$col_count;

                                    // print_r($col_array) ;
                                    $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        $output .= '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }

                                    $output .= '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>      <div class="lh-audit-group__header">
                                                    <p><span>PASSED AUDITS</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] != 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">
                                                        ' . htmlspecialchars($da_value["description"]) . '
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>
                                            </div>   <div class="lh-category-header lh-gauge__percentage">
                                                <div class="text-center">
                                                    <div class="rating">
                                                        ' . $ps_seo . '
                                                    </div>
                                                    <h6>SEO</h6>
                                                    <p>These checks ensure that your page is following basic search engine optimization
                                                        advice. There are many additional factors Lighthouse does not score here that may
                                                        affect your search ranking, including performance on <a
                                                            href="https://web.dev/learn-core-web-vitals" target="_blank">Core Web
                                                            Vitals</a>. <a rel="noopener" target="_blank"
                                                            href="https://support.google.com/webmasters/answer/35769">Learn more</a></p>
                                                </div>

                                                <div class="lh-audit-group__header">
                                                    <p><span>CONTENT BEST PRACTICES</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';
                        ?>

                        <!-- -------------------------------------------------------------------- -->
                        <!-- SEO -->


                        <?php

                        foreach ($desktop_categories["seo"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "seo-content") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];

                                        $output .= '<th>
                                                                            ' . $label . '
                                                                        </th>';

                                        $col_count++;
                                    }



                                    $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        $output .= '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }



                                    $output .= '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div><div class="lh-audit-group__header">
                                                    <p><span>CRAWLING AND INDEXING</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["seo"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "seo-crawl") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">

                                                        

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];

                                        $output .= '<th>
                                                                            ' . $label . '
                                                                        </th>';

                                        $col_count++;
                                    }


                                    $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        $output .= '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }


                                    $output .= '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= '</div>


                                                <div class="lh-audit-group__header">
                                                    <p><span>PASSED AUDITS</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["seo"]["auditRefs"]  as $performanceAuditRefs) {

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] != 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";
                                    $output .= '
                                                    <div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">
                                                        ' . htmlspecialchars($da_value["description"]) . '
                                                    </div>';

                                    break;
                                }
                            }
                        }

                        $output .= '</div>
                                            </div><div class="lh-category-header lh-gauge__percentage">
                                                <div class="text-center">
                                                    <div class="rating">
                                                        ' . $ps_pwa . '
                                                    </div>
                                                    <h6>Progressive Web App</h6>
                                                    <p>These checks validate the aspects of a Progressive Web App.<a rel="noopener"
                                                            target="_blank"
                                                            href="https://developers.google.com/web/progressive-web-apps/checklist">Learn
                                                            more</a></p>
                                                </div>

                                                <div class="lh-audit-group__header">
                                                    <p><span>Installable</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';






                        foreach ($desktop_categories["pwa"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "pwa-installable") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    // details
                                    // echo"<pre>";print_r($da_value) ;echo"</pre>";

                                    $output .= '<div class="accordion green">
                                                        ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">';



                                    $output .= '<div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];
                                        $output .= '
                                                                        <th>
                                                                            ' . $label . '
                                                                        </th>';

                                        $col_count++;
                                    }

                                    // echo "col_count : ".$col_count;

                                    // print_r($col_array) ;
                                    $output .= '     
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        $output .= '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }


                                    $output .= '
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= ' </div><div class="lh-audit-group__header">
                                                    <p><span>PWA Optimized</span></p>
                                                </div>
                                                <div class="lh-audit-group__header">';


                        foreach ($desktop_categories["pwa"]["auditRefs"]  as $performanceAuditRefs) {

                            if ($performanceAuditRefs["group"] != "pwa-optimized") {
                                continue;
                            }

                            foreach ($desktop_audits as $da_key => $da_value) {
                                if ($performanceAuditRefs["id"] == $da_key) {

                                    if ($da_value["score"] == 1) {
                                        continue;
                                    }

                                    $output .= '
                                                    <div class="accordion green">
                                                       ' . htmlspecialchars($da_value["title"]) . '
                                                    </div>
                                                    <div class="panel">

                                                       

                                                        <div class="table_ss">
                                                            <table style="margin-top:30px;">
                                                                <thead>
                                                                    <tr>';

                                    $col_count = 0;
                                    $col_array = [];

                                    foreach ($da_value["details"]["headings"] as $da_table) {
                                        $label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"];

                                        $col_array[$label] = $da_table["key"];

                                        $output .= '<th>
                                                                            <?=$label?>
                                                                        </th>';

                                        $col_count++;
                                    }


                                    $output .= '</tr>
                                                                </thead>
                                                                <tbody>';

                                    foreach ($da_value["details"]["items"] as $da_table) {
                                        $output .= '<tr>';

                                        foreach ($col_array as $ca_key => $ca_val) {
                                            $td = $da_table[$ca_val];

                                            if ($ca_val == "cacheLifetimeMs") {
                                                $td = round($td / 1000, 2) . ' s';
                                            } elseif ($ca_val == "totalBytes ") {
                                                $td = round($td / 1000, 2) . ' Kb';
                                            } elseif ($ca_val == "node") {
                                                $td = $td["snippet"];
                                            }

                                            $output .= '<td>' . $td . '</td>';
                                        }

                                        $output .= '</tr>';
                                    }

                                    $output .= '

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>';

                                    break;
                                }
                            }
                        }
                        $output .= ' </div>

                                                <div> <strong>Additional items to manually check -</strong>
                                                    ' . htmlspecialchars($desktop_categories["pwa"]["manualDescription"]) . '
                                                </div>


                                            </div>';





                        $output .= '</body>
                        </html>
                        // ';

// }
    // echo $output;






//     // Instantiate and use the dompdf class 
//     $dompdfi = new Dompdf();
//     $dompdfi->loadHtml($output);
//     $dompdfi->setPaper('A4', 'portrait');
//     $dompdfi->render();
//     $files = $dompdfi->output();
//     file_put_contents('generate-page-report/' . $pdf_name, $files);
//     // $dompdfi->stream($pdf_name, array("Attachment" => 0)); 

//     //----------------------------------------pdf generate end---------------------------------------


//                         // mobile details
//                         $mobile_data = google_page_speed_insight($website_url, "mobile");

//                         if (is_array($mobile_data)) {
//                             $mobile_lighthouseResult = $mobile_data["lighthouseResult"];

//                             $mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"]));
//                             $mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"]));
//                             $mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"]));
//                             $mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"]));
//                             $mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"]));
//                             $mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"]));
//                             $mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"]));
//                         } else {
//                             $mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null;
//                         }


//                         if ($last_id) {
//                             $sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$last_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";
//                             // echo "sql ".$sql."<br>";   
//                             // die(); 
//                             if ($conn->query($sql) == true) {
//                                 // echo "success";

//                             }
//                         }
//                     }
//                 }

//                 if (!empty($additional_website_url)) {
                    


//                         $additional = mysqli_insert_id($conn);
//                         $data = google_page_speed_insight($additional_website_url, "desktop");

//                         if (is_array($data)) {
//                             $lighthouseResult = $data["lighthouseResult"];
//                             $requestedUrl = $lighthouseResult["requestedUrl"];
//                             $finalUrl = $lighthouseResult["finalUrl"];
//                             $userAgent = $lighthouseResult["userAgent"];
//                             $fetchTime = $lighthouseResult["fetchTime"];
//                             $environment = $conn->real_escape_string(serialize($lighthouseResult["environment"]));
//                             $runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"]));
//                             $configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"]));
//                             $audits = $conn->real_escape_string(serialize($lighthouseResult["audits"]));
//                             $categories = $conn->real_escape_string(serialize($lighthouseResult["categories"]));
//                             $categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"]));
//                             $i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"]));


//                             // mobile details
//                             $mobile_data = google_page_speed_insight($additional_website_url, "mobile");

//                             if (is_array($mobile_data)) {
//                                 $mobile_lighthouseResult = $mobile_data["lighthouseResult"];

//                                 $mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"]));
//                                 $mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"]));
//                                 $mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"]));
//                                 $mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"]));
//                                 $mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"]));
//                                 $mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"]));
//                                 $mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"]));
//                             } else {
//                                 $mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null;
//                             }


//                             if ($additional) {
//                                 $sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$additional' , '$last_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";
//                                 // echo "sql ".$sql."<br>";    
//                                 if ($conn->query($sql) == true) {
//                                     // echo "success";

//                                 }
//                             }
                        
//                     }
//                 }

 

// }


// $speed_reports_row = getTableData($conn, " boost_website ", " plan_id != 0 ", "", 1);

// $mail_send_date = date("Y-m-1 h:i");
// // $newDate = date("Y-m-d h:i", strtotime($mail_send_date."+7 Days"));
// // echo $newDate."<br>";
// $email = "ajay.makkpress@gmail.com";



// echo "<pre>";;
// // die();
// // print_r($speed_reports_row);

// // print_r($speed_reports_row);die();
// foreach ($speed_reports_row as  $value) {
//     // print_r($value);

//     $website_name = parse_url($value['website_url'])['host'];
//     $desktop_speed_old = $value['desktop_speed_old'];
//     $desktop_speed_new = $value['desktop_speed_new'];
//     $mobile_speed_old = $value['mobile_speed_old'];
//     $mobile_speed_new = $value['mobile_speed_new'];
//     $admin_users_details = getTableData($conn, " admin_users ", " id ='" . $value['manager_id'] . "' ");
//     if (count($admin_users_details) > 0) {
//         $name = $admin_users_details['firstname'] . " " . $admin_users_details['lastname'];
//         $email = $admin_users_details['email'];
//     }
   

//     $all_plan_row = getTableData($conn, " user_subscriptions ", "  id ='" . $value['subscription_id'] . "' AND `status` LIKE 'succeeded' ");
//     if (count($all_plan_row) > 0) {
//         // code...


//         $plan_end = $all_plan_row['plan_period_end'];
//         $plan_end = strtotime($plan_end);
//     }
//     // echo "plan_end".$plan_end;
//                 // print_r($value);





//     //---------------------------------------------monthly--------------
//     if ($value['period'] == "monthly ") {


//         // print_r($value);
//         // echo "monthly :" .$value['manager_id']."<br>";

//         $mail_send_date = date("Y-m-01 h:i");
//         $newDate = date("Y-m-d h:i", strtotime($mail_send_date . "+1 Month"));
//         // echo $newDate;
//         $current_date = date("Y-m-d h:i");
//         $current_date = strtotime($current_date);

//         if (count($all_plan_row) > 0) {
//             if ($plan_end > $current_date) {
//                 $newDate = strtotime($newDate);

//                 if ($newDate == $current_date) {
//                     send_report($conn, $name = "Akash Makkpress", $value['website_url'], HOST_URL . "adminpannel/cron/generate-page-report/" . $pdf_name, $value['website_url'], "ajay.makkpress@gmail.com" );
//                     insertTableData($conn, "report_send_status", "manager_id,period,manager_site_url,status,website_id", "'" . $value['manager_id'] . "','monthly','1','1', '" . $value['id'] . "'");
//                     echo "monthly";
//                 }
//             }
//         }
//     }
//     //---------------------------------------------Weekly--------------
//     if ($value['period'] == "Weekly ") {
//         echo "Weekly :" .$value['manager_id']."<br>";
//         // print_r($value);




//         $current_date = date("Y-m-d h:i");
//         $current_date = strtotime($current_date);
//         if (count($all_plan_row) > 0) {
//             $current_date_weekly = date("Y-m-d");
//             $current_date_weekly = strtotime($current_date_weekly);
//             if ($plan_end > $current_date) {

//                 $date = new DateTime('first Monday of this month');
//                 $thisMonth = $date->format('m');
//                 while ($date->format('m') === $thisMonth) {
//                     // echo $date->format('Y-m-d h:i'), "\n";
//                     $weekly = $date->format('Y-m-d');
//                     // echo $weekly."<br>";
//                     $weekly = strtotime($weekly);

//                     if ($weekly == $current_date_weekly) {

//                         send_report($conn, $name = "Akash Makkpress", $value['website_url'], HOST_URL . "adminpannel/cron/generate-page-report/" . $pdf_name, $value['website_url'], "ajay.makkpress@gmail.com" );
//                         insertTableData($conn, "report_send_status", "manager_id,period,manager_site_url,status,website_id", "'" . $value['manager_id'] . "','Weekly','1','1', '" . $value['id'] . "'");
//                         echo "Weekly <br> ";
//                     }
//                     $date->modify('next Monday');
//                 }
//             }
//         }
//     }
//     //---------------------------------------------Daily--------------

    
//     if ($value['period'] == "Daily ") {

//         echo "Daily :".$value['manager_id']."<br>";
//                 print_r($value);





//         $current_date = date("Y-m-d h:i");
//         $current_date = strtotime($current_date);
//         if (count($all_plan_row) > 0) {

//             if ($plan_end > $current_date) {

//                 send_report($conn, $name = "Akash Makkpress", $value['website_url'], HOST_URL . "adminpannel/cron/generate-page-report/" . $pdf_name, $value['website_url'], "ajay.makkpress@gmail.com" );
//                 insertTableData($conn, "report_send_status", "manager_id,period,manager_site_url,status,website_id", "'" . $value['manager_id'] . "','Daily','1','1', '" . $value['id'] . "'");

//                 echo "Daily <br>";
//             }
//         }
//     }
//     //-------------------------------------------additional_websites_row----------------

//     // echo "value['manager_id'] ".$value['manager_id']."<br>";



//     $additional_websites_row = getTableData($conn, " additional_websites ", " manager_id ='" . $value['manager_id'] . "'  ", "", 1);
//     // print_r($additional_websites_row);die();
//     // print_r($additional_websites_row);
//     if (count($additional_websites_row) > 0) {

//         foreach ($additional_websites_row as $key =>  $additional_websites) {
//             // print_r($additional_websites);
//             if (in_array($key, $tem_arr)) {

//                 continue;
//             } else {
//                 array_push($tem_arr, $key);
//             }
            






//             if (count($additional_websites) > 0) {

//                 // print_r($additional_websites);
//                 // echo "additional_websites ".$additional_websites['manager_id']."<br>";
//                 // echo "additional_websites" . $key . " " . $additional_websites['id'] . "id  " . $value['manager_id'] . "<br>";
//                 if (isset($plan_end)) {
//                     // print_r($additional_websites);

//                     // $plan_end = strtotime($plan_end);

//                     $current_date = date("Y-m-d h:i");
//                     $current_date = strtotime($current_date);
//                     $admin_users_details = getTableData($conn, " admin_users ", " id ='" . $additional_websites['manager_id'] . "' ");
//                     $name = $admin_users_details['firstname'] . " " . $admin_users_details['lastname'];
//                     $email = $admin_users_details['email'];
//                     // echo "current_date" . $current_date . "<br>";
//                     // echo "plan_end" . $plan_end . "<br>";
//                     //--------------------------------------------monthly
//                     if ($additional_websites['period'] == 'monthly') {
//                         $mail_send_date = date("Y-m-01 h:i");
//                         $newDate = date("Y-m-d h:i", strtotime($mail_send_date . "+1 Month"));
//                         // echo $newDate;


//                         if ($plan_end > $current_date) {
//                             $newDate = strtotime($newDate);

//                             if ($newDate == $current_date) {
//                                 send_report($conn, $name = "Akash Makkpress", $additional_websites['website_url'], HOST_URL . "adminpannel/cron/generate-page-report/" . $pdf_name, $additional_websites['website_url'], "ajay.makkpress@gmail.com");

//                                 echo "monthly Additional URl";
//                                 insertTableData($conn, "report_send_status", "manager_id,period,additional_site_url,status,website_id", "'" . $value['manager_id'] . "','monthly','1','1', '" . $value['id'] . "'");
//                             }
//                         }
//                     }
//                     //--------------------------------------------Weekly

//                     if ($additional_websites['period'] == "weekly") {

//                         $current_date_weekly = date("Y-m-d");
//                         $current_date_weekly = strtotime($current_date_weekly);

//                         if ($plan_end > $current_date) {



//                             $date = new DateTime('first Monday of this month');
//                             $thisMonth = $date->format('m');
//                             while ($date->format('m') === $thisMonth) {
//                                 // echo $date->format('Y-m-d h:i'), "\n";
//                                 $weekly = $date->format('Y-m-d');
//                                 // echo $weekly."<br>";
//                                 $weekly = strtotime($weekly);

//                                 if ($weekly == $current_date_weekly) {

//                                     send_report($conn, $name = "Akash Makkpress", $additional_websites['website_url'], HOST_URL . "adminpannel/cron/generate-page-report/" . $pdf_name, $additional_websites['website_url'], "ajay.makkpress@gmail.com");
//                                     insertTableData($conn, "report_send_status", "manager_id,period,additional_site_url,status,website_id", "'" . $value['manager_id'] . "','Weekly','1','1', '" . $value['id'] . "'");

//                                     echo "Weekly Additional URl";
//                                 }
//                                 $date->modify('next Monday');
//                             }
//                         }
//                     }
//                     //--------------------------------------------Daily

//                     if ($additional_websites['period'] == "daily") {
//                                         // print_r($additional_websites);

//                         if ($plan_end > $current_date) {

//                             send_report($conn, $name = "Akash Makkpress", $additional_websites['website_url'], HOST_URL . "adminpannel/cron/generate-page-report/" . $pdf_name, $additional_websites['website_url'], "ajay.makkpress@gmail.com");
//                             insertTableData($conn, "report_send_status", "manager_id,period,additional_site_url,status,website_id", "'" . $value['manager_id'] . "','Daily','1','1', '" . $value['id'] . "'");

//                             echo "Daily Additional URl";
//                         }
//                     }
//                 }
//             }
//         }
//     }
// }
// // print_r($tem_arr);
