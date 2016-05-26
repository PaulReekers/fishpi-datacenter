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

    var fishDataTime = [];
    var fishDataAir = [];
    var fishDataWater = [];

    chartData.data.forEach( function( item ) {
         fishDataTime.push( [item.time]);
    });
    chartData.data.forEach( function( item ) {
         fishDataAir.push( [item.air/1000]);
    });
    chartData.data.forEach( function( item ) {
         fishDataWater.push( [item.water/1000]);
    });

    fishDataTime.reverse();
    fishDataAir.reverse();
    fishDataWater.reverse();

    $('.draw-linechart').highcharts({
        chart: {
            type: 'spline',
            zoomType: 'x'
        },
        title: {
            text: 'Temperature Office FishTank',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: FishPi Datacenter',
            x: -20
        },
        credits: {
            enabled: false
        },
        xAxis: {
            categories: fishDataTime
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            },
            plotBands: [{ // Light air
                from: 25.5,
                to: 26.5,
                color: 'rgba(68, 170, 213, 0.1)',
                label: {
                    style: {
                        color: '#606060'
                    }
                }
            }]
        },
        tooltip: {
            valueSuffix: '°C'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Air',
            data: fishDataAir,
            color: '#1ca3ec',
        }, {
            name: 'Water',
            data: fishDataWater,
            color: '#4285f4',
        }]
    });
}


$( document ).ready(function() {
  // This command is used to initialize some elements and make them work properly
  $.material.init();

  setInterval(function(){
    fishtankGaugeData();
    fishtankChartData();
  }, 60000);

});
