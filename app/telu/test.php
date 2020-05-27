<?php
 session_start();
 $root_lib=$_SERVER["DOCUMENT_ROOT"];
 if (substr($root_lib,-1)!="/") {
     $root_lib=$root_lib."/";
 }
 include_once($root_lib.'app/telu/setting.php');

$kode_lokasi=$_SESSION['lokasi'];
$periode=$_SESSION['periode'];
$kode_pp=$_SESSION['kodePP'];
$nik=$_SESSION['userLog'];
$resource = $_GET["resource"];
$fullId = $_GET["fullId"];

$path = "http://".$_SERVER["SERVER_NAME"]."/";	
$poly1 = $path."image/Polygon1.png";
$poly2 = $path."image/Polygon12.png";
$group12 = $path."image/Group12.png";
$group13 = $path."image/Group13.png";
?>
<style>
    .page-wrapper{
        background:white;
    }
    .card{
        border:none;
        box-shadow:none;
    }
    /* h5{
        font-weight:bold;
        color:#ad1d3e;
        padding-left:20px;
    } */
    td,th{
        padding:4px !important;
    }
    .btn-red{
        padding: 2px 20px;
        border-radius: 15px; 
        background:#ad1d3e;
        color:white;
        border-color: #ad1d3e;
    }

    /* NAV TABS */
    .nav-tabs {
        border:none;
    }

    .nav-tabs .nav-link{
        border: 1px solid #ad1d3e;
        border-radius: 20px;
        padding: 2px 25px;
        color:#ad1d3e;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: white;
        background-color: #ad1d3e;
        border-color: #ad1d3e;
    }

    .nav-tabs .nav-item {
        margin-bottom: -1px;
        padding: 0px 10px 0px 0px;
    }
</style>

<div class="container-fluid mt-3">
    <div class="row" >
        <div class="col-6">
            <div class="card">
                <h5 style='font-weight:bold;color:#ad1d3e;padding-left:20px;'>Test Google Chart</h5>
                <div class="card-body pt-0">
                    <div id="chart_div"></div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
<script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
      
    function drawChart() {
      var jsonData = $.ajax({
          url: "<?=$root_ser?>/dashTelu.php?fx=getGrowthRKA2",
          dataType: "json",
          data:{'periode':'<?=$periode?>','param':'<?=$kode_lokasi?>|'},
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      var formatter = new google.visualization.NumberFormat({ 
          suffix: ' M',negativeParens: true,decimalSymbol:',',groupingSymbol:'.',fractionDigits:2
        });
        formatter.format(data, 1);
        formatter.format(data, 2);
        formatter.format(data, 3);

      var view = new google.visualization.DataView(data);
			 view.setColumns([0, 1,{
             calc: "stringify",
             sourceColumn: 1,
             type: "string",
             role: "annotation"
         },2,{
             calc: "stringify",
             sourceColumn: 2,
             type: "string",
             role: "annotation"
         },3,{
             calc: "stringify",
             sourceColumn: 3,
             type: "string",
             role: "annotation"
         }]);

      // Instantiate and draw our chart, passing in some options.
    //   var options = {
    //       title: '',
    //       curveType: 'function',
    //       legend: { position: 'bottom' },
    //       width:'100%'
    //   };

      var options = {
            title : '',
            vAxis: {title: ''},
            hAxis: {title: ''},
            seriesType: 'bars',
            series: {2: {type: 'line'}},
            legend: { position: 'bottom' },
            width: '100%',
            height:350,
            chartArea:{
                left:50,
                top: 40,
                width:'100%'
            },
            bar: {
                groupWidth: 80
            },
            annotations: {
                alwaysOutside: true,
                highContrast: true,  // default is true, but be sure
                textStyle: {
                    bold: true,
                    paddingBottom:30,
                    fontSize:9
                },
                stem: {
                    length: 8
                }
            }
        };

      var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
      chart.draw(view, options);
    }

</script>
