
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="TaxCash Pay it Forward" />
    <base href="./">
    <link rel="shortcut icon" href="Scripts/AdminImg/fav2.jpg" />

    <title>TaxCash Admin Portal</title>
    
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="dist/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/vendors/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="dist/vendors/jquery-ui/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="dist/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="dist/vendors/flags-icon/css/flag-icon.min.css">
    <link rel="stylesheet" href="dist/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="dist/vendors/morris/morris.css">
    <link rel="stylesheet" href="dist/vendors/weather-icons/css/pe-icon-set-weather.min.css">
    <link rel="stylesheet" href="dist/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="dist/vendors/starrr/starrr.css">
    <link rel="stylesheet" href="dist/vendors/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="dist/vendors/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css" />
    <link rel="stylesheet" href="dist/css/main.css">
    <link rel="stylesheet" href="dist/css/tc-custom.css" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" crossorigin="anonymous" />

    <style>
        #header-fix .logo-bar {
        width: 250px;
        padding: 4px 5px 4px 5px;
        border-right: 1px solid var(--bordercolor);
        background: var(--logobg);
        transition: all 0.5s;
        }

        .compact-menu .hlogo {
        display: none;
        }

        #example_length {
        width: 300px;
        }

        #settings {
        display: none;
        }

        .sidebar {
        top: 0px;
        padding-top: 0px;
        }

        .container-fluid, .container-lg, .container-md, .container-sm, .container-xl {
        margin-top: -35px !important;
        }

        .sidebar-footer {
        height: 50px;
        position: absolute;
        width: 100%;
        bottom: 0;
        list-style-type: none;
        padding-bottom: 5.5em;
        text-align: center;
        }

        #mobileshow {
        display: none;
        }

        @media screen and (max-width: 500px) {
        #mobileshow {
        display: block;
        }
        }
    </style>
    <script type="text/javascript">!function(T,l,y){var S=T.location,k="script",D="instrumentationKey",C="ingestionendpoint",I="disableExceptionTracking",E="ai.device.",b="toLowerCase",w="crossOrigin",N="POST",e="appInsightsSDK",t=y.name||"appInsights";(y.name||T[e])&&(T[e]=t);var n=T[t]||function(d){var g=!1,f=!1,m={initialize:!0,queue:[],sv:"5",version:2,config:d};function v(e,t){var n={},a="Browser";return n[E+"id"]=a[b](),n[E+"type"]=a,n["ai.operation.name"]=S&&S.pathname||"_unknown_",n["ai.internal.sdkVersion"]="javascript:snippet_"+(m.sv||m.version),{time:function(){var e=new Date;function t(e){var t=""+e;return 1===t.length&&(t="0"+t),t}return e.getUTCFullYear()+"-"+t(1+e.getUTCMonth())+"-"+t(e.getUTCDate())+"T"+t(e.getUTCHours())+":"+t(e.getUTCMinutes())+":"+t(e.getUTCSeconds())+"."+((e.getUTCMilliseconds()/1e3).toFixed(3)+"").slice(2,5)+"Z"}(),iKey:e,name:"Microsoft.ApplicationInsights."+e.replace(/-/g,"")+"."+t,sampleRate:100,tags:n,data:{baseData:{ver:2}}}}var h=d.url||y.src;if(h){function a(e){var t,n,a,i,r,o,s,c,u,p,l;g=!0,m.queue=[],f||(f=!0,t=h,s=function(){var e={},t=d.connectionString;if(t)for(var n=t.split(";"),a=0;a<n.length;a++){var i=n[a].split("=");2===i.length&&(e[i[0][b]()]=i[1])}if(!e[C]){var r=e.endpointsuffix,o=r?e.location:null;e[C]="https://"+(o?o+".":"")+"dc."+(r||"services.visualstudio.com")}return e}(),c=s[D]||d[D]||"",u=s[C],p=u?u+"/v2/track":d.endpointUrl,(l=[]).push((n="SDK LOAD Failure: Failed to load Application Insights SDK script (See stack for details)",a=t,i=p,(o=(r=v(c,"Exception")).data).baseType="ExceptionData",o.baseData.exceptions=[{typeName:"SDKLoadFailed",message:n.replace(/\./g,"-"),hasFullStack:!1,stack:n+"\nSnippet failed to load ["+a+"] -- Telemetry is disabled\nHelp Link: https://go.microsoft.com/fwlink/?linkid=2128109\nHost: "+(S&&S.pathname||"_unknown_")+"\nEndpoint: "+i,parsedStack:[]}],r)),l.push(function(e,t,n,a){var i=v(c,"Message"),r=i.data;r.baseType="MessageData";var o=r.baseData;return o.message='AI (Internal): 99 message:"'+("SDK LOAD Failure: Failed to load Application Insights SDK script (See stack for details) ("+n+")").replace(/\"/g,"")+'"',o.properties={endpoint:a},i}(0,0,t,p)),function(e,t){if(JSON){var n=T.fetch;if(n&&!y.useXhr)n(t,{method:N,body:JSON.stringify(e),mode:"cors"});else if(XMLHttpRequest){var a=new XMLHttpRequest;a.open(N,t),a.setRequestHeader("Content-type","application/json"),a.send(JSON.stringify(e))}}}(l,p))}function i(e,t){f||setTimeout(function(){!t&&m.core||a()},500)}var e=function(){var n=l.createElement(k);n.src=h;var e=y[w];return!e&&""!==e||"undefined"==n[w]||(n[w]=e),n.onload=i,n.onerror=a,n.onreadystatechange=function(e,t){"loaded"!==n.readyState&&"complete"!==n.readyState||i(0,t)},n}();y.ld<0?l.getElementsByTagName("head")[0].appendChild(e):setTimeout(function(){l.getElementsByTagName(k)[0].parentNode.appendChild(e)},y.ld||0)}try{m.cookie=l.cookie}catch(p){}function t(e){for(;e.length;)!function(t){m[t]=function(){var e=arguments;g||m.queue.push(function(){m[t].apply(m,e)})}}(e.pop())}var n="track",r="TrackPage",o="TrackEvent";t([n+"Event",n+"PageView",n+"Exception",n+"Trace",n+"DependencyData",n+"Metric",n+"PageViewPerformance","start"+r,"stop"+r,"start"+o,"stop"+o,"addTelemetryInitializer","setAuthenticatedUserContext","clearAuthenticatedUserContext","flush"]),m.SeverityLevel={Verbose:0,Information:1,Warning:2,Error:3,Critical:4};var s=(d.extensionConfig||{}).ApplicationInsightsAnalytics||{};if(!0!==d[I]&&!0!==s[I]){var c="onerror";t(["_"+c]);var u=T[c];T[c]=function(e,t,n,a,i){var r=u&&u(e,t,n,a,i);return!0!==r&&m["_"+c]({message:e,url:t,lineNumber:n,columnNumber:a,error:i}),r},d.autoExceptionInstrumented=!0}return m}(y.cfg);function a(){y.onInit&&y.onInit(n)}(T[t]=n).queue&&0===n.queue.length?(n.queue.push(a),n.trackPageView({})):a()}(window,document,{
src: "https://js.monitor.azure.com/scripts/b/ai.2.min.js", // The SDK URL Source
crossOrigin: "anonymous", 
cfg: { // Application Insights Configuration
    connectionString: 'InstrumentationKey=fbbd4bca-0f57-4b44-a672-6a291a8b2de8;IngestionEndpoint=https://canadacentral-1.in.applicationinsights.azure.com/;LiveEndpoint=https://canadacentral.livediagnostics.monitor.azure.com/'
}});</script>
</head>

<body id="main-container" class="default gradient">

    <!-- Login Overlay -->
    <div id="loginOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.95); z-index: 99999; display: flex; align-items: center; justify-content: center;">
        <div style="background: rgba(0, 0, 0, 0.8); padding: 40px; border-radius: 10px; border: 2px solid #00ff00; max-width: 400px; width: 90%;">
            <h2 class="text-white text-center mb-4" style="font-family: Montserrat, sans-serif;">TaxCash Admin Portal</h2>
            <form id="loginForm" onsubmit="return false;">
                <div class="mb-3">
                    <label class="text-white" style="font-family: Montserrat, sans-serif;">Username:</label>
                    <input type="text" id="username" class="form-control" style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid #00ff00;" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="text-white" style="font-family: Montserrat, sans-serif;">Password:</label>
                    <input type="password" id="password" class="form-control" style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid #00ff00;" required>
                </div>
                <div id="loginError" class="text-danger mb-3" style="display: none; font-size: 14px;">Invalid username or password</div>
                <button type="submit" class="btn btn-success w-100" style="background: #00ff00; border: none; color: #000; font-weight: bold; padding: 12px;">Login</button>
            </form>
        </div>
    </div>

    <div class="se-pre-con">
        <div class="loader"></div>
    </div>

    <!-- Dashboard Content - Hidden until authenticated -->
    <div id="dashboardContent" style="display: none;">
    <div class="sidebar">
        <div class="site-width">
            
<img src="Scripts/AdminImg/taxcash-liclogo2.jpg" width="100%">
<ul id="side-menu" class="sidebar-menu">
    <ul id="side-menu" class="sidebar-menu">
        <li class="dropdown active">
            <ul>
                <li><a href="/Admin/indexA2"><i class="icon-chart"></i> Dashboard</a></li>
                <li>
                    <a href="/Admin/LoansPreApproveA2/"><i class="icon-star"></i>All Current Loans V2</a>
                </li>
                <li class="dropdown">
                    <a href="/Admin/LoansPreApproveA2/"><i class="icon-star"></i>Loan Verifications </a>
                        <ul class="sub-menu">

                            <li>
                                <a href="/Admin/LoansPreStepApproveA2?stepId=1">
                                    <i class="icon-star"></i>
                                    <span class="title">Loan list to verify</span>
                                </a>
                            </li>

                                <li><a style="color: red" href="/Admin/OperatorsActivities/"><i class="icon-star"></i>Operator Activity</a></li>
                        </ul>
                    <li><a href="/Admin/OffersA2"><i class="icon-energy"></i>Offers</a></li>

                <li class="dropdown">
                    <a href="#"><i class="icon-star"></i>Collections</a>
                    <ul class="sub-menu">
                            <li><a href="/Admin/CollectionFlow"><i class="icon-energy"></i>Collection Flow</a></li>                        
                            <li><a href="/Admin/CollectionsFlowReport/"><i class="icon-energy"></i>Collections Flow Report </a></li>
                            <li><a href="/Admin/CollectionFlowUploadCsv"><i class="icon-energy"></i>Collection Flow Upload</a></li>
                        <li><a href="/Admin/CollectionNotesCurA2"><i class="icon-energy"></i>My Collection Notes A2</a></li>
                        <li><a href="/Admin/CollectionNotesA2"><i class="icon-energy"></i> Collection Notes V2</a></li>
                        <li><a href="/Admin/CollectionSearch"><i class="icon-energy"></i> Search Collection</a></li>
                        <li><a href="/Admin/CollectionNotesBulk/"><i class="icon-energy"></i>Bulk Collection Notes</a></li>
                        <li><a href="/Admin/fraudUsers/"><i class="icon-energy"></i>Fraud Users</a></li>

                        <li><a href="/Admin/BorrowerPadPayments/"><i class="icon-energy"></i>Borrower PADs</a></li>
                            <li><a style="color: red; font-weight: 900" href="/Admin/MonitorCollection/"><i class="icon-energy"></i>Monitor Collections</a></li>

                            <li><a href="/Admin/LearningMaterial/"><i class="icon-energy"></i>Learning Material</a></li>

                            <li><a href="/Admin/RingCentralHistory/"><i class="icon-energy"></i>Ring Central Summary</a></li>
                        <li><a href="/Admin/PadRequests"><i class="icon-energy"></i>Request for Repad</a></li>
                            <li><a href="/Collections/DialerCampaign"><i class="icon-energy"></i>Dialer Campaigns</a></li>

                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="icon-user"></i>Email Inbox</a>
                    <ul class="sub-menu">
                        <li><a href="/Admin/BorrowerIssueNotes"><i class="icon-energy"></i>Borrower Issues - <span class="nav-badge-issue-notes-count"></span></a></li>
                        <li><a href="/Admin/MessageInboxAjaxA2/"><i class="icon-energy"></i> Inbox A2</a></li>
                        <li><a href="/Admin/ComposeMessageA2?id=0"><i class="icon-disc"></i> Compose Message</a></li>
                        <li><a href="/Admin/CampaignEmailUnsub"><i class="icon-energy"></i>Unsubscribe Emails</a></li>
                        <li><a href="/Admin/AllOffers"><i class="icon-energy"></i>All Offers</a></li>
                    </ul>
                </li>
                    <li class="dropdown">
                        <a href="#"><i class="icon-paypal"></i>Banking</a>
                        <ul class="sub-menu">
                            <li><a href="/Admin/AccountInternalTransaction/"><i class="icon-disc"></i> Account Balance Management</a></li>
                            <li><a href="/Admin/APayLoPaymentPage/"><i class="icon-disc"></i> APayLo All Transactions</a></li>
                            <li><a href="/Admin/APayLoPaymentPage?LicenseeId=0"><i class="icon-disc"></i> Root Transactions</a></li>
                            <li><a href="/Admin/VopayBanksA2/"><i class="icon-disc"></i> TaxCash Payments</a></li>
                        </ul>
                    </li>
                    <li class="dropdown active">
                        <a href="#"><i class="icon-user"></i>Licensees</a>
                        <ul class="sub-menu">
                            <li><a href="/Admin/LicenseesA2/"><i class="icon-energy"></i> Licensees</a></li>
                            <li><a style="color: red; font-weight: 900" href="/LicAdmin/dashboard"><i class="icon-energy"></i> Licensee Dashboards</a></li>
                            <li><a href="/Admin/LicenseeBalanceA2"><i class="icon-energy"></i> Licensee Balance A2</a></li>
                            <li><a href="/Admin/LicenseeFundsHeldClosedAccount"><i class="icon-energy"></i> Funds Held Closed Account</a></li>
                            <li><a href="/Admin/LoanAgreementTemplate"><i class="icon-energy"></i>Licensee Agreements</a></li>
                            <li><a href="/LicAdmin/LicenseeMonthlyPayment"><i class="icon-energy"></i>Licensee Payments</a></li>
                            <li class="active pulsate"><a style="color: blue; font-weight: 900" href="/admin/LoanDashboard"><i class="icon-energy"></i>Master Dashboard</a></li>
                            <li class="active pulsate"><a href="/admin/BorrowerPadStatus"><i class="icon-energy"></i>Pad Statistics</a></li>
                        </ul>
                    </li>
                <li class="dropdown">
                    <a href="#"><i class="icon-user"></i>Territories</a>
                    <ul class="sub-menu">
                            <li><a href="/sales/LicensingAll"><i class="icon-target"></i>Admin View</a></li>
                        <li><a href="/sales/Licensing"><i class="icon-target"></i>Sales View</a></li>
                    </ul>
                </li>

                <li><a href="/Admin/AdminBorrowersAjaxA2"><i class="icon-user"></i> Borrowers</a></li>
                
                    <li class="dropdown">
                        <a href="#"><i class="icon-wrench"></i>Development Tools</a>
                        <ul class="sub-menu">
                            <li><a href="/Admin/UserImpersonation/"><i class="icon-energy"></i>User Impersonation</a></li>
                            <li><a href="/DevTools/BulkPadUpdate/"><i class="icon-energy"></i>Bulk Pad Update</a></li>
                            <li><a href="/Admin/RiskCardA2/"><i class="icon-disc"></i>Scorecard RS</a></li>
                            <li><a href="/Admin/DesisionRules/"><i class="icon-energy"></i>Desision Rules RS</a></li> 
                            <li><a href="/Admin/InHouseIdVerify/"><i class="icon-disc"></i>ID Verify</a></li>
                            <li><a href="/DevTools/LoanCalculatorV4"><i class="icon-frame"></i>Loan Calculator V4</a></li>
                            <li><a href="#" id="logoutLink" onclick="window.logout(); return false;"><i class="icon-power"></i>Logout</a></li>
                            <li><a href="/Admin/LicenseeSelectionA2/"><i class="icon-frame"></i>Licensee Select</a></li>
                            <li><a href="/Admin/LicenseeBalance/"><i class="icon-frame"></i> Licensee Balance</a></li>
                            <li><a href="/Home/SeoCampaign/"><i class="icon-frame"></i>Marketing Stats</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#"><i class="icon-settings"></i>Settings</a>
                        <ul class="sub-menu">
                            <li><a href="/Setting/Settings"><i class="icon-energy"></i>Loan Settings</a></li>
                            <li><a href="/Setting/Holidays"><i class="icon-energy"></i>Holiday Settings</a></li>
                            <li><a href="/Setting/LoanProperty"><i class="icon-energy"></i>Loan Property</a></li>
                            <li><a href="/Setting/LoanApprovalSteps/"><i class="icon-energy"></i>Verification Rules</a></li>
                            <li><a href="/Setting/LoanApprovalSubSteps/"><i class="icon-energy"></i>Verification Sub Rules</a></li>
                            <li><a href="/setting/Campaign/"><i class="icon-energy"></i>Campaign</a></li>
                            <li><a href="/Setting/KnowledgeBase"><i class="icon-energy"></i>KnowledgeBase</a></li>
                            <li><a href="/Setting/TextMessageTemplate"><i class="icon-energy"></i>Text Message Template</a></li>
                            <li><a href="/Setting/RingCentralClient"><i class="icon-energy"></i>Ring Central Client</a></li>

                        </ul>
                    </li>
                <li class="dropdown">
                    <a href="#"><i class="icon-settings"></i>Employee data</a>
                    <ul class="sub-menu">
                            <li><a style="color: red; font-weight: 900" href="/Setting/PersonnelSchedule"><i class="icon-energy"></i>Staff Schedule</a></li>
                    </ul>
                </li>
                    <li class="dropdown">
                        <a href="#"><i class="icon-user"></i>Lead Account</a>
                        <ul class="sub-menu">
                                <li><a href="/leadaccount/SummaryReport">Lead Account Report</a></li>
                        </ul>
                    </li>
                <li><a href="admin.php"><i class="icon-power"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</ul>

            <ol class="breadcrumb bg-transparent align-self-center m-5 p-0 ml-auto">
                <li class="breadcrumb-item"><a href="#">Application</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
    <div class="row col-lg-12" id="mobileshow">
        <div class="row">
            <div id="col" style="padding-top:10px; margin-left:30px; margin-bottom:-40px">
                <img src="dist/images/lion-logo2.png">
            </div>
            <div class="col text-right" style="margin-top:20px; margin-right:-20px; font-size:20px;">
                <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
            </div>
        </div>
    </div>

    
<style>
    #iframe::after .site-footer {
        
        display: none;
        
    }
</style>
<div class="container col-10" style="margin-left: 250px;">
    <div class="col-12 col-lg-12">

        <div class="row">

            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12 col-sm-3 mt-3">
                        <div class="card h-100 glow">
                            <div class="card-body">
                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                    <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                    <div class='card-liner-content'>
                                        <h1 class="card-liner-title text-white">Available Balance</h1>
                                        <h1 class="card-liner-subtitle text-white">
                                            <span class="neons" style="font-size: 24px;" id="availableBalanceValue">$43,477</span> <br />
                                            Pending Balance: <span id="pendingBalanceValue">$99,103</span> <br />
                                            Account Balance: <span id="accountBalanceValue">$142,580</span> <br />
                                            Cumulative Balance: <span id="cumulativeBalanceValue">$5,247,154</span> <br />
                                            Investment so far: <span id="roiInvestmentValue">$6,000,000</span> <br />
                                            Income: <span id="roiIncomeValue1">$1,800,000</span> <br />
                                            ROI: <span id="roiPercentageValue">29.57%</span> Income: <span id="roiIncomeValue2">$7,337,006.66</span>
                                        </h1>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 mt-3">
                        <div class="card h-100 glow2">
                            <div class="card-body">
                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                    <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                    <div class='card-liner-content'>
                                        <h1 class="card-liner-title text-white">Funding to Lending Account</h1>
                                        <h1 class="card-liner-subtitle text-white">
                                            <span class="neons" style="font-size: 24px;" id="fundingTotalValue">$8,475,646</span> <br />
                                            E-Transfer Balance: <span id="fundingEtransferValue">$3,909,401</span> <br />
                                            Funding Received: <span id="fundingReceivedValue">$9,769,921</span> <br />
                                            Funding Balance: <span id="fundingBalanceValue">$1,294,275</span>
                                        </h1>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 mt-3">
                        <div class="card h-100 glow3" >
                            <div class="card-body">
                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                    <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                    <div class='card-liner-content'>
                                        <h1 class="card-liner-title text-white">TaxCash ROI Lending</h1>
                                        <h1 class="card-liner-subtitle text-white mb-1">
                                            <span class="neons" style="font-size: 24px;" id="roiLendingPercentageValue">29.57%</span>
                                        </h1>
                                        <h1 class="card-liner-title text-white">TaxCash ROI Sales</h1>
                                        <h1 class="card-liner-subtitle text-white">
                                            <span class="neons" style="font-size: 24px;" id="roiSalesPercentageValue">29.57%</span>
                                        </h1>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 mt-3">
                        <div class="card h-100 glow4 neons">
                            <div class="card-body">
                                <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                    <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                    <div class='card-liner-content'>
                                        <h1 class="card-liner-title text-white">Territories</h1>
                                        <h1 class="card-liner-subtitle text-white">
                                            <span class="neons" style="font-size: 24px;" id="territoriesTotalValue">$8,620,000 / 47</span>  <br />
                                            Sold: <span id="territoriesSoldValue">$3,369,000 / 25</span>  <br />
                                            In progress:  <span id="territoriesProgressValue">$0 / 0</span> <br />
                                            On holding:  <span id="territoriesHoldingValue">$3,115,000 / 10</span>
                                        </h1>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 col-md-12 col-lg-6 mt-3">
            <div class="card glow3">
                <div class="card-header  justify-content-between align-items-center">
                    <h6 class="card-title neons">Loan Graph</h6>
                </div>
                <style>
                    body {
                        background-color: #30303d;
                        color: #fff;
                    }

                    #chartdiv {
                        width: 100%;
                        height: 300px;
                        margin-top: 0px;
                        font-size: 14px !important;
                    }

                    #mapChartDiv {
                        width: 100%;
                    }
                </style>
                <!-- Resources -->
                <script src="https://cdn.amcharts.com/lib/4/core.js" crossorigin="anonymous"></script>
                <script src="https://cdn.amcharts.com/lib/4/charts.js" crossorigin="anonymous"></script>
                <script src="https://cdn.amcharts.com/lib/4/themes/dark.js" crossorigin="anonymous"></script>
                <script src="https://cdn.amcharts.com/lib/4/themes/animated.js" crossorigin="anonymous"></script>

                <!-- Chart code -->
                <script>
                    // Data is now loaded from external JSON files in the data/ folder
                    // This ensures code changes don't affect the data
                    // All data files:
                    // - data/loan-graph.json (loans and gauges)
                    // - data/aging-data.json (aging table data)
                    // - data/dashboard-metrics.json (dashboard card metrics)
                    // - data/licensee-income.json (licensee income data)

                    // Define numberWithCommas here so it's accessible to all functions in this scope
                    function numberWithCommas(x) {
                        x = x.toString();
                        var pattern = /(-?\d+)(\d{3})/;
                        while (pattern.test(x))
                            x = x.replace(pattern, "$1,$2");
                        return x;
                    }

                    function fetchLoanGraphData() {
                        return fetch("data/loan-graph.json?v=" + Date.now() + "&t=" + Math.random())
                            .then(function(response) {
                                if (!response.ok) {
                                    throw new Error("Failed to load loan graph data from data/loan-graph.json");
                                }
                                return response.json();
                            })
                            .then(function(payload) {
                                var loans, gauges;
                                if (payload) {
                                    if (Array.isArray(payload.loans) && payload.loans.length) {
                                        loans = payload.loans;
                                    } else if (Array.isArray(payload) && payload.length) {
                                        loans = payload;
                                    }
                                    if (Array.isArray(payload.gauges) && payload.gauges.length) {
                                        gauges = payload.gauges;
                                    }
                                } else if (Array.isArray(payload) && payload.length) {
                                    loans = payload;
                                }
                                
                                // Require data from file - no fallback defaults
                                if (!loans || !loans.length) {
                                    throw new Error("No loan data found in data/loan-graph.json");
                                }
                                
                                return {
                                    loans: loans,
                                    gauges: gauges || []
                                };
                            })
                            .catch(function(error) {
                                console.error("Error loading loan graph data:", error);
                                // Show error message instead of using defaults
                                var tbody = document.getElementById("loanDataTableBody");
                                if (tbody) {
                                    tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error: Could not load data from data/loan-graph.json. Please ensure the file exists and is valid JSON.</td></tr>';
                                }
                                throw error; // Re-throw to prevent rendering with empty data
                            });
                    }

                    function fetchAgingData() {
                        return fetch("data/aging-data.json?v=" + Date.now() + "&t=" + Math.random())
                            .then(function(response) {
                                if (!response.ok) {
                                    throw new Error("Failed to load aging data from data/aging-data.json");
                                }
                                return response.json();
                            })
                            .then(function(payload) {
                                var agingData;
                                if (payload && Array.isArray(payload.aging) && payload.aging.length) {
                                    agingData = payload.aging;
                                } else if (Array.isArray(payload) && payload.length) {
                                    agingData = payload;
                                }
                                
                                // Require data from file - no fallback defaults
                                if (!agingData || !agingData.length) {
                                    throw new Error("No aging data found in data/aging-data.json");
                                }
                                
                                return agingData;
                            })
                            .catch(function(error) {
                                console.error("Error loading aging data:", error);
                                // Show error message instead of using defaults
                                var tbody = document.getElementById("agingTableBody");
                                if (tbody) {
                                    tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error: Could not load data from data/aging-data.json. Please ensure the file exists and is valid JSON.</td></tr>';
                                }
                                throw error; // Re-throw to prevent rendering with empty data
                            });
                    }

                    function renderLoanGraph(loandata) {
                        // Filter out Total Loans row
                        var chartData = loandata.filter(function(item) {
                            return String(item.id) !== "1" && (item.particularTitle || "").toLowerCase() !== "total loans";
                        });
                        if (!chartData.length) {
                            chartData = loandata.slice();
                        }
                        // Use varietyPercent directly from the data (same as "% of Total (Auto)" column)
                        chartData = chartData.map(function(item) {
                            var count = Number(item.itemCount || 0);
                            var computedPercent = Number(item.varietyPercent || 0);
                            if (isNaN(computedPercent)) {
                                computedPercent = 0;
                            }
                            return Object.assign({}, item, {
                                computedPercent: computedPercent,
                                displayCount: count
                            });
                        });
                        am4core.useTheme(am4themes_dark);
                        am4core.useTheme(am4themes_animated);

                        var chart = am4core.create("chartdiv", am4charts.PieChart);
                        chart.hiddenState.properties.opacity = 0;
                        chart.data = chartData;
                        chart.radius = am4core.percent(70);
                        chart.innerRadius = am4core.percent(40);
                        chart.startAngle = 180;
                        chart.endAngle = 360;

                        var series = chart.series.push(new am4charts.PieSeries());
                        series.dataFields.value = "computedPercent";
                        series.dataFields.category = "particularTitle";
                        series.colors.list = [
                            am4core.color("gold"),
                            am4core.color("darkturquoise"),
                            am4core.color("blueviolet"),
                            am4core.color("dodgerblue")
                        ];
                        series.slices.template.tooltipText = "{particularTitle}: [bold]{displayCount}[/] loans ({computedPercent}%)";
                        series.slices.template.cornerRadius = 10;
                        series.slices.template.innerCornerRadius = 7;
                        series.slices.template.draggable = true;
                        series.slices.template.inert = true;
                        series.alignLabels = false;

                        series.hiddenState.properties.startAngle = 90;
                        series.hiddenState.properties.endAngle = 90;

                        chart.legend = new am4charts.Legend();
                    }

                    function formatCurrencyForLoanTable(value) {
                        var num = Number(value);
                        if (isNaN(num)) {
                            return "$0";
                        }
                        var fixed = Math.round(num * 100) % 100 === 0 ? num.toFixed(0) : num.toFixed(2);
                        return "$" + numberWithCommas(fixed);
                    }

                    function renderLoanTable(loandata) {
                        var tbody = document.getElementById("loanDataTableBody");
                        if (!tbody) {
                            return;
                        }
                        if (!loandata || !loandata.length) {
                            tbody.innerHTML = '<tr><td colspan="9" class="text-center">No data available</td></tr>';
                            return;
                        }

                        var rows = loandata.map(function(item) {
                            var count = Number(item.itemCount || 0);
                            // Use varietyPercent directly from the data (this is the "% of Total (Auto)" column)
                            var percent = Number(item.varietyPercent || 0);
                            if (isNaN(percent)) {
                                percent = 0;
                            }
                            // Display without rounding - show full precision
                            var percentText = percent + "%";
                            return '<tr>' +
                                '<td>' + (item.particularTitle || '') + '</td>' +
                                '<td class="text-warning">' + numberWithCommas(count) + '</td>' +
                                '<td class="text-info">' + formatCurrencyForLoanTable(item.disbursedAmount) + '</td>' +
                                '<td class="text-info">' + formatCurrencyForLoanTable(item.interestAmount) + '</td>' +
                                '<td class="text-info">' + formatCurrencyForLoanTable(item.totalAmount) + '</td>' +
                                '<td class="text-info">' + formatCurrencyForLoanTable(item.collectAmount) + '</td>' +
                                '<td class="text-success">' + percentText + '</td>' +
                                '<td class="text-success">' + formatCurrencyForLoanTable(item.totalTfpPaid) + '</td>' +
                                '<td class="text-success">' + formatCurrencyForLoanTable(item.totalTfpCollected) + '</td>' +
                                '</tr>';
                        }).join("");

                        tbody.innerHTML = rows;
                    }

                    function renderAgingTable(agingData) {
                        var tbody = document.getElementById("agingTableBody");
                        if (!tbody) {
                            return;
                        }
                        if (!agingData || !agingData.length) {
                            tbody.innerHTML = '<tr><td colspan="9" class="text-center">No data available</td></tr>';
                            return;
                        }
                        var rows = agingData.map(function(row) {
                            return '<tr>' +
                                '<td>' + (row.label || "") + '</td>' +
                                '<td class="text-warning">' + formatCurrencyForLoanTable(row.loanDisbursement) + '</td>' +
                                '<td class="text-secondary">' + formatCurrencyForLoanTable(row.interest) + '</td>' +
                                '<td class="text-secondary">' + formatCurrencyForLoanTable(row.partialPayment) + '</td>' +
                                '<td class="text-secondary">' + formatCurrencyForLoanTable(row.totalDebt) + '</td>' +
                                '<td class="text-secondary">' + formatCurrencyForLoanTable(row.totalCollected) + '</td>' +
                                '<td class="text-success">' + formatCurrencyForLoanTable(row.tfpPayment) + '</td>' +
                                '<td class="text-success">' + formatCurrencyForLoanTable(row.tfpCollected) + '</td>' +
                                '<td class="text-success">' + formatCurrencyForLoanTable(row.tfpNet) + '</td>' +
                                '</tr>';
                        }).join("");
                        tbody.innerHTML = rows;
                    }

                    function calculateGaugesFromLoanData(loandata) {
                        if (!loandata || !loandata.length) {
                            return [];
                        }
                        
                        // Find the Total Loans row
                        var totalLoansRow = loandata.find(function(item) {
                            return String(item.id) === "1" || (item.particularTitle || "").toLowerCase() === "total loans";
                        });
                        
                        if (!totalLoansRow) {
                            return [];
                        }
                        
                        var totalAmount = Number(totalLoansRow.totalAmount || 0);
                        var collectAmount = Number(totalLoansRow.collectAmount || 0);
                        var totalTfpPaid = Number(totalLoansRow.totalTfpPaid || 0);
                        var totalTfpCollected = Number(totalLoansRow.totalTfpCollected || 0);
                        
                        // Calculate percentages
                        var totalCollectedPercent = totalAmount > 0 ? (collectAmount / totalAmount) * 100 : 0;
                        var tfpCollectedPercent = totalTfpPaid > 0 ? (totalTfpCollected / totalTfpPaid) * 100 : 0;
                        
                        // Calculate non-TFP collected (total collected minus TFP collected)
                        var nonTfpCollected = collectAmount - totalTfpCollected;
                        var nonTfpCollectedPercent = totalAmount > 0 ? (nonTfpCollected / totalAmount) * 100 : 0;
                        
                        // Only return calculated gauges - other gauges should come from JSON file
                        return [
                            { id: "chartdiv1", title: "Total Collected", value: totalCollectedPercent },
                            { id: "chartdiv2", title: "Collected", value: tfpCollectedPercent },
                            { id: "chartdiv3", title: "Non Collected", value: nonTfpCollectedPercent }
                        ];
                    }

                    am4core.ready(function() {
                        fetchLoanGraphData()
                            .then(function(dataset) {
                                var loans = dataset.loans;
                                var gauges = dataset.gauges;
                                // Calculate gauges from loan data if not provided in JSON
                                if (!gauges || !gauges.length) {
                                    gauges = calculateGaugesFromLoanData(loans);
                                }
                                renderLoanGraph(loans);
                                renderLoanTable(loans);
                                renderGauges(gauges);
                            })
                            .catch(function(error) {
                                // Error already displayed in fetchLoanGraphData
                                console.error("Failed to initialize loan graph:", error);
                            });
                    });

                    fetchAgingData()
                        .then(function(data) {
                            console.log("Aging data loaded successfully:", data);
                            renderAgingTable(data);
                        })
                        .catch(function(error) {
                            console.error("Failed to initialize aging table:", error);
                            // Show error in table
                            var tbody = document.getElementById("agingTableBody");
                            if (tbody) {
                                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error loading aging data. Check console for details.</td></tr>';
                            }
                        });
                </script>

                <!-- HTML -->
                <div id="chartdiv"></div>
            </div>
        </div>

        <div class="col-6 col-md-12 col-lg-6 mt-3">
            <div class="card h-100 glow3">
                <div class="card-header  justify-content-between align-items-center">
                    <h6 class="card-title neons"></h6>
                </div>
                <script>
                    function drawGauge(chartName, value, title) {
                        var chart = am4core.create(chartName, am4charts.GaugeChart);
                        chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect

                        chart.innerRadius = -25;

                        var axis = chart.xAxes.push(new am4charts.ValueAxis());
                        axis.min = 0;
                        axis.max = 100;;
                        axis.strictMinMax = true;
                        axis.renderer.grid.template.stroke = new am4core.InterfaceColorSet().getFor("background");
                        axis.renderer.grid.template.strokeOpacity = 0.3;

                        var colorSet = new am4core.ColorSet();

                        var range0 = axis.axisRanges.create();
                        range0.value = 0;
                        range0.endValue = 50;
                        range0.axisFill.fillOpacity = 1;
                        range0.axisFill.fill = colorSet.getIndex(4);
                        range0.axisFill.zIndex = - 1;

                        var range1 = axis.axisRanges.create();
                        range1.value = 50;
                        range1.endValue = 80;
                        range1.axisFill.fillOpacity = 1;
                        range1.axisFill.fill = colorSet.getIndex(2);
                        range1.axisFill.zIndex = -1;

                        var range2 = axis.axisRanges.create();
                        range2.value = 80;
                        range2.endValue = 100;
                        range2.axisFill.fillOpacity = 1;
                        range2.axisFill.fill = colorSet.getIndex(1);
                        range2.axisFill.zIndex = -1;

                        var legend = new am4charts.Legend();
                        legend.isMeasured = false;
                        legend.y = am4core.percent(100);
                        legend.verticalCenter = "middle";
                        legend.parent = chart.chartContainer;

                        legend.data = [{
                            "name": (value).toFixed(2) + "% \n" + title,
                            "fill": value <= 50 ? colorSet.getIndex(4) : (value <= 80 ? colorSet.getIndex(2) : colorSet.getIndex(1))
                    }];
                        var hand2 = chart.hands.push(new am4charts.ClockHand());
                        chart.setTimeout(randomValue2, 2000);

                        function randomValue2() {
                            hand2.showValue((value), am4core.ease.cubicOut);

                            chart.setTimeout(randomValue2, 2000);
                        }
// using chart.setTimeout method as the timeout will be disposed together with a chart


                    }
                </script>
                <!-- HTML -->
                <div class="row" style="font-size: 10px">
                    <div class="col-4"><div id="chartdiv1"></div></div>
                    <div class="col-4"><div id="chartdiv2"></div></div>
                    <div class="col-4"><div id="chartdiv3"></div></div>
                </div><div class="row" style="font-size: 10px">
                    <div class="col-4"><div id="chartdiv4"></div></div>
                    <div class="col-4"><div id="chartdiv5"></div></div>
                    <div class="col-4"><div id="chartdiv6"></div></div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-sm-3 mt-3">
                    <div class="card h-100 glow5">
                        <div class="card-body">
                            <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                <div class='card-liner-content'>
                                    <h1 class="card-liner-title text-white">Total Loans</h1>
                                    <h1 class="card-liner-subtitle text-white">
                                        <span class="neons pulsate-number" style="font-size: 24px;" id="totalLoansCount">0</span> <br />
                                        Today: <span class="pulsate-number" id="totalLoansToday">0</span> <br />
                                        This Month: <span class="pulsate-number" id="totalLoansMonth">0</span> <br />
                                        Total Value: <span class="pulsate-number" id="totalLoansValue">$0</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3 mt-3">
                    <div class="card h-100 glow6">
                        <div class="card-body">
                            <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                <div class='card-liner-content'>
                                    <h1 class="card-liner-title text-white">Approved Loan Value</h1>
                                    <h1 class="card-liner-subtitle text-white">
                                        <span class="neons pulsate-number" style="font-size: 24px;" id="approvedLoanValue">$0</span> <br />
                                        Total Loans: <span class="pulsate-number" id="totalLoansAmount">$0</span> <br />
                                        Pending: <span class="pulsate-number" id="pendingLoanValue">$0</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3 mt-3">
                    <div class="card h-100 glow7">
                        <div class="card-body">
                            <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                <div class='card-liner-content'>
                                    <h1 class="card-liner-title text-white">Average Loan Value</h1>
                                    <h1 class="card-liner-subtitle text-white">
                                        <span class="neons pulsate-number" style="font-size: 24px;" id="averageLoanValue">$0</span> <br />
                                        Min: <span class="pulsate-number" id="minLoanValue">$0</span> <br />
                                        Max: <span class="pulsate-number" id="maxLoanValue">$0</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3 mt-3">
                    <div class="card h-100 glow8">
                        <div class="card-body">
                            <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                <div class='card-liner-content'>
                                    <h1 class="card-liner-title text-white">Registered Users</h1>
                                    <h1 class="card-liner-subtitle text-white">
                                        <span class="neons pulsate-number" style="font-size: 24px;" id="registeredUsersCount">0</span> <br />
                                        Today: <span class="pulsate-number" id="registeredUsersToday">0</span> <br />
                                        This Month: <span class="pulsate-number" id="registeredUsersMonth">0</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class=" mt-3">
        <div class="card glow3">
            <div class="card-header  justify-content-between align-items-center">
                <h6 class="card-title neons">Loan Data</h6>
            </div>
            <div class="card-body table-responsive p-0">

                <table class="table font-w-600 mb-0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Loan Count</th>
                            <th>Loan Disbursement</th>
                            <th>Interest</th>
                            <th>Total</th>
                            <th>Loan Repayment</th>
                            <th>% of Total Loans</th>
                            <th>Paid</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody id="loanDataTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class=" mt-3">
        <div class="card h-100 glow3" style="font-size: 16px">
            <div class="card-header justify-content-between align-items-left">
                <h6 class="card-title neons">Aging Analysis</h6>
            </div>
            <div class="card-body table-responsive p-0">

                <table class="table font-w-600 mb-0">
                    <thead>
                        <tr>
                            <th>Aging</th>
                            <th>Loan Disbursement</th>
                            <th>Interest</th>
                            <th>Partial Payment </th>
                            <th>Total debt</th>
                            <th>Total Collected</th>
                            <th>Payment</th>
                            <th>Collected</th>
                            <th>Net</th>
                        </tr>
                    </thead>
                    <tbody id="agingTableBody">
                    <tfoot>
                        <tr>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-12">
            <!-- Date Range Filters and Summary Metrics Section - IN THE SAME ROW -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card glow3" style="font-size: 16px; background: rgba(0,0,0,0.3); border: 2px solid #0066ff;">
                        <div class="card-body" style="padding: 20px;">
                            <div class="row mb-3">
                                <!-- Date Range Buttons -->
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <div class="d-flex flex-wrap align-items-center" style="min-height: 50px; gap: 8px;">
                                        <button type="button" class="date-range-btn active" data-range="7" style="background: #808080 !important; border: 1px solid white !important; color: white !important; padding: 10px 25px !important; cursor: pointer !important; display: inline-block !important; font-weight: bold !important; min-width: 80px !important;">7 Days</button>
                                        <button type="button" class="date-range-btn" data-range="30" style="background: #000000 !important; border: 1px solid white !important; color: white !important; padding: 10px 25px !important; cursor: pointer !important; display: inline-block !important; font-weight: bold !important; min-width: 80px !important;">30 Days</button>
                                        <button type="button" class="date-range-btn" data-range="60" style="background: #000000 !important; border: 1px solid white !important; color: white !important; padding: 10px 25px !important; cursor: pointer !important; display: inline-block !important; font-weight: bold !important; min-width: 80px !important;">60 Days</button>
                                        <button type="button" class="date-range-btn" data-range="180" style="background: #000000 !important; border: 1px solid white !important; color: white !important; padding: 10px 25px !important; cursor: pointer !important; display: inline-block !important; font-weight: bold !important; min-width: 80px !important;">180 Days</button>
                                        <button type="button" class="date-range-btn" data-range="all" style="background: #000000 !important; border: 1px solid white !important; color: white !important; padding: 10px 25px !important; cursor: pointer !important; display: inline-block !important; font-weight: bold !important; min-width: 80px !important;">ALL</button>
                                        <span class="text-white" style="margin-left: 10px; margin-right: 15px; font-size: 14px; font-weight: bold;">Filter : All / <span id="selectedDateRangeText2">7 Days</span></span>
                                        <div style="display: inline-block; margin-left: 10px;">
                                            <i class="entypo-search text-white">Country: </i>
                                            <select id="countryFilter2" class="form-control form-control-sm d-inline-block" style="width: auto; display: inline-block; background: #333; color: white; border: 1px solid #0f0;">
                                                <option value="ALL">ALL</option>
                                                <option value="CA">Canada</option>
                                                <option value="US" selected="true">United States</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Summary Metrics Cards -->
                                <div class="col-12 col-md-6">
                                    <div class="d-flex flex-wrap justify-content-end">
                                        <div class="metric-card" style="border: 2px solid #ff0000; padding: 8px 12px; margin: 5px; background: rgba(255,0,0,0.1);">
                                            <div class="text-white" style="font-size: 12px;">Daily</div>
                                            <div class="text-white neons" style="font-size: 18px; font-weight: bold;" id="metricDaily2">2268</div>
                                        </div>
                                        <div class="metric-card" style="border: 2px solid #ff0000; padding: 8px 12px; margin: 5px; background: rgba(255,0,0,0.1);">
                                            <div class="text-white" style="font-size: 12px;">MTD</div>
                                            <div class="text-white neons" style="font-size: 18px; font-weight: bold;" id="metricMTD2">41148</div>
                                        </div>
                                        <div class="metric-card" style="border: 2px solid #ff0000; padding: 8px 12px; margin: 5px; background: rgba(255,0,0,0.1);">
                                            <div class="text-white" style="font-size: 12px;">YTD</div>
                                            <div class="text-white neons" style="font-size: 18px; font-weight: bold;" id="metricYTD2">41148</div>
                                        </div>
                                        <div class="metric-card" style="border: 2px solid #ff0000; padding: 8px 12px; margin: 5px; background: rgba(255,0,0,0.1);">
                                            <div class="text-white" style="font-size: 12px;">ALL</div>
                                            <div class="text-white neons" style="font-size: 18px; font-weight: bold;" id="metricALL2">13,939,128</div>
                                        </div>
                                        <div class="metric-card" style="border: 2px solid #ff0000; padding: 8px 12px; margin: 5px; background: rgba(255,0,0,0.1);">
                                            <div class="text-white" style="font-size: 12px;">ALV</div>
                                            <div class="text-white neons" style="font-size: 18px; font-weight: bold;" id="metricALV2">680</div>
                                        </div>
                                        <div class="metric-card" style="border: 2px solid #ff0000; padding: 8px 12px; margin: 5px; background: rgba(255,0,0,0.1);">
                                            <div class="text-white" style="font-size: 12px;">ALVM</div>
                                            <div class="text-white neons" style="font-size: 18px; font-weight: bold;" id="metricALVM2">635</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Licensee Grid -->
                            <div id="licenseeGrid2" class="row" style="margin: 0;">
                                <div class="col-12 text-center text-white">Loading licensees...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card glow3" style="font-size: 16px">
        
        <div class="card-body row m-3">
            <div class="mb-2 mr-3">
                <i class="entypo-search">Year: </i>
                <select id="year">
                    <option value="0" selected="true">All</option>                   
                </select>
            </div>
            <div class="mb-2 mr-3">
                <i class="entypo-search">Month: </i>
                <select id="month">
                    <option value="0" selected="true">All</option>                   
                </select>
            </div>
            <div class="mb-2 mr-3">
                <i class="entypo-search">Licensee: </i>
                <select id="licensee">
                    <option value="-1" selected="true">Not Selected</option>
                </select>
            </div>
        </div>
        <table class="table font-w-600 mb-0" id="income_t">
            <thead >
            <tr>
                <th>Licensee Name</th>
               
                <th>Transactional Fee</th>
                <th>Monthly Amount</th>
                <th>Licensee Fee</th>
                <th>Lending Funds Required</th>
                <th>Lending Funds Received</th>
                <th>Total Income</th>
            </tr>
            </thead> 
            <tbody id="income" ></tbody>
         
        </table>
    </div> 

    <div class="card-body p-0">  
        <ul class="nav flex todo-menu" id="licensees" style="display: none;"></ul>
           <div id="dialog-1">
        <iframe id="ifram"  class="card glow3 col-12 "  style="height: 1800px;display: none">
           
        </iframe>
           </div>
    </div>
</div>
</div>

        


    <br /><br />

    <footer class="site-footer">
    Copyright @ <span id="currentYear"></span> TaxCash Pay It Forward Corp.
</footer>

    <script>
        // Login functionality
        (function() {
            // Check if user is already logged in
            var isAuthenticated = sessionStorage.getItem('taxcash_authenticated') === 'true';
            var loginOverlay = document.getElementById('loginOverlay');
            var dashboardContent = document.getElementById('dashboardContent');
            
            if (isAuthenticated) {
                if (loginOverlay) {
                    loginOverlay.style.display = 'none';
                }
                if (dashboardContent) {
                    dashboardContent.style.display = 'block';
                }
                document.body.style.overflow = 'auto';
            } else {
                // Not authenticated - show login overlay and hide dashboard
                if (loginOverlay) {
                    loginOverlay.style.display = 'flex';
                }
                if (dashboardContent) {
                    dashboardContent.style.display = 'none';
                }
                document.body.style.overflow = 'hidden';
            }

            // Handle login form submission
            var loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var username = document.getElementById('username').value;
                    var password = document.getElementById('password').value;
                    var errorDiv = document.getElementById('loginError');

                    if (username === 'admin' && password === '1907') {
                        // Successful login
                        sessionStorage.setItem('taxcash_authenticated', 'true');
                        var loginOverlay = document.getElementById('loginOverlay');
                        var dashboardContent = document.getElementById('dashboardContent');
                        if (loginOverlay) {
                            loginOverlay.style.display = 'none';
                        }
                        if (dashboardContent) {
                            dashboardContent.style.display = 'block';
                        }
                        document.body.style.overflow = 'auto';
                        if (errorDiv) {
                            errorDiv.style.display = 'none';
                        }
                    } else {
                        // Failed login
                        if (errorDiv) {
                            errorDiv.style.display = 'block';
                        }
                        var passwordField = document.getElementById('password');
                        if (passwordField) {
                            passwordField.value = '';
                            passwordField.focus();
                        }
                    }
                });
            }

            // Global logout function
            window.logout = function() {
                sessionStorage.clear();
                localStorage.clear();
                var dashboardContent = document.getElementById('dashboardContent');
                var loginOverlay = document.getElementById('loginOverlay');
                if (dashboardContent) {
                    dashboardContent.style.display = 'none';
                }
                if (loginOverlay) {
                    loginOverlay.style.display = 'flex';
                }
                document.body.style.overflow = 'hidden';
                window.location.replace('index.php?' + new Date().getTime());
            };

            // Set current year dynamically
            var yearElement = document.getElementById('currentYear');
            if (yearElement) {
                yearElement.textContent = new Date().getFullYear();
            }
        })();

        // Add event listener for logout link - multiple attempts to ensure it works
        function attachLogoutHandler() {
            var logoutLink = document.getElementById('logoutLink');
            if (logoutLink) {
                // Remove any existing handlers
                var newLink = logoutLink.cloneNode(true);
                logoutLink.parentNode.replaceChild(newLink, logoutLink);
                
                // Add click handler
                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    window.logout();
                    return false;
                });
                
                // Also add onclick as backup
                newLink.onclick = function(e) {
                    e.preventDefault();
                    window.logout();
                    return false;
                };
            }
        }
        
        // Try multiple times to ensure it attaches
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                attachLogoutHandler();
                // Try again after a short delay
                setTimeout(attachLogoutHandler, 100);
            });
        } else {
            attachLogoutHandler();
            setTimeout(attachLogoutHandler, 100);
        }
        
        // Also try on window load
        window.addEventListener('load', function() {
            attachLogoutHandler();
        });
    </script>

    <script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
    <script src="dist/vendors/jquery-ui/jquery-ui.min.js"></script>
    <script src="dist/vendors/moment/moment.js"></script>
    <script src="dist/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/vendors/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="dist/js/app.js"></script>
    <script src="dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
    <script src="dist/vendors/jquery-jvectormap/jquery-jvectormap-ca-lcc.js"></script>
    <script src="dist/vendors/raphael/raphael.min.js"></script>
    <script src="dist/vendors/morris/morris.min.js"></script>
    <script src="dist/vendors/chartjs/Chart.min.js"></script>
    <script src="dist/vendors/starrr/starrr.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.canvaswrapper.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.colorhelpers.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.saturated.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.browser.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.drawSeries.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.uiConstants.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.legend.js"></script>
    <script src="dist/vendors/jquery-flot/jquery.flot.pie.js"></script>
    <script src="dist/vendors/chartjs/Chart.min.js"></script>
    <script src="dist/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="dist/js/home.script.js"></script>
    <script src="dist/vendors/chartjs/Chart.min.js"></script>
    <script src="dist/js/chartjs.script.js"></script>

    <script>
        var useStaticIncomeData = true;
        var staticIncomeData = [
            {
                licenseeId: 1,
                licenseeName: "MoolahCashLoans",
                transactionalFee: "$ 2,859.57",
                monthlyAmount: "$ 22,354",
                licenseeFee: "$ 90,000",
                lendingFundsReceived: "$ 100,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 115,213.57"
            },
            {
                licenseeId: 2,
                licenseeName: "QualityCashLoans",
                transactionalFee: "$ 37,901.04",
                monthlyAmount: "$ 73,040",
                licenseeFee: "$ 25,000",
                lendingFundsReceived: "$ 117,488.04",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 135,941.04"
            },
            {
                licenseeId: 3,
                licenseeName: "PaisaCashLoans",
                transactionalFee: "$ 5,033.58",
                monthlyAmount: "$ 6,300",
                licenseeFee: "$ 22,500",
                lendingFundsReceived: "$ 20,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 33,833.58"
            },
            {
                licenseeId: 4,
                licenseeName: "CashBucksLoan",
                transactionalFee: "$ 2,649.76",
                monthlyAmount: "$ 0",
                licenseeFee: "$ 0",
                lendingFundsReceived: "$ 50,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 2,649.76"
            },
            {
                licenseeId: 5,
                licenseeName: "ReliableSpeedyLoans",
                transactionalFee: "$ 19,513.15",
                monthlyAmount: "$ 25,900",
                licenseeFee: "$ 45,000",
                lendingFundsReceived: "$ 100,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 90,413.15"
            },
            {
                licenseeId: 6,
                licenseeName: "MyPaydayLoan",
                transactionalFee: "$ 33.83",
                monthlyAmount: "$ 0",
                licenseeFee: "$ 0",
                lendingFundsReceived: "$ 0",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 33.83"
            },
            {
                licenseeId: 7,
                licenseeName: "KwartaLoans",
                transactionalFee: "$ 11,373.96",
                monthlyAmount: "$ 22,580",
                licenseeFee: "$ 45,000",
                lendingFundsReceived: "$ 75,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 78,953.96"
            },
            {
                licenseeId: 8,
                licenseeName: "6hFinancialServices",
                transactionalFee: "$ 3,782.05",
                monthlyAmount: "$ 18,064",
                licenseeFee: "$ 35,000",
                lendingFundsReceived: "$ 50,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 56,846.05"
            },
            {
                licenseeId: 9,
                licenseeName: "PrairieSkyLoans",
                transactionalFee: "$ 51,903.86",
                monthlyAmount: "$ 53,901",
                licenseeFee: "$ 45,000",
                lendingFundsReceived: "$ 124,718.5",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 150,804.86"
            },
            {
                licenseeId: 10,
                licenseeName: "MegaCashBucks",
                transactionalFee: "$ 17.33",
                monthlyAmount: "$ 0",
                licenseeFee: "$ 0",
                lendingFundsReceived: "$ 0",
                totalIncome: "$ 17.33"
            },
            {
                licenseeId: 11,
                licenseeName: "MyOnlineCash",
                transactionalFee: "$ 203,623.14",
                monthlyAmount: "$ 357,000",
                licenseeFee: "$ 170,500",
                lendingFundsReceived: "$ 1,200,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 731,123.14"
            },
            {
                licenseeId: 12,
                licenseeName: "PaydayCashLoans",
                transactionalFee: "$ 89,560.01",
                monthlyAmount: "$ 312,960",
                licenseeFee: "$ 184,500",
                lendingFundsReceived: "$ 1,000,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 587,020.01"
            },
            {
                licenseeId: 13,
                licenseeName: "SwiftOnlineCash",
                transactionalFee: "$ 142,071.04",
                monthlyAmount: "$ 214,112.9",
                licenseeFee: "$ 340,000",
                lendingFundsReceived: "$ 455,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 696,183.94"
            },
            {
                licenseeId: 14,
                licenseeName: "GoGreenOnline",
                transactionalFee: "$ 0",
                monthlyAmount: "$ 0",
                licenseeFee: "$ 75,000",
                lendingFundsReceived: "$ 0",
                totalIncome: "$ 75,000"
            },
            {
                licenseeId: 15,
                licenseeName: "SpeedyPay",
                transactionalFee: "$ 97,207.41",
                monthlyAmount: "$ 108,951.61",
                licenseeFee: "$ 180,000",
                lendingFundsReceived: "$ 426,980",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 386,159.02"
            },
            {
                licenseeId: 16,
                licenseeName: "GetCashFast",
                transactionalFee: "$ 115,405.81",
                monthlyAmount: "$ 167,086.02",
                licenseeFee: "$ 350,000",
                lendingFundsReceived: "$ 822,745",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 632,491.83"
            },
            {
                licenseeId: 17,
                licenseeName: "DeltaCash",
                transactionalFee: "$ 136,961.99",
                monthlyAmount: "$ 215,750",
                licenseeFee: "$ 310,000",
                lendingFundsReceived: "$ 1,007,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 662,711.99"
            },
            {
                licenseeId: 18,
                licenseeName: "CloudNineLoans",
                transactionalFee: "$ 30,777.03",
                monthlyAmount: "$ 76,667",
                licenseeFee: "$ 90,000",
                lendingFundsReceived: "$ 150,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 197,444.03"
            },
            {
                licenseeId: 19,
                licenseeName: "EasyBuckOnline",
                transactionalFee: "$ 21,669.92",
                monthlyAmount: "$ 30,350",
                licenseeFee: "$ 90,000",
                lendingFundsReceived: "$ 161,800",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 142,019.92"
            },
            {
                licenseeId: 20,
                licenseeName: "MadDashLoans",
                transactionalFee: "$ 37,040.44",
                monthlyAmount: "$ 73,741.9",
                licenseeFee: "$ 125,000",
                lendingFundsReceived: "$ 160,100",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 235,782.34"
            },
            {
                licenseeId: 21,
                licenseeName: "TideWaterFinancial",
                transactionalFee: "$ 46,245.67",
                monthlyAmount: "$ 73,536.18",
                licenseeFee: "$ 100,000",
                lendingFundsReceived: "$ 316,270.92",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 219,781.85"
            },
            {
                licenseeId: 22,
                licenseeName: "MaxCapSolutions",
                transactionalFee: "$ 58,199.98",
                monthlyAmount: "$ 164,333.33",
                licenseeFee: "$ 165,000",
                lendingFundsReceived: "$ 426,818.67",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 387,533.31"
            },
            {
                licenseeId: 23,
                licenseeName: "Sundog Financial Solutions",
                transactionalFee: "$ 44,249.91",
                monthlyAmount: "$ 55,500",
                licenseeFee: "$ 115,000",
                lendingFundsReceived: "$ 211,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 214,749.91"
            },
            {
                licenseeId: 24,
                licenseeName: "CashCartLoans",
                transactionalFee: "$ 38,251.1",
                monthlyAmount: "$ 106,548.57",
                licenseeFee: "$ 200,000",
                lendingFundsReceived: "$ 360,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 344,799.67"
            },
            {
                licenseeId: 25,
                licenseeName: "NorthRidgePaydayCash",
                transactionalFee: "$ 49,718.69",
                monthlyAmount: "$ 185,403.81",
                licenseeFee: "$ 400,000",
                lendingFundsReceived: "$ 950,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 635,122.5"
            },
            {
                licenseeId: 26,
                licenseeName: "GreenTreeCash",
                transactionalFee: "$ 61,747.43",
                monthlyAmount: "$ 101,500",
                licenseeFee: "$ 275,000",
                lendingFundsReceived: "$ 700,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 438,247.43"
            },
            {
                licenseeId: 27,
                licenseeName: "RapidCashToday",
                transactionalFee: "$ 48,952.25",
                monthlyAmount: "$ 19,112.9",
                licenseeFee: "$ 0",
                lendingFundsReceived: "$ 200,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 68,065.15"
            },
            {
                licenseeId: 28,
                licenseeName: "BrexPrime",
                transactionalFee: "$ 18,063.49",
                monthlyAmount: "$ 0",
                licenseeFee: "$ 0",
                lendingFundsReceived: "$ 360,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 18,063.49"
            },
            {
                licenseeId: 29,
                licenseeName: "BlackIrishCapital",
                transactionalFee: "$ 0",
                monthlyAmount: "$ 0",
                licenseeFee: "$ 0",
                lendingFundsReceived: "$ 225,000",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 0"
            },
            {
                licenseeId: 999,
                licenseeName: "All",
                transactionalFee: "$ 1,374,813.44",
                monthlyAmount: "$ 2,484,693.22",
                licenseeFee: "$ 3,477,500",
                lendingFundsReceived: "$ 9,769,921.13",
                lendingFundsShort: "$ 0",
                totalIncome: "$ 7,337,006.66",
                isAggregate: true
            }
        ];
        var staticLicenseeOptions = [
            { licenseeId: 1, licenseeName: "MoolahCashLoans.Ca", showInNav: true },
            { licenseeId: 4, licenseeName: "CashBucksLoan.Com", showInNav: false },
            { licenseeId: 6, licenseeName: "MyPaydayLoan.Ca", showInNav: false },
            { licenseeId: 10, licenseeName: "MegaCashBucks.Ca", showInNav: false },
            { licenseeId: 2, licenseeName: "QualityCashLoans.Com", showInNav: true },
            { licenseeId: 3, licenseeName: "PaisaCashLoans.Com", showInNav: true },
            { licenseeId: 5, licenseeName: "ReliableSpeedyLoans.Com", showInNav: true },
            { licenseeId: 7, licenseeName: "KwartaLoans.Com", showInNav: true },
            { licenseeId: 8, licenseeName: "6hFinancialServices.Com", showInNav: true },
            { licenseeId: 9, licenseeName: "PrairieSkyLoans.com", showInNav: true },
            { licenseeId: 11, licenseeName: "MyOnlineCash.Ca", showInNav: true },
            { licenseeId: 12, licenseeName: "PaydayCashLoans.Ca", showInNav: true },
            { licenseeId: 13, licenseeName: "SwiftOnlineCash.Com", showInNav: true },
            { licenseeId: 14, licenseeName: "GoGreenOnline.Ca", showInNav: true },
            { licenseeId: 15, licenseeName: "SpeedyPay.Ca", showInNav: true },
            { licenseeId: 16, licenseeName: "GetCashFast.Ca", showInNav: true },
            { licenseeId: 17, licenseeName: "DeltaCash.Ca", showInNav: true },
            { licenseeId: 18, licenseeName: "CloudNineLoans.Com", showInNav: true },
            { licenseeId: 19, licenseeName: "EasyBuckOnline.Ca", showInNav: true },
            { licenseeId: 20, licenseeName: "MadDashLoans.Com", showInNav: true },
            { licenseeId: 21, licenseeName: "TideWaterFinancial.Ca", showInNav: true },
            { licenseeId: 22, licenseeName: "MaxCapSolutions.Ca", showInNav: true },
            { licenseeId: 23, licenseeName: "Sundog Financial Solutions", showInNav: true },
            { licenseeId: 24, licenseeName: "CashCartLoans.Com", showInNav: true },
            { licenseeId: 25, licenseeName: "NorthRidgePaydayCash.Com", showInNav: true },
            { licenseeId: 26, licenseeName: "GreenTreeCash.Ca", showInNav: true },
            { licenseeId: 27, licenseeName: "RapidCashToday.Ca", showInNav: true },
            { licenseeId: 28, licenseeName: "BrexPrime.Com", showInNav: true },
            { licenseeId: 30, licenseeName: "TitanEdgeUSA.Com", showInNav: true },
            { licenseeId: 31, licenseeName: "BrattsLakeSolutions.Com", showInNav: true },
            { licenseeId: 32, licenseeName: "SageEndeavours.Com", showInNav: true },
            { licenseeId: 33, licenseeName: "PrestoVenturesGroup", showInNav: true },
            { licenseeId: 34, licenseeName: "BlackSilverCapital.Com", showInNav: true },
            { licenseeId: 35, licenseeName: "SwiftCashToday.Com", showInNav: true },
            { licenseeId: 29, licenseeName: "BlackIrishCapital", showInNav: true },
            { licenseeId: 36, licenseeName: "TompkinsFinance", showInNav: true },
            { licenseeId: 999, licenseeName: "All", showInNav: false, includeInSelect: false }
        ];
        var currentIncomeData = staticIncomeData.slice();
        var currentLicenseeOptions = staticLicenseeOptions.slice();
        var licenseeTableVisibilityMap = {};

        function initializeStaticContent() {
            currentIncomeData = staticIncomeData.slice();
            currentLicenseeOptions = staticLicenseeOptions.slice();
            updateLicenseeTableVisibilityMap(currentLicenseeOptions);
            populateLicenseeControlsFromList(currentLicenseeOptions, false);
            renderIncomeTableRows(getIncomeRows());
        }

        function populateLicenseeControlsFromList(list, isServerData) {
            var $licenseeSelect = $('#licensee');
            var $licenseeNav = $('#licensees');
            $licenseeSelect.find('option').not('[value="-1"]').remove();
            $licenseeNav.empty();
            currentLicenseeOptions = list.slice();
            updateLicenseeTableVisibilityMap(currentLicenseeOptions);
            list.forEach(function(item) {
                var name = item.licenseeName || item.title || item.name || "";
                var value = item.licenseeId != null ? item.licenseeId : item.id;
                if (!name || value === undefined || value === null) {
                    return;
                }
                var includeOption = (item.includeInSelect !== false || isServerData);
                if (includeOption) {
                    $licenseeSelect.append('<option value="' + value + '">' + name + '</option>');
                }
                var showNav = item.showInNav !== false && item.isAggregate !== true;
                if (isServerData && name.toLowerCase() === "all") {
                    showNav = false;
                }
                if (showNav) {
                    $licenseeNav.append(' <li class="nav-item "> <a class="nav-link licenseeName" onclick="licensees(' + value + ',this);return false;" ItemId="' + value + '">' + name + '</a></li>');
                }
            });
            $licenseeSelect.val('-1');
        }

        function updateLicenseeTableVisibilityMap(list) {
            licenseeTableVisibilityMap = {};
            list.forEach(function(item) {
                var id = item.licenseeId != null ? Number(item.licenseeId) : null;
                var name = (item.licenseeName || "").toLowerCase();
                var show = item.showInTable !== false;
                if (id != null) {
                    licenseeTableVisibilityMap["id:" + id] = show;
                }
                if (name) {
                    licenseeTableVisibilityMap["name:" + name] = show;
                }
            });
        }

        function getLicenseeTableFlag(entry) {
            if (!entry) {
                return undefined;
            }
            var id = entry.licenseeId != null ? Number(entry.licenseeId) : null;
            var name = (entry.licenseeName || "").toLowerCase();
            if (id != null && Object.prototype.hasOwnProperty.call(licenseeTableVisibilityMap, "id:" + id)) {
                return licenseeTableVisibilityMap["id:" + id];
            }
            if (name && Object.prototype.hasOwnProperty.call(licenseeTableVisibilityMap, "name:" + name)) {
                return licenseeTableVisibilityMap["name:" + name];
            }
            return undefined;
        }

        function renderIncomeTableRows(rows) {
            console.log("renderIncomeTableRows called with", rows ? rows.length : 0, "rows");
            console.log("useStaticIncomeData =", useStaticIncomeData);
            var visibleRows = filterVisibleLicensees(rows);
            var tbody = "";
            if (visibleRows && visibleRows.length) {
                visibleRows.forEach(function(row) {
                    // Check for GoGreenOnline specifically
                    if (row.licenseeName === "GoGreenOnline.Ca" || row.licenseeId === 14) {
                        console.log("GoGreenOnline row data:", row);
                    }
                    var transactional = row.transactionalFee;
                    if (transactional === undefined && row.totalFeeAmount !== undefined) {
                        transactional = row.totalFeeAmount;
                    }
                    var monthly = row.monthlyAmount;
                    if (monthly === undefined && row.totalMonthlyAmount !== undefined) {
                        monthly = row.totalMonthlyAmount;
                    }
                    var licenseeFee = row.licenseeFee;
                    if (licenseeFee === undefined && row.totalFirstAmount !== undefined) {
                        licenseeFee = row.totalFirstAmount;
                    }
                    var lendingFunds = row.lendingFundsReceived;
                    if (lendingFunds === undefined && row.totalBalanceAmount !== undefined) {
                        lendingFunds = row.totalBalanceAmount;
                    }
                    var lendingFundsShort = row.lendingFundsShort;
                    if (lendingFundsShort === undefined) {
                        lendingFundsShort = 0;
                    }
                    var income = row.totalIncome;
                    tbody += '<tr>' +
                        '<td>' + (row.licenseeName || '') + '</td>' +
                        '<td class="text-warning">' + formatIncomeCell(transactional) + '</td>' +
                        '<td class="text-warning">' + formatIncomeCell(monthly) + '</td>' +
                        '<td class="text-warning">' + formatIncomeCell(licenseeFee) + '</td>' +
                        '<td class="text-secondary">' + formatIncomeCell(lendingFunds) + '</td>' +
                        '<td class="text-secondary">' + formatIncomeCell(lendingFundsShort) + '</td>' +
                        '<td class="text-success">' + formatIncomeCell(income) + '</td>' +
                        '</tr>';
                });
            }
            if (!tbody) {
                tbody = '<tr><td colspan="7" style="text-align: center;">No record found</td></tr>';
            }
            $('#income').html(tbody);
        }

        function numberWithCommas(x) {
            x = x.toString();
            var pattern = /(-?\d+)(\d{3})/;
            while (pattern.test(x))
                x = x.replace(pattern, "$1,$2");
            return x;
        }

        function formatCurrencyValue(value) {
            if (value === null || value === undefined || value === "") {
                return "$0";
            }
            var num = Number(value);
            if (isNaN(num)) {
                return value;
            }
            var hasDecimals = Math.round(num * 100) % 100 !== 0;
            var text = hasDecimals ? num.toFixed(2) : num.toFixed(0);
            return "$" + numberWithCommas(text);
        }

        function formatIncomeCell(value) {
            if (value === null || value === undefined || value === "") {
                return formatCurrencyValue(0);
            }
            if (typeof value === "number") {
                return formatCurrencyValue(value);
            }
            var text = value.toString().trim();
            if (!text) {
                return formatCurrencyValue(0);
            }
            if (text[0] === "$") {
                return text;
            }
            var numeric = Number(text.replace(/[^0-9.\-]/g, ""));
            if (!isNaN(numeric)) {
                return formatCurrencyValue(numeric);
            }
            return text;
        }

        function findLicenseeOption(entry) {
            if (!entry || !currentLicenseeOptions) {
                return null;
            }
            var id = entry.licenseeId != null ? Number(entry.licenseeId) : null;
            var name = (entry.licenseeName || "").toLowerCase();
            for (var i = 0; i < currentLicenseeOptions.length; i++) {
                var opt = currentLicenseeOptions[i];
                if (!opt) continue;
                if (id != null && Number(opt.licenseeId) === id) {
                    return opt;
                }
                if (name && (opt.licenseeName || "").toLowerCase() === name) {
                    return opt;
                }
            }
            return null;
        }

        function isLicenseeVisible(entry) {
            if (!entry) {
                return false;
            }
            if (entry.isAggregate) {
                return true;
            }
            var tableFlag;
            if (entry.showInTable !== undefined) {
                tableFlag = entry.showInTable !== false;
            } else {
                var optTable = findLicenseeOption(entry);
                if (optTable && optTable.showInTable !== undefined) {
                    tableFlag = optTable.showInTable !== false;
                } else {
                    var mapFlag = getLicenseeTableFlag(entry);
                    tableFlag = mapFlag !== undefined ? mapFlag : true;
                }
            }

            var navFlag;
            if (entry.showInNav !== undefined) {
                navFlag = entry.showInNav !== false;
            } else {
                var optNav = findLicenseeOption(entry);
                if (optNav && optNav.showInNav !== undefined) {
                    navFlag = optNav.showInNav !== false;
                } else {
                    navFlag = true;
                }
            }

            return tableFlag && navFlag;
        }

        function filterVisibleLicensees(rows) {
            if (!rows || !rows.length) {
                return [];
            }
            return rows.filter(isLicenseeVisible);
        }

        function renderGauges(gaugeData) {
            // Only render gauges from data - no fallback defaults
            if (!gaugeData || !gaugeData.length) {
                console.warn("No gauge data provided - gauges will not be rendered");
                return;
            }
            var renderedTargets = {};
            gaugeData.forEach(function(gauge, index) {
                var target = gauge.id || ("chartdiv" + (index + 1));
                if (renderedTargets[target]) {
                    target = "chartdiv" + (index + 1);
                }
                renderedTargets[target] = true;
                var value = Number(gauge.value);
                if (isNaN(value)) {
                    value = 0;
                }
                drawGauge(target, value, gauge.title || "");
            });
        }

        function buildLicenseeOptionsFromData(list) {
            return list.map(function(item) {
                var isAggregate = !!item.isAggregate;
                return {
                    licenseeId: item.licenseeId,
                    licenseeName: item.licenseeName,
                    showInNav: isAggregate ? false : item.showInNav !== false,
                    includeInSelect: isAggregate ? false : item.includeInSelect !== false,
                    showInTable: item.showInTable !== false,
                    isAggregate: isAggregate
                };
            });
        }

        function applyLicenseeIncomeDataset(list) {
            if (!Array.isArray(list) || !list.length) {
                console.warn("applyLicenseeIncomeDataset: Invalid or empty list");
                return;
            }
            console.log("Applying licensee income dataset with", list.length, "entries");
            // Convert numeric values to match the format expected by formatIncomeCell
            currentIncomeData = list.map(function(item) {
                return {
                    licenseeId: item.licenseeId,
                    licenseeName: item.licenseeName,
                    transactionalFee: typeof item.transactionalFee === 'number' ? item.transactionalFee : parseFloat(String(item.transactionalFee).replace(/[^0-9.\-]/g, '')) || 0,
                    monthlyAmount: typeof item.monthlyAmount === 'number' ? item.monthlyAmount : parseFloat(String(item.monthlyAmount).replace(/[^0-9.\-]/g, '')) || 0,
                    licenseeFee: typeof item.licenseeFee === 'number' ? item.licenseeFee : parseFloat(String(item.licenseeFee).replace(/[^0-9.\-]/g, '')) || 0,
                    lendingFundsReceived: typeof item.lendingFundsReceived === 'number' ? item.lendingFundsReceived : parseFloat(String(item.lendingFundsReceived).replace(/[^0-9.\-]/g, '')) || 0,
                    lendingFundsShort: typeof item.lendingFundsShort === 'number' ? item.lendingFundsShort : parseFloat(String(item.lendingFundsShort).replace(/[^0-9.\-]/g, '')) || 0,
                    totalIncome: typeof item.totalIncome === 'number' ? item.totalIncome : parseFloat(String(item.totalIncome).replace(/[^0-9.\-]/g, '')) || 0,
                    showInNav: item.showInNav !== false,
                    includeInSelect: item.includeInSelect !== false,
                    showInTable: item.showInTable !== false,
                    isAggregate: item.isAggregate === true
                };
            });
            currentLicenseeOptions = buildLicenseeOptionsFromData(list);
            populateLicenseeControlsFromList(currentLicenseeOptions, false);
            useStaticIncomeData = false;
            console.log("Updated currentIncomeData, useStaticIncomeData = false");
            renderIncomeTableRows(getIncomeRows());
        }

        function loadLicenseeIncomeData() {
            var cacheBuster = "?v=" + Date.now() + "&t=" + Math.random();
            return fetch("data/licensee-income.json" + cacheBuster, {
                cache: 'no-store',
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache'
                }
            })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error("Failed to load licensee data: " + response.status);
                    }
                    return response.json();
                })
                .then(function(payload) {
                    console.log("Licensee income data loaded from JSON:", payload);
                    console.log("Payload type:", typeof payload, "Is array:", Array.isArray(payload));
                    if (payload && Array.isArray(payload.licensees) && payload.licensees.length) {
                        console.log("Found", payload.licensees.length, "licensees in payload.licensees");
                        // Check GoGreenOnline specifically
                        var goGreen = payload.licensees.find(function(l) { return l.licenseeId === 14 || l.licenseeName === "GoGreenOnline.Ca"; });
                        if (goGreen) {
                            console.log("GoGreenOnline data from JSON:", goGreen);
                        }
                        applyLicenseeIncomeDataset(payload.licensees);
                        return Promise.resolve();
                    } else if (Array.isArray(payload) && payload.length) {
                        console.log("Found", payload.length, "licensees in payload array");
                        applyLicenseeIncomeDataset(payload);
                        return Promise.resolve();
                    } else {
                        throw new Error("No licensee data found in payload");
                    }
                })
                .catch(function(error) {
                    console.error("Error loading licensee income data:", error);
                    throw error; // Re-throw so caller can handle fallback
                });
        }

        function getIncomeRows() {
            var selectedId = $('#licensee').val();
            // Always prefer currentIncomeData (from JSON) over static data
            var dataSet = (currentIncomeData && currentIncomeData.length && !useStaticIncomeData) ? currentIncomeData : staticIncomeData;
            if (!selectedId || selectedId === '-1') {
                return filterVisibleLicensees(dataSet);
            }
            var idNumber = Number(selectedId);
            var filtered = dataSet.filter(function(entry) {
                return entry.licenseeId === idNumber || entry.isAggregate;
            });
            if (!filtered.length) {
                filtered = dataSet.filter(function(entry) {
                    return entry.isAggregate;
                });
            }
            return filterVisibleLicensees(filtered);
        }

        function fetchLicenseesFromServer() {
            if (!shouldCallRemoteApis()) {
                return;
            }
            $.ajax({
                url: window.location.origin + "/Admin/GetActiveLicenseeList",
                type: "post",
                dataType: "json"
            }).done(function(result) {
                if (result && result.data && result.data.length) {
                    populateLicenseeControlsFromList(result.data, true);
                    reloadPage();
                }
            });
        }

        function shouldCallRemoteApis() {
            var host = (window.location.hostname || "").toLowerCase();
            if (!host) {
                return false;
            }
            return host !== "localhost" && host !== "127.0.0.1" && host !== "::1";
        }

        function initStaticAndRemoteData() {
            // Load JSON data first, then fallback to static if needed
            loadLicenseeIncomeData().then(function() {
                // JSON loaded successfully
                console.log("Licensee data loaded from JSON file");
            }).catch(function(error) {
                console.warn("Failed to load JSON, using static data:", error);
                initializeStaticContent();
            });
            fetchLicenseesFromServer();
        }

        jQuery(document).ready(function($) {
            initStaticAndRemoteData();
            var cuYear = new Date().getFullYear();
            for (var y = 2019; y <= cuYear; y++) {
                $('#year').append("<option value='" + y + "'>" + y + "</option>");
            }
            for (var m = 1; m <= 12; m++) {
                $('#month').append("<option value='" + m + "'>" + m + "</option>");
            }
            $("[aria-labelledby='id-66-title']").hide();
            $("[aria-labelledby='id-713-title']").hide();
            $("[aria-labelledby='id-602-title']").hide();
            $("[aria-labelledby='id-491-title']").hide();
            $("[aria-labelledby='id-380-title']").hide();
            $("[aria-labelledby='id-269-title']").hide();
            $("[aria-labelledby='id-158-title']").hide();

            $.QueryString = (function(a) {
                if (a == "") return {};
                var b = {};
                for (var i = 0; i < a.length; ++i) {
                    var p = a[i].split('=');
                    if (p.length != 2) continue;
                    b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
                }
                return b;
            })(window.location.search.substr(1).split('&'));
            var licenseeId = -1;
            var year = 0;
            var month = 0;
            $(document).on('change',
                '#year',
                function() {
                    reloadPage();
                });
            $(document).on('change',
                '#month',
                function() {
                    reloadPage();
                });
            reloadPage();
        });

        $(document).on('change',
            '#licensee',
            function() {
                reloadPage();
            });

        // numberWithCommas moved to top to be accessible by formatCurrencyValue

        function reloadPage() {
            var year = $('#year').val(), month = $('#month').val(), licenseeId = $('#licensee').val();
            renderIncomeTableRows(getIncomeRows());
            if (!shouldCallRemoteApis()) {
                return;
            }
            $.ajax({
                url: "/Admin/Income?licenseeId=" + licenseeId + "&year=" + year + "&month=" + month,
                type: 'GET',
                success: function(data) {
                    if (data && data.incomes && data.incomes.length) {
                        useStaticIncomeData = false;
                        renderIncomeTableRows(data.incomes);
                    } else if (!useStaticIncomeData) {
                        renderIncomeTableRows(getIncomeRows());
                    }
                },
                error: function() {
                    useStaticIncomeData = true;
                    renderIncomeTableRows(getIncomeRows());
                }
            });
        }


        function exportCharts() {
            window.open("/excel/GetLicenseeAgingDetail?licenseeId=" + $("#dropdownLicensee").val());
        }

        function licensees(itemId, a) {
            $("#ifram").css('display', 'inherit');
            $(".licenseeName").removeClass("active");
            $(a).addClass("active");

            $("#ifram").attr('src', '/LicAdmin/LicenReportA2?licenseeId=' + itemId + '&isdlg=1');

        }


    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>

        <script type="text/javascript">
            window.onload = function () {
            this.setInterval(function() {

                $.ajax({
                    url: window.location.origin + "/Admin/CheckNewManualLoan",
                    type: "get",
                    dataType: "json",
                    beforeSend: function(x) {
                        if (x && x.overrideMimeType)
                        {
                            x.overrideMimeType("application/json;charset=UTF-8");
                        };
                    },
                    complete: function(result) {
                        console.log(result);
                        if (result.statusText == "success" || result.statusText == "OK")
                        {
                            for (i = 0; i < result.responseJSON.length; i++)
                            {
                                CreateNotification(result.responseJSON[i].id);
                            }
                        }
                        else
                        {

                        }
                    }
                });

                $.ajax({
                    url: window.location.origin + "/Admin/CheckNewBorrowerAccountCheck",
                    type: "get",
                    dataType: "json",
                    beforeSend: function(x) {
                        if (x && x.overrideMimeType) {
                            x.overrideMimeType("application/json;charset=UTF-8");
                        };
                    },
                    complete: function(result) {
                        console.log(result);
                        if (result.statusText == "success" || result.statusText == "OK") {
                            for (i = 0; i < result.responseJSON.length; i++) {
                                CreateBorrowerNotification(result.responseJSON[i].memberId);
                            }
                        } else {

                        }
                    }
                });

                RefreshIssueNotesCount();
            },
            1000 * 120);


                RefreshIssueNotesCount(); // initial run
            };
                function RefreshIssueNotesCount(){
                    $.ajax({
                        url: window.location.origin + "/Admin/CheckIssueNotesCount",
                        type: "get",
                        dataType: "json",
                        beforeSend: function (x) {
                            if (x && x.overrideMimeType) {
                                x.overrideMimeType("application/json;charset=UTF-8");
                            };
                        },
                        success: function (result) {
                            console.log('Issue Notes Count: ', result);
                            $('.nav-badge-issue-notes-count').text(result);
                        }
                    });
                }

                function CreateBorrowerNotification(memberId) {
                    var opts = {
                        "closeButton": true,
                        "debug": false,
                        "positionClass": "toast-bottom-left",
                        "onclick": function () { window.open("/Admin/BorrowerBankAccountEditA2?memberId=" + memberId);
                            return false;
                        },
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "0",
                        "extendedTimeOut": "0",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    toastr.warning("New Borrower Bank Account has problem. MID" + memberId, "Borrower Bank Account!", opts);
                }
                function CreateNotification(LoanId) {
                    var opts = {
                        "closeButton": true,
                        "debug": false,
                        "positionClass": "toast-bottom-left",
                        "onclick": function () { window.open("/Admin/LoansEditA2?id=" + LoanId);
                            return false;
                        },
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "0",
                        "extendedTimeOut": "0",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    toastr.success("New Manual Loan was created. LID" + LoanId, "New manual loan!", opts);
                }
        </script>

        <script>
            (function() {
                var dashboardMetricsDefaults = {
                    cards: {
                        balances: {
                            available: 43477,
                            pending: 99103,
                            account: 142580,
                            cumulative: 5247154
                        },
                        funding: {
                            total: 8475646,
                            etransfer: 3909401,
                            received: 9769921,
                            balance: 1294275
                        },
                        roi: {
                            percentage: 29.57,
                            investment: 8475646,
                            income: 7337006.66
                        },
                        territories: {
                            totalAmount: 8620000,
                            totalCount: 47,
                            soldAmount: 3369000,
                            soldCount: 25,
                            inProgressAmount: 0,
                            inProgressCount: 0,
                            onHoldAmount: 3115000,
                            onHoldCount: 10
                        }
                    }
                };

                function formatCurrencyValue(value) {
                    if (value === null || value === undefined || value === "") {
                        return "$0";
                    }
                    var num = Number(value);
                    if (isNaN(num)) {
                        return value;
                    }
                    var hasDecimals = Math.round(num * 100) % 100 !== 0;
                    var text = hasDecimals ? num.toFixed(2) : num.toFixed(0);
                    return "$" + numberWithCommas(text);
                }

                function formatPercentageValue(value) {
                    if (value === null || value === undefined || value === "") {
                        return "0%";
                    }
                    var num = Number(value);
                    if (isNaN(num)) {
                        return value;
                    }
                    var text = num.toFixed(2).replace(/\.00$/, "");
                    return text + "%";
                }

                function formatCountValue(value) {
                    if (value === null || value === undefined || value === "") {
                        return "0";
                    }
                    var num = Number(value);
                    if (isNaN(num)) {
                        return value;
                    }
                    return numberWithCommas(num.toFixed(0));
                }

                function setText(id, value) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.textContent = value;
                    }
                }

                function formatAmountWithCount(amount, count) {
                    return formatCurrencyValue(amount) + " / " + formatCountValue(count);
                }

                function applyDashboardMetrics(payload) {
                    var data = payload && payload.cards ? payload.cards : dashboardMetricsDefaults.cards;
                    var balances = data.balances || dashboardMetricsDefaults.cards.balances;
                    var funding = data.funding || dashboardMetricsDefaults.cards.funding;
                    var roi = data.roi || dashboardMetricsDefaults.cards.roi;
                    var territories = data.territories || dashboardMetricsDefaults.cards.territories;

                    setText("availableBalanceValue", formatCurrencyValue(balances.available));
                    setText("pendingBalanceValue", formatCurrencyValue(balances.pending));
                    setText("accountBalanceValue", formatCurrencyValue(balances.account));
                    setText("cumulativeBalanceValue", formatCurrencyValue(balances.cumulative));

                    setText("fundingTotalValue", formatCurrencyValue(funding.total));
                    setText("fundingEtransferValue", formatCurrencyValue(funding.etransfer));
                    setText("fundingReceivedValue", formatCurrencyValue(funding.received));
                    setText("fundingBalanceValue", formatCurrencyValue(funding.balance));

                    setText("roiPercentageValue", formatPercentageValue(roi.percentage));
                    setText("roiLendingPercentageValue", formatPercentageValue(roi.percentage));
                    setText("roiSalesPercentageValue", formatPercentageValue(roi.salesPercentage || roi.percentage));
                    setText("roiInvestmentValue", formatCurrencyValue(roi.investment));
                    setText("roiIncomeValue1", formatCurrencyValue(roi.income));
                    setText("roiIncomeValue2", formatCurrencyValue(roi.income2 || 7337006.66));

                    setText("territoriesTotalValue", formatAmountWithCount(territories.totalAmount, territories.totalCount));
                    setText("territoriesSoldValue", formatAmountWithCount(territories.soldAmount, territories.soldCount));
                    setText("territoriesProgressValue", formatAmountWithCount(territories.inProgressAmount, territories.inProgressCount));
                    setText("territoriesHoldingValue", formatAmountWithCount(territories.onHoldAmount, territories.onHoldCount));
                }

                function loadDashboardMetrics() {
                    fetch("data/dashboard-metrics.json?v=" + Date.now() + "&t=" + Math.random())
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error("Failed to load metrics");
                            }
                            return response.json();
                        })
                        .then(function(payload) {
                            applyDashboardMetrics(payload);
                        })
                        .catch(function() {
                            applyDashboardMetrics(dashboardMetricsDefaults);
                        });
                }

                function initDashboardMetrics() {
                    if (document.readyState === "loading") {
                        document.addEventListener("DOMContentLoaded", loadDashboardMetrics);
                    } else {
                        loadDashboardMetrics();
                    }
                }
                initDashboardMetrics();
            })();
        </script>

        <script>
            (function() {
                var totalLoansDefaults = {
                    totalLoans: {
                        count: 0,
                        today: 0,
                        month: 0,
                        value: 0
                    },
                    approvedLoans: {
                        value: 0,
                        totalAmount: 0,
                        pending: 0
                    },
                    averageLoan: {
                        average: 0,
                        min: 0,
                        max: 0
                    },
                    registeredUsers: {
                        count: 0,
                        today: 0,
                        month: 0
                    }
                };

                function formatCurrencyValue(value) {
                    if (value === null || value === undefined || value === "") {
                        return "$0";
                    }
                    var num = Number(value);
                    if (isNaN(num)) {
                        return value;
                    }
                    var hasDecimals = Math.round(num * 100) % 100 !== 0;
                    var text = hasDecimals ? num.toFixed(2) : num.toFixed(0);
                    return "$" + numberWithCommas(text);
                }

                function formatCountValue(value) {
                    if (value === null || value === undefined || value === "") {
                        return "0";
                    }
                    var num = Number(value);
                    if (isNaN(num)) {
                        return value;
                    }
                    return numberWithCommas(num.toFixed(0));
                }

                function setText(id, value) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.textContent = value;
                    }
                }

                function applyTotalLoansMetrics(payload) {
                    var metrics = payload && payload.cards ? payload.cards : totalLoansDefaults;
                    
                    // Total Loans section
                    var totalLoans = metrics.totalLoans || totalLoansDefaults.totalLoans;
                    setText("totalLoansCount", formatCountValue(totalLoans.count));
                    setText("totalLoansToday", formatCountValue(totalLoans.today));
                    setText("totalLoansMonth", formatCountValue(totalLoans.month));
                    setText("totalLoansValue", formatCurrencyValue(totalLoans.value));

                    // Approved Loan Value section
                    var approvedLoans = metrics.approvedLoans || totalLoansDefaults.approvedLoans;
                    setText("approvedLoanValue", formatCurrencyValue(approvedLoans.value));
                    setText("totalLoansAmount", formatCurrencyValue(approvedLoans.totalAmount));
                    setText("pendingLoanValue", formatCurrencyValue(approvedLoans.pending));

                    // Average Loan Value section
                    var averageLoan = metrics.averageLoan || totalLoansDefaults.averageLoan;
                    setText("averageLoanValue", formatCurrencyValue(averageLoan.average));
                    setText("minLoanValue", formatCurrencyValue(averageLoan.min));
                    setText("maxLoanValue", formatCurrencyValue(averageLoan.max));

                    // Registered Users section
                    var registeredUsers = metrics.registeredUsers || totalLoansDefaults.registeredUsers;
                    setText("registeredUsersCount", formatCountValue(registeredUsers.count));
                    setText("registeredUsersToday", formatCountValue(registeredUsers.today));
                    setText("registeredUsersMonth", formatCountValue(registeredUsers.month));
                }

                function loadTotalLoansMetrics() {
                    fetch("data/dashboard-metrics.json?v=" + Date.now() + "&t=" + Math.random())
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error("Failed to load metrics");
                            }
                            return response.json();
                        })
                        .then(function(payload) {
                            applyTotalLoansMetrics(payload);
                        })
                        .catch(function() {
                            applyTotalLoansMetrics(totalLoansDefaults);
                        });
                }

                function initTotalLoansMetrics() {
                    if (document.readyState === "loading") {
                        document.addEventListener("DOMContentLoaded", loadTotalLoansMetrics);
                    } else {
                        loadTotalLoansMetrics();
                    }
                }
                initTotalLoansMetrics();
            })();
        </script>

        <script>
            (function() {
                // Helper function to set text
                function setText(id, value) {
                    var el = document.getElementById(id);
                    if (el) {
                        el.textContent = value;
                    }
                }
                
                // Color mapping for licensee boxes
                var colorClasses = {
                    "blue": "licensee-box-blue",
                    "green": "licensee-box-green",
                    "yellow": "licensee-box-yellow",
                    "pink": "licensee-box-pink",
                    "red": "licensee-box-red",
                    "lightblue": "licensee-box-lightblue"
                };

                function getColorClass(color) {
                    return colorClasses[color] || "licensee-box-blue";
                }

                function formatLicenseeNumber(num) {
                    var numStr = String(num);
                    // Add commas for numbers >= 1000
                    if (num >= 1000) {
                        return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                    // Keep padding for smaller numbers
                    return numStr.padStart(3, '0');
                }

                function renderLicenseeGrid(licensees) {
                    console.log("renderLicenseeGrid called with", licensees ? licensees.length : 0, "licensees");
                    var grid2 = document.getElementById("licenseeGrid2");
                    console.log("licenseeGrid2 element found:", grid2);
                    
                    if (!grid2) {
                        console.error("licenseeGrid2 element not found!");
                        return;
                    }
                    
                    if (!licensees || !licensees.length) {
                        console.warn("No licensees to render");
                        grid2.innerHTML = '<div class="col-12 text-center text-white">No licensees found</div>';
                        return;
                    }
                    
                    // Clear existing content
                    var existingContent = grid2.innerHTML;
                    grid2.innerHTML = "";
                    
                    // Render licensees from dashboard-metrics.json
                    licensees.forEach(function(licensee) {
                        var col = document.createElement("div");
                        col.className = "col-12 col-sm-6 col-md-4 col-lg-2";
                        col.style.marginBottom = "2px";
                        col.style.padding = "2px";
                        
                        var nameBox = document.createElement("div");
                        nameBox.className = "licensee-name-box";
                        nameBox.textContent = licensee.name;
                        
                        var ratioBox = document.createElement("div");
                        ratioBox.className = "licensee-ratio-box " + getColorClass(licensee.color);
                        ratioBox.textContent = licensee.ratio;
                        // Initialize second number to random higher value if it's low
                        setTimeout(function() {
                            initializeRatioSecondNumber(ratioBox);
                        }, 100);
                        
                        var container = document.createElement("div");
                        container.className = "d-flex align-items-center";
                        container.style.marginBottom = "0";
                        container.appendChild(nameBox);
                        container.appendChild(ratioBox);
                        
                        col.appendChild(container);
                        grid2.appendChild(col);
                    });
                    console.log("Rendered", licensees.length, "licensees to licenseeGrid2");
                }

                function loadLicenseesFromIncomeJson() {
                    return fetch("data/licensee-income.json?v=" + Date.now() + "&t=" + Math.random())
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error("Failed to load licensee income data");
                            }
                            return response.json();
                        })
                        .then(function(payload) {
                            if (payload && Array.isArray(payload.licensees)) {
                                // Filter out aggregate and return only active licensees
                                return payload.licensees.filter(function(l) {
                                    return !l.isAggregate && l.showInTable !== false;
                                });
                            }
                            return [];
                        })
                        .catch(function(error) {
                            console.error("Error loading licensee income data:", error);
                            return [];
                        });
                }
                
                function renderLicenseeFromIncomeJson(licensees) {
                    var grid2 = document.getElementById("licenseeGrid2");
                    if (!grid2) {
                        console.error("licenseeGrid2 element not found for JSON licensees!");
                        return;
                    }
                    
                    if (!licensees || !licensees.length) {
                        console.warn("No licensees from JSON to render");
                        return;
                    }
                    
                    // Append to existing grid2 content (don't clear it, add to it)
                    licensees.forEach(function(licensee) {
                        var col = document.createElement("div");
                        col.className = "col-12 col-sm-6 col-md-4 col-lg-2";
                        col.style.marginBottom = "2px";
                        col.style.padding = "2px";
                        
                        var nameBox = document.createElement("div");
                        nameBox.className = "licensee-name-box";
                        nameBox.textContent = licensee.licenseeName || licensee.name;
                        
                        // Create a ratio box - default to blue with 0/0 for now
                        var ratioBox = document.createElement("div");
                        ratioBox.className = "licensee-ratio-box licensee-box-blue";
                        ratioBox.textContent = "0/0"; // Can be customized later
                        // Initialize second number to random higher value
                        setTimeout(function() {
                            initializeRatioSecondNumber(ratioBox);
                        }, 100);
                        
                        var container = document.createElement("div");
                        container.className = "d-flex align-items-center";
                        container.style.marginBottom = "0";
                        container.appendChild(nameBox);
                        container.appendChild(ratioBox);
                        
                        col.appendChild(container);
                        grid2.appendChild(col);
                    });
                    console.log("Rendered", licensees.length, "licensees from JSON to licenseeGrid2");
                }
                
                function initializeRatioSecondNumber(ratioBox) {
                    // Check if this ratio box has been initialized (has a data attribute)
                    if (ratioBox.hasAttribute("data-initialized")) {
                        return; // Already initialized
                    }
                    
                    var currentText = ratioBox.textContent.trim();
                    var parts = currentText.split("/");
                    
                    if (parts.length === 2) {
                        // Initialize second number to a random value between 30 and 50
                        var randomSecondNum = Math.floor(Math.random() * 21) + 30; // 30 to 50
                        var firstNum = parseInt(parts[0], 10) || 0;
                        
                        ratioBox.textContent = firstNum + "/" + randomSecondNum;
                        ratioBox.setAttribute("data-initialized", "true");
                        console.log("Initialized ratio to", firstNum + "/" + randomSecondNum);
                    }
                }
                
                function updateRatioNumbers() {
                    // Find all ratio boxes
                    var ratioBoxes = document.querySelectorAll(".licensee-ratio-box");
                    console.log("Updating", ratioBoxes.length, "ratio boxes");
                    
                    ratioBoxes.forEach(function(ratioBox) {
                        // Initialize second number if not already done
                        initializeRatioSecondNumber(ratioBox);
                        
                        var currentText = ratioBox.textContent.trim();
                        // Parse the ratio (e.g., "0/42" -> first=0, second=42)
                        var parts = currentText.split("/");
                        
                        if (parts.length === 2) {
                            var firstNum = parseInt(parts[0], 10) || 0;
                            var secondNum = parseInt(parts[1], 10) || 0;
                            
                            // Add random number between 3 and 5 to BOTH numbers
                            var randomIncrement = Math.floor(Math.random() * 3) + 3; // 3, 4, or 5
                            var newFirstNum = firstNum + randomIncrement;
                            var newSecondNum = secondNum + randomIncrement;
                            
                            // Update the ratio box text
                            ratioBox.textContent = newFirstNum + "/" + newSecondNum;
                            
                            console.log("Updated ratio from", currentText, "to", newFirstNum + "/" + newSecondNum);
                        }
                    });
                }
                
                // Store interval ID to prevent multiple intervals
                var ratioUpdateInterval = null;
                
                function startRatioAutoUpdate() {
                    // Clear any existing interval
                    if (ratioUpdateInterval) {
                        clearInterval(ratioUpdateInterval);
                    }
                    
                    // Update immediately
                    updateRatioNumbers();
                    
                    // Then update every 2 minutes (120000 milliseconds)
                    ratioUpdateInterval = setInterval(updateRatioNumbers, 120000);
                    console.log("Ratio auto-update started - will update every 2 minutes");
                }
                
                function applyLicenseeGridMetrics(payload) {
                    console.log("applyLicenseeGridMetrics called with payload:", payload);
                    var gridData = payload && payload.cards && payload.cards.licenseeGrid ? payload.cards.licenseeGrid : null;
                    console.log("gridData extracted:", gridData);
                    
                    if (!gridData) {
                        console.warn("No gridData found in payload");
                        return;
                    }
                    
                    // Update summary metrics
                    setText("metricDaily", formatLicenseeNumber(gridData.daily || 2268));
                    setText("metricDaily2", formatLicenseeNumber(gridData.daily || 2268));
                    setText("metricMTD", formatLicenseeNumber(gridData.mtd || 41148));
                    setText("metricMTD2", formatLicenseeNumber(gridData.mtd || 41148));
                    setText("metricYTD", formatLicenseeNumber(gridData.ytd || 41148));
                    setText("metricYTD2", formatLicenseeNumber(gridData.ytd || 41148));
                    setText("metricALL", formatLicenseeNumber(gridData.all || 13939128));
                    setText("metricALL2", formatLicenseeNumber(gridData.all || 13939128));
                    setText("metricALV", formatLicenseeNumber(gridData.alv || 220320));
                    setText("metricALVM", formatLicenseeNumber(gridData.alvm || 205740));
                    
                    // Update date range text
                    var dateRangeText = gridData.selectedDateRange || "7";
                    if (dateRangeText === "all") {
                        setText("selectedDateRangeText", "ALL");
                    } else {
                        setText("selectedDateRangeText", dateRangeText + " Days");
                    }
                    
                    // Render licensee grid
                    if (gridData.licensees && gridData.licensees.length) {
                        console.log("Rendering", gridData.licensees.length, "licensees");
                        renderLicenseeGrid(gridData.licensees);
                    } else {
                        console.warn("No licensees found in gridData.licensees:", gridData.licensees);
                    }
                    
                    // Load and render licensees from licensee-income.json
                    loadLicenseesFromIncomeJson().then(function(licensees) {
                        console.log("Loaded", licensees.length, "licensees from licensee-income.json");
                        renderLicenseeFromIncomeJson(licensees);
                        // Start auto-update after all licensees are rendered
                        setTimeout(function() {
                            startRatioAutoUpdate();
                        }, 1000); // Wait 1 second to ensure all elements are rendered
                    });
                }

                function loadLicenseeGridMetrics() {
                    console.log("Loading licensee grid metrics...");
                    fetch("data/dashboard-metrics.json?v=" + Date.now() + "&t=" + Math.random())
                        .then(function(response) {
                            console.log("Fetch response status:", response.status);
                            if (!response.ok) {
                                throw new Error("Failed to load metrics: " + response.status);
                            }
                            return response.json();
                        })
                        .then(function(payload) {
                            console.log("Licensee grid data loaded successfully");
                            console.log("Payload structure:", payload);
                            console.log("Has cards:", !!payload.cards);
                            console.log("Has licenseeGrid:", !!(payload.cards && payload.cards.licenseeGrid));
                            if (payload.cards && payload.cards.licenseeGrid) {
                                console.log("LicenseeGrid licensees count:", payload.cards.licenseeGrid.licensees ? payload.cards.licenseeGrid.licensees.length : 0);
                            }
                            applyLicenseeGridMetrics(payload);
                        })
                        .catch(function(error) {
                            console.error("Error loading licensee grid metrics:", error);
                            var grid2 = document.getElementById("licenseeGrid2");
                            if (grid2) {
                                grid2.innerHTML = '<div class="col-12 text-center text-danger">Error: ' + error.message + '</div>';
                            }
                        });
                }

                // Date range button handlers
                function initDateRangeButtons() {
                    var buttons = document.querySelectorAll(".date-range-btn");
                    buttons.forEach(function(btn) {
                        btn.addEventListener("click", function() {
                            // Remove active class from all buttons
                            buttons.forEach(function(b) {
                                b.classList.remove("active");
                                b.style.background = "#000000 !important";
                            });
                            
                            // Add active class to clicked button
                            btn.classList.add("active");
                            btn.style.background = "#808080 !important";
                            
                            // Update text (both sections)
                            var range = btn.getAttribute("data-range");
                            var rangeText = range === "all" ? "ALL" : range + " Days";
                            setText("selectedDateRangeText", rangeText);
                            setText("selectedDateRangeText2", rangeText);
                            
                            // TODO: Filter licensees based on date range
                        });
                    });
                }

                function initLicenseeGrid() {
                    if (document.readyState === "loading") {
                        document.addEventListener("DOMContentLoaded", function() {
                            loadLicenseeGridMetrics();
                            initDateRangeButtons();
                        });
                    } else {
                        loadLicenseeGridMetrics();
                        initDateRangeButtons();
                    }
                }
                initLicenseeGrid();
            })();
        </script>

        <style>
            @keyframes pulsate {
                0%, 100% {
                    transform: scale(1);
                    opacity: 1;
                }
                50% {
                    transform: scale(1.05);
                    opacity: 0.9;
                }
            }
            
            .pulsate-number {
                animation: pulsate 2s ease-in-out infinite;
                display: inline-block;
            }
            
            .licensee-name-box {
                font-size: 11px;
                width: 164px;
                align-items: center;
                text-decoration: none;
                box-sizing: border-box;
                border: 1px solid var(--success, #1ee0ac);
                text-align: center;
                color: rgb(30, 224, 172);
                position: relative;
                overflow: hidden;
                background-color: rgba(0, 0, 0, 0.2);
                margin: 0px;
                line-height: 16.5px;
                font-weight: 500;
                letter-spacing: 0.3px;
                font-family: Montserrat, sans-serif;
                padding: 4px 8px;
                margin-right: 4px;
                border-radius: 2px;
                display: flex;
                justify-content: center;
                transition: all 0.3s ease;
                cursor: pointer;
            }
            
            .licensee-name-box:hover {
                border-color: #4df5d4;
                box-shadow: 0 0 10px rgba(30, 224, 172, 0.6), 0 0 20px rgba(30, 224, 172, 0.4);
                background-color: rgba(0, 0, 0, 0.3);
            }
            
            .licensee-ratio-box {
                padding: 4px 8px;
                border-radius: 2px;
                font-size: 12px;
                font-weight: bold;
                color: white;
                min-width: 40px;
                text-align: center;
                border: 2px solid #ff0000;
                line-height: 1.2;
            }
            
            #licenseeGrid2 .row {
                margin: 0;
            }
            
            #licenseeGrid2 [class*="col-"] {
                padding-left: 4px;
                padding-right: 4px;
            }
            
            .licensee-box-blue {
                background: #0066cc;
            }
            
            .licensee-box-green {
                background: #28a745;
            }
            
            .licensee-box-yellow {
                background: #ffc107;
                color: #000;
            }
            
            .licensee-box-pink {
                background: #e91e63;
            }
            
            .licensee-box-red {
                background: #dc3545;
            }
            
            .licensee-box-lightblue {
                background: #17a2b8;
            }
            
            .date-range-btn {
                background: #000000 !important;
                color: white !important;
                border: 1px solid white !important;
                padding: 10px 25px !important;
                font-size: 14px !important;
                font-weight: bold !important;
                transition: all 0.3s ease !important;
                margin: 0 !important;
                border-radius: 0 !important;
                cursor: pointer !important;
                transition: all 0.3s !important;
                display: inline-block !important;
                visibility: visible !important;
                opacity: 1 !important;
                min-width: 80px !important;
                text-align: center !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.5) !important;
            }
            
            .date-range-btn:hover {
                background: rgba(255, 255, 255, 0.2) !important;
                border-color: #cccccc !important;
                transform: scale(1.05) !important;
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.5), 0 0 20px rgba(255, 255, 255, 0.3) !important;
            }
            
            .date-range-btn.active {
                background: #808080 !important;
                border: 2px solid white !important;
                color: white !important;
                box-shadow: 0 0 10px rgba(255,255,255,0.5) !important;
            }
            
            .metric-card {
                border-radius: 4px;
                min-width: 70px;
                text-align: center;
            }
        </style>

    </div>
    <!-- End Dashboard Content -->

</body>
</html>
