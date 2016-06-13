@extends('layouts-admin.master')
@section('meta-title')
    <title>Sync-Account Merchant - Super Administrator AbbyCard</title>
@stop
@section('css')
@stop
@section('title')
<div class="title-pages">
    <h2>Sync-Account Merchant</h2>
</div>
@stop
@section('content')
<div class="account-manage-container">
KPIS
<center>
    <div id="container"></div>
</center>
</div>
@stop
@section('js')
<script src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    $(function () {
    var gd = function (year, month, day) {
        return new Date(year, month - 1, day).getTime();
    }

    var hiddenXAxis = function () {
        return {
            type: 'datetime',
            labels: {
                enabled: false // Remove the label
            },
            tickWidth: 0, // Remove the ticks
            lineWidth: 0 // Remove the axis line
        };
    }
    
    var data1 = [
        [gd(2014, 1, 1), 4],
        [gd(2014, 2, 1), 8],
        [gd(2014, 3, 1), 4],
        [gd(2014, 4, 1), 10],
        [gd(2014, 5, 1), 4],
        [gd(2014, 6, 1), 16],
        [gd(2014, 7, 1), 15]
    ];

    var data2 = [
        [gd(2013, 1, 1), 3],
        [gd(2013, 2, 1), 5],
        [gd(2013, 3, 1), 3],
        [gd(2013, 4, 1), 11],
        [gd(2013, 5, 1), 4],
        [gd(2013, 6, 1), 13],
        [gd(2013, 7, 1), 9],
        [gd(2013, 8, 1), 5],
        [gd(2013, 9, 1), 2],
        [gd(2013, 10, 1), 3],
        [gd(2013, 11, 1), 2],
        [gd(2013, 12, 1), 1]
    ];

    var data3 = [
        [gd(2015, 1, 1), 3],
        [gd(2015, 2, 1), 2],
        [gd(2015, 3, 1), 3],
        [gd(2015, 4, 1), 7],
        [gd(2015, 5, 1), 4],
        [gd(2015, 6, 1), 11],
        [gd(2015, 7, 1), 3],
        [gd(2015, 8, 1), 4],
        [gd(2015, 9, 1), 5],
        [gd(2015, 10, 1), 3],
        [gd(2015, 11, 1), 2],
        [gd(2015, 12, 1), 6]
    ];

    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'Thống kê toàn bộ các loại Merchant'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        yAxis: {
            title: {
                text: 'Số lượng Merchant'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' merchant'
        },
        credits: {
            enabled: false
        },
        colors: ['#1ab394', '#464f88', '#ff4f60'],
        plotOptions: {
            areaspline: {
                fillOpacity: 0.4
            }
        },
        xAxis: [{
            type: 'datetime',
            dateTimeLabelFormats: {
                month: '%b'
            },
            gridLineWidth: 1,
            showLastLabel: false,
            tickInterval: 24 * 3600 * 1000 * 30.4
        },
        hiddenXAxis(), hiddenXAxis()],
        series: [ {
            name: 'Toàn bộ Merchant',
            data: data2
        }, {
            name: 'Actived Merchant',
            data: data1,
            xAxis: 1
        }, {
            name: 'Non Actived Merchant',
            data: data3,
            xAxis: 2
        }]

    }, function (chart) { // Sets the min and max to display every series from January to December
        for (var i = 0; i < chart.xAxis.length; i++) {
            var extremes = chart.xAxis[i].getExtremes();
            var year = new Date(extremes.max).getFullYear();
            var firstDay = new Date(year, 0, 1);
            var lastDay = new Date(year, 11, 4);
            chart.xAxis[i].setExtremes(firstDay, lastDay);
        }
    });
});
</script>
@stop
