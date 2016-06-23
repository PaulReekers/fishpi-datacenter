// Set date format to Dutch
moment.locale('nl');

function fishtankGaugeData() {
   $.ajax({
      url: 'api/v1/current',
      dataType: 'json',
      success: function( data ) {
        current( data );
      },
      error: function( req, status, err ) {
        console.log( 'creating gauge data went wrong', status, err );
      }
    });
}


function fishtankChartData() {
   $.ajax({
      url: 'api/v1/collection',
      dataType: 'json',
      success: function( data ) {
        drawChart( data );
      },
      error: function( req, status, err ) {
        console.log( 'Creating chartData went wrong', status, err );
      }
    });
}

function current( data ) {
    Highcharts.setOptions({
        chart: {
            type: 'gauge',
            backgroundColor:'rgba(255, 255, 255, 0.0)',
        },

        title: {
            text: ''
        },

        credits: {
            enabled: false
        },

        exporting: {
            enabled: false
        },
        pane: {
            startAngle: -150,
            endAngle: 150,
            background: [{
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#FFF'],
                        [1, '#333']
                    ]
                },
                borderWidth: 0,
                outerRadius: '109%'
            }, {
                backgroundColor: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0, '#333'],
                        [1, '#FFF']
                    ]
                },
                borderWidth: 0,
                outerRadius: '0%'
            }, {
                // default background
            }, {

            }]
        },
    });

    $('.draw-gauge-water').highcharts({
        yAxis: {
            min: 0,
            max: 50,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },

            title: {
                text: 'Water °C'
            },

            plotBands: [{
                from: 0,
                to: data.alarmtemp / 1000,
                color: '#55BF3B' // green
            }, {
                from: data.alarmtemp / 1000,
                to: data.criticaltemp/1000,
                color: '#DDDF0D' // yellow
            }, {
                from: data.criticaltemp / 1000,
                to: 50,
                color: '#DF5353' // red
            }]
        },

        series: [{
            name: 'Water',
            data: [data.water / 1000],
            tooltip: {
                valueSuffix: '°C'
            }
        }]

    });

    $('.draw-gauge-air').highcharts({
        yAxis: {
            min: 0,
            max: 50,

            minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 30,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto'
            },
            title: {
                text: 'Air °C'
            },
            plotBands: [{
                from: 0,
                to: data.alarmtemp / 1000,
                color: '#55BF3B' // green
            }, {
                from: data.alarmtemp / 1000,
                to: data.criticaltemp / 1000,
                color: '#DDDF0D' // yellow
            }, {
                from: data.criticaltemp / 1000,
                to: 50,
                color: '#DF5353' // red
            }]
        },

        series: [{
            name: 'Air',
            data: [data.air / 1000],
            tooltip: {
                valueSuffix: '°C'
            }
        }]

    });
}

function drawChart( chartData ) {

    var fishDataTime = [];
    var fishDataAir = [];
    var fishDataWater = [];

    chartData.data.forEach( function( item ) {
         fishDataTime.push( [moment(item.time).format('LTS')] );
    });
    chartData.data.forEach( function( item ) {
         fishDataAir.push( [item.air / 1000] );
    });
    chartData.data.forEach( function( item ) {
         fishDataWater.push( [item.water / 1000] );
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
            categories: fishDataTime,
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
        exporting: {
            enabled: true
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
            color: '#f7a35c',
        }, {
            name: 'Water',
            data: fishDataWater,
            color: '#7cb5ec',
        }]
    });
}

setInterval(function() {
    fishtankGaugeData();
    fishtankChartData();
}, 60000);

$( document ).ready(function() {
  // This command is used to initialize some elements and make them work properly
  $.material.init();

  fishtankGaugeData();
  fishtankChartData();
});
