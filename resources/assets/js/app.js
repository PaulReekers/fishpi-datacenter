function fishtankGaugeData() {
   $.ajax({
      url: 'api/v1/drawgauge',
      dataType: 'json',
      success: function( data ) {
        var air = data.air;
        var water = data.water;
        drawGauge(air, water);
      },
      error: function( req, status, err ) {
        console.log( 'something went wrong', status, err );
      }
    });
}

google.charts.load('current', {'packages':['gauge', 'line']});
google.charts.setOnLoadCallback(fishtankGaugeData);
//google.charts.setOnLoadCallback(fishtankChartData);

var gaugeOptions = {min: 0, max: 50, yellowFrom: 28, yellowTo: 32,
    redFrom: 32, redTo: 50, minorTicks: 5};
var gauge;

function drawGauge(air, water) {
  gaugeData = new google.visualization.DataTable();
  gaugeData.addColumn('number', 'Water');
  gaugeData.addColumn('number', 'Air');
  gaugeData.addRows(2);
  gaugeData.setCell(0, 0, (water / 1000) );
  gaugeData.setCell(0, 1, (air / 1000) );

  gauge = new google.visualization.Gauge(document.getElementById('gauge_div'));
  gauge.draw(gaugeData, gaugeOptions);
}

$( document ).ready(function() {
  // This command is used to initialize some elements and make them work properly
  $.material.init();
});
