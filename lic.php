<?php
$profileId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
if ($profileId < 1) {
    $profileId = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />
    <base href="./">
    <link rel="icon" href="Scripts/AdminImg/fav2.jpg">
    <title></title>
    <meta name="description">
    <meta name="keywords">
    
    
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
    <style>
        ul {
          padding: 0;
          list-style: none;
        }

        .jvectormap-legend .jvectormap-legend-tick-sample {
          height: 26px;
        }

        .jvectormap-legend-icons {
          background: white;
          border: black 1px solid;
        }

        .jvectormap-legend-icons {
          color: black;
        }
  </style>
  <script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
  <script src="dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
  <script src="dist/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js"></script>
  <script>
    $(function(){
      var markers = [],
          values1 = [408, 512, 550, 781],
          values2 = [1, 2, 3, 4],
          values3 = {
            '4': 'bank',
            '5': 'factory'
          };

      var map = new jvm.Map({
        container: $('.map'),

        map: 'us_aea',
        backgroundColor: 'transparent',
        textColor: '#000',
        
        labels: {
          textColor: '#000',
          regions: {
            render: function(code){
              var doNotShow = ['US-RI', 'US-DC'];

              if (doNotShow.indexOf(code) === -1) {
                return code.split('-')[1];
              }
            },
            offsets: function(code){
              return {
                'CA': [-10, 10],
                'ID': [0, 40],
                'OK': [25, 0],
                'LA': [-20, 0],
                'FL': [45, 0],
                'KY': [10, 5],
                'VA': [15, 5],
                'MI': [30, 30],
                'AK': [50, -25],
                'HI': [25, 50]
              }[code.split('-')[1]];
            }
          },
          markers: {
            render: function(index){
              return 'Marker '+index;
            }
          }
        },
        markers: markers,
        series: {
          regions: [{
            scale: {
              red: '#bc0025',
              green: '#41ae75',
              purple: '#5e478f',
              pink: '#f416e5',
              yellow: '#fffe32',
              orange: '#fc4e2b'
            },
            attribute: 'fill',
            values: {
              "US-WA": 'purple',
              "US-OK": 'purple',
              "US-MT": 'red',
              "US-CO": 'red',
              "US-AZ": 'red',
              "US-AR": 'red',
              "US-NY": 'red',
              "US-PA": 'red',
              "US-MA": 'red',
              "US-CT": 'red',
              "US-WV": 'red',
              "US-NC": 'red',
              "US-GA": 'red',
              "US-VT": 'red',
              "US-NJ": 'red',
              "US-DE": 'red',
              "US-MD": 'red',

              "US-CA": 'yellow',
              "US-NV": 'yellow',
              "US-MO": 'yellow',
              "US-TX": 'yellow',

              "US-ID": 'pink',
              "US-WY": 'pink',
              "US-LA": 'pink',
              "US-AK": 'pink',
              "US-HI": 'pink',

              "US-VA": 'orange',
              "US-MS": 'orange',
              "US-AL": 'orange',
              "US-FL": 'orange',

              "US-ND": 'green',
              "US-WI": 'green',
              "US-IA": 'green',
              "US-MI": 'green',
              "US-IN": 'green',
              "US-KY": 'green',
              "US-TN": 'green',
              "US-SC": 'green',
              "US-RI": 'green',
              "US-KS": 'green'
            }
          },{
            scale: {
              redGreen: '#ff0000',
              yellowBlue: '#ffff00'
            },
            values: {
              "US-OR": 'redGreen',
              "US-UT": 'redGreen',
              "US-NM": 'redGreen',
              "US-SD": 'redGreen',
              "US-NE": 'redGreen',
              "US-MN": 'redGreen',
              "US-IL": 'redGreen',
              "US-OH": 'redGreen',
              "US-NH": 'redGreen',
              "US-ME": 'redGreen',
            },
            attribute: 'fill'
          }]
        },
        regionsSelectable: true,
        markersSelectable: true,
        markersSelectableOne: true,
        selectedRegions: JSON.parse( window.localStorage.getItem('jvectormap-selected-regions') || '[]' ),
        selectedMarkers: JSON.parse( window.localStorage.getItem('jvectormap-selected-markers') || '[]' ),

        onMarkerSelected: function(event, index, isSelected, selectedMarkers){
          console.log('marker-select', index, isSelected, selectedMarkers);
          if (window.localStorage) {
            window.localStorage.setItem(
              'jvectormap-selected-markers',
              JSON.stringify(selectedMarkers)
            );
          }
        },

        onRegionTipShow: function(event, tip, code){
            if(code == "US-AL"){
              tip.html(tip.html()+' <br>456%');
            } else if(code == "US-AK"){
              tip.html(tip.html()+' <br>520%');
            } else if(code == "US-AZ"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-AR"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-CA"){
              tip.html(tip.html()+' <br>460%');
            } else if(code == "US-CO"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-CT"){
              tip.html(tip.html()+' <br>400%');
            } else if(code == "US-DE"){
              tip.html(tip.html()+' <br>High APR');
            } else if(code == "US-FL"){
              tip.html(tip.html()+' <br>304%');
            } else if(code == "US-GA"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-HI"){
              tip.html(tip.html()+' <br>459%');
            } else if(code == "US-ID"){
              tip.html(tip.html()+' <br>652%');
            } else if(code == "US-IL"){
              tip.html(tip.html()+' <br>404%');
            } else if(code == "US-IN"){
              tip.html(tip.html()+' <br>391%');
            } else if(code == "US-IA"){
              tip.html(tip.html()+' <br>433%');
            } else if(code == "US-KS"){
              tip.html(tip.html()+' <br>391%');
            } else if(code == "US-KY"){
              tip.html(tip.html()+' <br>460%');
            } else if(code == "US-LA"){
              tip.html(tip.html()+' <br>780%');
            } else if(code == "US-ME"){
              tip.html(tip.html()+' <br>217%');
            } else if(code == "US-MD"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-MA"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-MI"){
              tip.html(tip.html()+' <br>369%');
            } else if(code == "US-MN"){
              tip.html(tip.html()+' <br>200%');
            } else if(code == "US-MS"){
              tip.html(tip.html()+' <br>391%');
            } else if(code == "US-MO"){
              tip.html(tip.html()+' <br>391%');
            } else if(code == "US-MT"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-NE"){
              tip.html(tip.html()+' <br>21%');
            } else if(code == "US-NV"){
              tip.html(tip.html()+' <br>NO CAP');
            } else if(code == "US-NH"){
              tip.html(tip.html()+' <br>36%');
            } else if(code == "US-NJ"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-NM"){
              tip.html(tip.html()+' <br>175%');
            } else if(code == "US-NY"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-NC"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-ND"){
              tip.html(tip.html()+' <br>520%');
            } else if(code == "US-OH"){
              tip.html(tip.html()+' <br>28%');
            } else if(code == "US-OK"){
              tip.html(tip.html()+' <br>395%');
            } else if(code == "US-OR"){
              tip.html(tip.html()+' <br>154%');
            } else if(code == "US-PA"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-RI"){
              tip.html(tip.html()+' <br>260%');
            } else if(code == "US-SC"){
              tip.html(tip.html()+' <br>391%');
            } else if(code == "US-SD"){
              tip.html(tip.html()+' <br>36%');
            } else if(code == "US-TN"){
              tip.html(tip.html()+' <br>459%');
            } else if(code == "US-TX"){
              tip.html(tip.html()+' <br>NO CAP');
            } else if(code == "US-UT"){
              tip.html(tip.html()+' <br>NO CAP');
            } else if(code == "US-VT"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-VA"){
              tip.html(tip.html()+' <br>687%');
            } else if(code == "US-WA"){
              tip.html(tip.html()+' <br>391% - 309%');
            } else if(code == "US-WV"){
              tip.html(tip.html()+' <br>Prohibited');
            } else if(code == "US-WI"){
              tip.html(tip.html()+' <br>NO CAP');
            } else if(code == "US-WY"){
              tip.html(tip.html()+' <br>780%');
            }
        },
        onRegionOver: function(event, code){
          console.log('region-over', code, map.getRegionName(code));
        },
        onRegionOut: function(event, code){
          console.log('region-out', code);
        },
        onRegionClick: function(event, code){
          console.log('region-click', code);
        },
        onRegionSelected: function(event, code, isSelected, selectedRegions){
          console.log('region-select', code, isSelected, selectedRegions);
          if (window.localStorage) {
            window.localStorage.setItem(
              'jvectormap-selected-regions',
              JSON.stringify(selectedRegions)
            );
          }
        },
        onViewportChange: function(e, scale, transX, transY){
            console.log('viewportChange', scale, transX, transY);
        }
      });

      $('.list-markers :checkbox').change(function(){
        var index = $(this).closest('li').attr('data-marker-index');
        if ($(this).prop('checked')) {
          map.addMarker( index, markers[index], [values1[index], values2[index], values3[index]] );
        } else {
          map.removeMarkers( [index] );
        }
      });
      $('.button-add-all').click(function(){
        $('.list-markers :checkbox').prop('checked', true);
        map.addMarkers(markers, [values1, values2, values3]);
      });
      $('.button-remove-all').click(function(){
        $('.list-markers :checkbox').prop('checked', false);
        map.removeAllMarkers();
      });
      $('.button-clear-selected-regions').click(function(){
        map.clearSelectedRegions();
      });
      $('.button-clear-selected-markers').click(function(){
        map.clearSelectedMarkers();
      });
      $('.button-remove-map').click(function(){
        map.remove();
      });
      $('.button-change-values').click(function(){
        map.series.regions[1].clear();
        map.series.regions[1].setValues({
          "US-TX": "black",
          "US-CA": "black"
        });
      });
      $('.button-reset-map').click(function(){
        map.reset();
      });
    });
  </script>

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
            padding-top: 5px;
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
            display:none;
        }
        @media screen and (max-width: 500px) {
            #mobileshow {
                display:block; 
            }
        }
}
</style>
</head>

<body id="main-container" class="default gradient">
    <div class="se-pre-con">
        <div class="loader"></div>
    </div>
    <div class="sidebar">
        <div class="site-width">
            
<img src="Scripts/AdminImg/taxcash-liclogo2.jpg" width="100%">
<ul id="side-menu" class="sidebar-menu">
    <li class="dropdown active">
            <span>Welcome Back</span> <br />
            <span class="neons" style="font-size: 16px;"><a href="https://titanedgeusa.com" id="lic_websiteName">TitanEdgeUSA.Com</a></span><br />
            <span style="color: red;"><b id="lic_licenseeName"> Salim Popatiya</b></span>
        <ul>
            <li class="active"><a href="/LicAdmin/LicenReportA2?licenseeId=32&year=2025&territoryId=-1"><i class="icon-rocket"></i> Dashboard</a></li>
            <li><a href="/LicAdmin/loansB?licenseeId=32&year=2025&territoryId=-1"><i class="icon-star"></i> Loans</a></li>
            <li><a href="/LicAdmin/LoanFeesA2?licenseeId=32&year=2025&territoryId=-1"><i class="icon-layers"></i> Loan Fees</a></li>
            <li><a href="/LicAdmin/WriteOffOverview?licenseeId=32&year=2025&territoryId=-1"><i class="icon-pencil"></i> Written Off Loans</a></li>
            <li><a href="/LicAdmin/dashboard/"><i class="icon-power"></i> Licensee Dashboards</a></li>
        </ul>
    </li>
</ul>
<br>
<center>
</center>
            <ol class="breadcrumb bg-transparent align-self-center m-5 p-0 ml-auto">
                <li class="breadcrumb-item"><a href="#">Application</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
        <div class="sidebar-footer">
            <button id="btnLicenseeReportDownload" type="button" class="btn btn-success">Download Report</button>
        </div>
    </div>

    <div class="row col-lg-12" id="mobileshow">
        <div class="col-8">
            <div id="mobileshow" style="margin-top:10px;">
                <img src="dist/images/lion-logo2.png" width="150%">
            </div>
            <div class="navbar-header h4 mb-0 text-right h-100 collapse-menu-bar" style="margin-top:10px; margin-right:0px;">
                <a href="#" class="sidebarCollapse" id="collapse"><i class="icon-menu"></i></a>
            </div>
        </div>
        <div class="col-2">
        </div>
    </div>

    
<main>
    <div class="container-fluid site-width">
        <!-- START: Card Data-->
        <div class="row">
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
                                                    <span class="neons" style="font-size: 24px;" id="lic_availableBalance">$0.00</span> <br />
                                                    <span>Payment In Progress: <span id="lic_paymentInProgress">$0.00</span></span> <br />
                                                    <span data-toggle="tooltip" data-placement="left" title="Available Balance And Payment In Progress">Account Balance: <span id="lic_accountBalance">$0.00</span></span> <br />
                                                    <span data-toggle="tooltip" data-placement="left" title="Total Transfers Balance">Transfers Balance: <span id="lic_transfersBalance">$0.00</span></span> <br />
                                                    <span data-toggle="tooltip" data-placement="left" title="Available Balance,  Capital Balance and Total Transfers Balance">Cumulative Balance: <span id="lic_cumulativeBalance">$0.00</span></span>
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
                                                <h1 class="card-liner-title text-white">Capital Account</h1>
                                                <h1 class="card-liner-subtitle text-white">
                                                    <span class="neons" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Capital Disbursed"> <span id="lic_capitalDisbursed">$0.00</span></span> /  
                                                    <span class="neons" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Total Capital"><span id="lic_totalCapital">$0.00</span></span><br />
                                                    <span>Capital Balance: <span id="lic_capitalBalance">$0.00</span></span><br/>
                                                    <span data-toggle="tooltip" data-placement="left" data-html="true" title="Transfers In Selected Period">
                                                        Transfers: <span id="lic_capitalTransfers">$0.00</span>
                                                    </span>
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 mt-3">
                                <div class="card h-100 glow3">
                                    <div class="card-body">
                                        <div class='d-flex px-0 px-lg-2 py-2 align-self-center'>
                                            <i class="icon-check icons card-liner-icon mt-2 text-white"></i>
                                            <div class='card-liner-content' style="width:100%">
                                                <div class="row">
                                                    <div class="col-7 col-xxl-8 col-xl-8 col-lg-12" style="padding-top:10px">
                                                        <h1 class="card-liner-title text-white"><span data-toggle="tooltip" data-placement="left" title="ROI For Selected Period">ROI</span></h1>
                                                    </div>
                                                    <div class="col-5 col-xxl-4 col-xl-4 col-lg-12">
                                                        <div class="dropdownlist">
                                                            <select class="form-control" data-validate="required" id="SelectedYear" name="SelectedYear" onchange="redirectToSelectedYear(this.value)" style="width:90px"><option selected="selected" value="2025">2025</option>
</select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="neons" style="font-size: 24px; padding-bottom:20px" id="lic_roiPercentage">0.00%</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h1 class="card-liner-subtitle text-white" style="border-bottom:0px solid var(--bordercolor);padding-bottom: 20px;}">
                                                            <span>
                                                                Interest Earned: <span id="lic_interestEarned">$0.00</span>
                                                            </span><br>
                                                            <span>
                                                                Late Interest & Late Fees Earned: <span id="lic_lateInterestFeesEarned">$0.00</span>
                                                            </span>
                                                        </h1>
                                                    </div>
                                                </div>
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
                                            <div class="card-liner-content">
                                                <div class="row">
                                                    <div class="col-12 col-xxl-12 col-xl-12 col-lg-12" style="padding-top:10px">
                                                        <h1 class="card-liner-title text-white"><span data-toggle="tooltip" data-placement="left" title="ROI Based On Initial Funding">Projected ROI</span></h1>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="neons" style="font-size: 24px; padding-bottom:20px" id="lic_projectedRoi">0.00%</span>
                                                    </div>
                                                </div>
                                                    <div class="row">
                                                        <div class="col-12 col-xxl-12 col-xl-12 col-lg-12">
                                                            <h1 class="card-liner-title text-white"><span data-toggle="tooltip" data-placement="left" title="ROI Annualized For Current Year">Annualized ROI</span></h1>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span class="neons" style="font-size: 24px; padding-bottom:20px" id="lic_annualizedRoi">0.00%</span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-12 col-lg-6 mt-3">
                <div class="card glow3">
                    <div class="card-header  justify-content-between align-items-center" style="border-bottom:0px">
                        <div class="row">
                            <div class="col col-lg-6">
                                <h6 class="card-title neons">Loan Graph</h6>
                            </div>
                            <div class="col col-lg-6 text-right" style="position: absolute; right:1px; width:250px;">
                                Total Loans Today
                            </div>
                            <div class="col col-lg-6 text-right">
                                <span class="badge outline-badge-danger neons" style="position: absolute; font-size:45px; right:50px; top:30px; width:70px;">0</span>
                            </div>
                        </div>
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

                        /* Custom tooltip styles */
                        .tooltip .tooltip-inner {
                            background-color: #343a40;
                            border: 2px solid #007bff;
                            color: #ffffff;
                        }

                        .tooltip .arrow::before {
                            border-left-color: #007bff;
                        }

                        .tooltip-inner {
                            max-width: none !important;
                        }
                        /* End Custom tooltip styles */
                    </style>
                    <!-- Resources -->
                    <script src="https://cdn.amcharts.com/lib/4/core.js" crossorigin="anonymous"></script>
                    <script src="https://cdn.amcharts.com/lib/4/charts.js" crossorigin="anonymous"></script>
                    <script src="https://cdn.amcharts.com/lib/4/themes/dark.js" crossorigin="anonymous"></script>
                    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js" crossorigin="anonymous"></script>

                    <!-- Chart code -->
                    <script>
                        function loadLicLoanGraphData() {
                            var profileId = <?php echo $profileId; ?>;
                            fetch("data/lic-" + profileId + "-loan-graph.json?v=" + Date.now())
                                .then(function(response) {
                                    if (!response.ok) {
                                        throw new Error("Failed to load loan graph data");
                                    }
                                    return response.json();
                                })
                                .then(function(payload) {
                                    var loandata = payload.loans || [];
                                    if (loandata.length > 0) {
                                        // Remove first item (Total Loans) for chart, keep for table
                                        var chartData = loandata.slice(1);
                                        renderLicLoanChart(chartData);
                                        renderLicLoanTable(loandata);
                                    } else {
                                        console.error("No loan data found");
                                    }
                                })
                                .catch(function(error) {
                                    console.error("Error loading loan graph data:", error);
                                });
                        }

                        function renderLicLoanChart(loandata) {
                            am4core.ready(function () {
                            // Themes begin
                            am4core.useTheme(am4themes_dark);
                            am4core.useTheme(am4themes_animated);
                            // Themes end

                            var chart = am4core.create("chartdiv", am4charts.PieChart);
                            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

                            chart.data = loandata;
                            chart.radius = am4core.percent(70);
                            chart.innerRadius = am4core.percent(40);
                            chart.startAngle = 180;
                            chart.endAngle = 360;

                            var series = chart.series.push(new am4charts.PieSeries());
                            series.dataFields.value = "varietyPercent";
                            series.dataFields.category = "particularTitle";
                            series.colors.list = [
                                am4core.color("gold"),
                                am4core.color("darkturquoise"),
                                am4core.color("blueviolet"),
                                am4core.color("dodgerblue")

                            ];
                            series.slices.template.cornerRadius = 10;
                            series.slices.template.innerCornerRadius = 7;
                            series.slices.template.draggable = true;
                            series.slices.template.inert = true;
                            series.alignLabels = false;

                            series.hiddenState.properties.startAngle = 90;
                            series.hiddenState.properties.endAngle = 90;

                            chart.legend = new am4charts.Legend();

                            }); // end am4core.ready()
                        }

                        function renderLicLoanTable(loandata) {
                            var tbody = document.getElementById('lic-loan-table-body');
                            if (!tbody) return;

                            tbody.innerHTML = '';
                            loandata.forEach(function(loan) {
                                var row = document.createElement('tr');
                                row.innerHTML = 
                                    '<td><span data-toggle="tooltip" data-placement="left">' + (loan.particularTitle || '') + '</span></td>' +
                                    '<td class="text-warning">' + (loan.itemCount || 0).toLocaleString() + '</td>' +
                                    '<td class="text-info">$' + (loan.disbursedAmount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                                    '<td class="text-info">$' + (loan.interestAmount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                                    '<td class="text-info">$' + (loan.totalAmount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                                    '<td class="text-info">$' + (loan.collectAmount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                                    '<td class="text-info">$' + (loan.activeCollection || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                                    '<td class="text-success">' + (loan.varietyPercent || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '%</td>';
                                tbody.appendChild(row);
                            });
                        }

                        // Load loan graph data on page load
                        if (document.readyState === "loading") {
                            document.addEventListener("DOMContentLoaded", loadLicLoanGraphData);
                        } else {
                            loadLicLoanGraphData();
                        }
                    </script>

                    <!-- HTML -->
                    <div id="chartdiv"></div>
                </div>
            </div>

            <div class="col-6 col-md-12 col-lg-6 mt-3">
                <div class="card h-100 glow3">
                        <div class="map ml-4" style="width: 550px; height: 350px; margin:auto"></div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12 mt-3">
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
                                    <th>Active Collection</th>
                                    <th>% of Total Loans</th>
                                </tr>
                            </thead>
                            <tbody id="lic-loan-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-6 mt-3">
                <div class="card overflow-hidden glow3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title neons">Funding</h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body p-0">
                            <ul class="list-group list-unstyled">
                                <li class="p-2 border-bottom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-success">Capital Provided Up To Today</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Money Sent So Far For Investment.</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_funding_capitalProvided">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom">
                                    <div class="media d-flex w-100">

                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-success">Minimum Capital Require For Lending</span>
                                            <p class="mb-0 font-w-500 tx-s-12">Capital As Specified In The Agreement.</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_funding_minimumCapitalRequired">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-success">Excess/(Short)</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Any Excess Or Shortfall In Capital.</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_funding_excessShort">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card overflow-hidden mt-3">
                    <div class="card h-100 glow3" style="height: 500px!important">
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
                                        <th>Total Debt</th>
                                        <th>% of Total Loans</th>
                                    </tr>
                                </thead>
                                <tbody id="lic-aging-table-body">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td>$125,776.00</td>
                                        <td></td>
                                        <td></td>
                                        <td>$129,095.60</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-6 mt-3">
                <div class="card h-100 overflow-hidden glow3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title neons">Projections</h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body p-0">
                            <ul class="list-group list-unstyled">
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">1. Assuming Money Has Been Rotated As Specified Below</span>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                    <span class="icon-pencil mr-2 h6 mb-0"></span> View Loan
                                                </a>
                                                <a href="" class="dropdown-item px-2 align-self-center d-flex">
                                                    <span class="icon-user mr-2 h6 mb-0"></span> View Fees
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">Investment</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Current Amount Of Money Invested Into Loans.</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_investment">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">Turnover Of Money</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Number Of Times Money Is Expected To Be Rotated</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_turnoverOfMoney">0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">Money Available For Loan Disbursement</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Total Cash Generated For Lending</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_moneyAvailableForLoanDisbursement">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">Estimated Earning Of Interest</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Interest Earning Based On Projected Loan Disbursement</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_estimatedEarningOfInterest">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">2.Estimated Bad Debts</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Loans That Are Bad Debts.</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_estimatedBadDebts">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">

                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">3.Assuming We Receive All The Money That Has Been Currently Lent Out</span><br>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">

                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">Estimated Interest</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Interest Earning With Current Investment</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_estimatedInterest">$0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="p-2 border-bottom zoom">
                                    <div class="media d-flex w-100">
                                        <div class="media-body align-self-center pl-2">
                                            <span class="mb-0 font-w-600 text-warning">Projected ROI</span><br>
                                            <p class="mb-0 font-w-500 tx-s-12">Projecting Our Loans Are Getting Paid</p>
                                        </div>
                                        <div class="ml-auto my-auto">
                                            <a href="#" data-toggle="dropdown">
                                                <span id="lic_projections_projectedRoi">0.00%</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
</main>


<a href="#" class="scrollup text-center">
    <i class="icon-arrow-up"></i>
</a>





<br /><br />

    <footer class="site-footer" style="cursor: pointer;" onclick="window.location.href='admin-lic.php?id=<?php echo $profileId; ?>'">
    Copyright @ 2025 TaxCash Corp.
</footer>

    

    <script src="dist/vendors/jquery-ui/jquery-ui.min.js"></script>
    <script src="dist/vendors/moment/moment.js"></script>
    <script src="dist/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/vendors/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="dist/js/app.js"></script>
    <script type="text/javascript">
        function redirectToSelectedYear(selectedValue) {
             const url = new URL(window.location.href);
             url.searchParams.set("year", selectedValue);
             window.location.href = url.href;
        }
    </script>


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
        $(document).ready(function () {
            $("#btnLicenseeReportDownload").click(function (e) {
                e.preventDefault();
                if (true) {
                    var selectedYear = $('#SelectedYear').val();
                    if (selectedYear == 0) {
                        alert('Invalid Year');
                        return;
                    }
                    var selectedTerritory = $('#territoryCodes').val() ?? -1;
                    var downloadUrl = `/excel/GetLicenseeReport?licenseeId=${32}&territoryId=${selectedTerritory}&year=${selectedYear}`;
                    var link = document.createElement("a");
                    link.download = name;
                    link.href = downloadUrl;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    delete link;
                } else {
                    window.location.href = `/excel/LicenseeReport?licenseeId=32&year=2025`;
                }
            });

            $('#territoryCodes').change(function () {
                var selectedYear = $('#SelectedYear').val();
                var newUrl = `${window.location.pathname}?licenseeId=${32}&territoryId=${$(this).val()}&year=${ selectedYear }`;
                window.location.href = newUrl;
            });

            function selectTerritory() {
                $("#territoryCodes").val(-1);
            }

            selectTerritory();
        });

        // Load License Metrics Data
        (function() {
            function formatCurrencyValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '$0.00';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '$0.00';
                }
                return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function formatPercentageValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '0.00%';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '0.00%';
                }
                return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%';
            }

            function setText(id, text) {
                var element = document.getElementById(id);
                if (element) {
                    element.textContent = text;
                }
            }

            function applyLicMetrics(payload) {
                if (!payload) {
                    console.error('No data provided to applyLicMetrics');
                    return;
                }

                var licensee = payload.licensee || {};
                var availableBalance = payload.availableBalance || {};
                var capitalAccount = payload.capitalAccount || {};
                var roi = payload.roi || {};
                var projectedRoi = payload.projectedRoi || {};

                if (licensee.websiteName) {
                    var websiteLink = document.getElementById('lic_websiteName');
                    if (websiteLink) {
                        websiteLink.textContent = licensee.websiteName;
                    }
                }
                if (licensee.licenseeName) {
                    setText("lic_licenseeName", licensee.licenseeName);
                }

                setText("lic_availableBalance", formatCurrencyValue(availableBalance.available));
                setText("lic_paymentInProgress", formatCurrencyValue(availableBalance.paymentInProgress));
                setText("lic_accountBalance", formatCurrencyValue(availableBalance.accountBalance));
                setText("lic_transfersBalance", formatCurrencyValue(availableBalance.transfersBalance));
                setText("lic_cumulativeBalance", formatCurrencyValue(availableBalance.cumulativeBalance));

                setText("lic_capitalDisbursed", formatCurrencyValue(capitalAccount.capitalDisbursed));
                setText("lic_totalCapital", formatCurrencyValue(capitalAccount.totalCapital));
                setText("lic_capitalBalance", formatCurrencyValue(capitalAccount.capitalBalance));
                setText("lic_capitalTransfers", formatCurrencyValue(capitalAccount.transfers));

                setText("lic_roiPercentage", formatPercentageValue(roi.percentage));
                setText("lic_interestEarned", formatCurrencyValue(roi.interestEarned));
                setText("lic_lateInterestFeesEarned", formatCurrencyValue(roi.lateInterestFeesEarned));

                setText("lic_projectedRoi", formatPercentageValue(projectedRoi.projected));
                setText("lic_annualizedRoi", formatPercentageValue(projectedRoi.annualized));
            }

            function loadLicMetrics() {
                var profileId = <?php echo $profileId; ?>;
                fetch("data/lic-" + profileId + "-metrics.json?v=" + Date.now())
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error("Failed to load license metrics");
                        }
                        return response.json();
                    })
                    .then(function(payload) {
                        applyLicMetrics(payload);
                    })
                    .catch(function(error) {
                        console.error("Error loading license metrics:", error);
                        var errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger';
                        errorDiv.style.position = 'fixed';
                        errorDiv.style.top = '10px';
                        errorDiv.style.right = '10px';
                        errorDiv.style.zIndex = '9999';
                        errorDiv.textContent = 'Error loading license metrics data. Please check data/lic-' + profileId + '-metrics.json';
                        document.body.appendChild(errorDiv);
                    });
            }

            function initLicMetrics() {
                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", loadLicMetrics);
                } else {
                    loadLicMetrics();
                }
            }
            initLicMetrics();
        })();

        // Load Funding Data
        (function() {
            function formatCurrencyValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '$0.00';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '$0.00';
                }
                return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function setText(id, text) {
                var element = document.getElementById(id);
                if (element) {
                    element.textContent = text;
                }
            }

            function applyLicFunding(payload) {
                if (!payload || !payload.funding) {
                    return;
                }
                var funding = payload.funding;
                setText("lic_funding_capitalProvided", formatCurrencyValue(funding.capitalProvided));
                setText("lic_funding_minimumCapitalRequired", formatCurrencyValue(funding.minimumCapitalRequired));
                setText("lic_funding_excessShort", formatCurrencyValue(funding.excessShort));
            }

            function loadLicFunding() {
                var profileId = <?php echo $profileId; ?>;
                fetch("data/lic-" + profileId + "-funding.json?v=" + Date.now())
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error("Failed to load funding data");
                        }
                        return response.json();
                    })
                    .then(function(payload) {
                        applyLicFunding(payload);
                    })
                    .catch(function(error) {
                        console.error("Error loading funding data:", error);
                    });
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", loadLicFunding);
            } else {
                loadLicFunding();
            }
        })();

        // Load Aging Analysis Data
        (function() {
            function formatCurrencyValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '$0.00';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '$0.00';
                }
                return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function formatPercentageValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '0.00%';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '0.00%';
                }
                return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%';
            }

            function renderLicAgingTable(agingData) {
                var tbody = document.getElementById('lic-aging-table-body');
                if (!tbody) return;

                tbody.innerHTML = '';
                agingData.forEach(function(row) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = 
                        '<td>' + (row.label || '') + '</td>' +
                        '<td class="text-warning">' + formatCurrencyValue(row.loanDisbursement) + '</td>' +
                        '<td class="text-secondary">' + formatCurrencyValue(row.interest) + '</td>' +
                        '<td class="text-secondary">' + formatCurrencyValue(row.partialPayment) + '</td>' +
                        '<td class="text-secondary">' + formatCurrencyValue(row.totalDebt) + '</td>' +
                        '<td class="text-success">' + formatPercentageValue(row.percent) + '</td>';
                    tbody.appendChild(tr);
                });
            }

            function loadLicAging() {
                var profileId = <?php echo $profileId; ?>;
                fetch("data/lic-" + profileId + "-aging.json?v=" + Date.now())
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error("Failed to load aging data");
                        }
                        return response.json();
                    })
                    .then(function(payload) {
                        if (payload && payload.aging) {
                            renderLicAgingTable(payload.aging);
                        }
                    })
                    .catch(function(error) {
                        console.error("Error loading aging data:", error);
                    });
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", loadLicAging);
            } else {
                loadLicAging();
            }
        })();

        // Load Projections Data
        (function() {
            function formatCurrencyValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '$0.00';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '$0.00';
                }
                return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function formatPercentageValue(value) {
                if (value === null || value === undefined || value === '') {
                    return '0.00%';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '0.00%';
                }
                return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%';
            }

            function formatNumberValue(value, decimals) {
                if (value === null || value === undefined || value === '') {
                    return '0.00';
                }
                var num = parseFloat(value);
                if (isNaN(num)) {
                    return '0.00';
                }
                return num.toLocaleString('en-US', { minimumFractionDigits: decimals || 2, maximumFractionDigits: decimals || 2 });
            }

            function setText(id, text) {
                var element = document.getElementById(id);
                if (element) {
                    element.textContent = text;
                }
            }

            function applyLicProjections(payload) {
                if (!payload || !payload.projections) {
                    return;
                }
                var projections = payload.projections;
                setText("lic_projections_investment", formatCurrencyValue(projections.investment));
                setText("lic_projections_turnoverOfMoney", formatNumberValue(projections.turnoverOfMoney, 2));
                setText("lic_projections_moneyAvailableForLoanDisbursement", formatCurrencyValue(projections.moneyAvailableForLoanDisbursement));
                setText("lic_projections_estimatedEarningOfInterest", formatCurrencyValue(projections.estimatedEarningOfInterest));
                setText("lic_projections_estimatedBadDebts", formatCurrencyValue(projections.estimatedBadDebts));
                setText("lic_projections_estimatedInterest", formatCurrencyValue(projections.estimatedInterest));
                setText("lic_projections_projectedRoi", formatPercentageValue(projections.projectedRoi));
            }

            function loadLicProjections() {
                var profileId = <?php echo $profileId; ?>;
                fetch("data/lic-" + profileId + "-projections.json?v=" + Date.now())
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error("Failed to load projections data");
                        }
                        return response.json();
                    })
                    .then(function(payload) {
                        applyLicProjections(payload);
                    })
                    .catch(function(error) {
                        console.error("Error loading projections data:", error);
                    });
            }

            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", loadLicProjections);
            } else {
                loadLicProjections();
            }
        })();
    </script>
</body>
</html>

