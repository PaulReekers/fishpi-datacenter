function fishtankGaugeData() {

   $.ajax({
      url: 'api/v1/drawgauge',
      dataType: 'json',
      success: function( data ) {
        drawGauge( data );
      },
      error: function( req, status, err ) {
        console.log( 'creating drawGauge went wrong', status, err );
      }
    });
}

function fishtankChartData() {

   $.ajax({
      url: 'api/v1/drawlinechart',
      dataType: 'json',
      success: function( data ) {
        drawChart( data );
      },
      error: function( req, status, err ) {
        console.log( 'creating chartData went wrong', status, err );
      }
    });
}


google.charts.load('current', {'packages':['gauge', 'line'], 'callback': drawCharts});

function drawCharts() {
  fishtankGaugeData();
  fishtankChartData();
}

function drawGauge( data ) {

    var gaugeOptions = {
        min: 0,
        max: 50,
        yellowFrom: (data.alarmtemp/1000),
        yellowTo: (data.criticaltemp/1000),
        redFrom: (data.criticaltemp/1000),
        redTo: 50,
        minorTicks: 5,
        animation: 500,
        width:380
    };
    var gauge;

    gaugeData = new google.visualization.DataTable();
    gaugeData.addColumn('number', 'Water');
    gaugeData.addColumn('number', 'Air');
    gaugeData.addRows(2);
    gaugeData.setCell(0, 0, (data.water / 1000) );
    gaugeData.setCell(0, 1, (data.air / 1000) );

    gauge = new google.visualization.Gauge( document.getElementById('gauge-div') );
    gauge.draw( gaugeData, gaugeOptions );
}

function drawChart( chartData ) {

    var chartOptions = {
        height: 300
    };
    var linechart;
    var fishData = [];

    chartData.data.forEach( function( item ) {
        fishData.push ( [item.time, (item.water/1000), (item.air/1000)] );
    });

    fishData.reverse();

    linechart = new google.visualization.DataTable();
    linechart.addColumn('string');
    linechart.addColumn('number', 'Water');
    linechart.addColumn('number', 'Air');

    linechart.addRows( fishData );

    var chart = new google.charts.Line( document.getElementById('linechart-div') );

    chart.draw( linechart, chartOptions );
}


$( document ).ready(function() {
  // This command is used to initialize some elements and make them work properly
  $.material.init();


  setInterval(function(){
    fishtankGaugeData();
    fishtankChartData();
  }, 60000);

});
