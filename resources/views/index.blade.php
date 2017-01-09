{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>室內溫度紀錄</h1>
@stop

@section('content')
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
@stop

@section('css')

@stop

@section('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'line'
        },
        title: {
            text: '{{$date['date']}}'
        },
        subtitle: {
            text: '來源：台中市　西區'
        },
        xAxis: [{
            type:'datetime',
            tickInterval:3600 * 1000,
            labels: {
                formatter: function () {
                    return Highcharts.dateFormat('%H', this.value);
                },
                style: {
                    color: '#89A54E'
                }
            }
        }],
        yAxis: {
            title: {
                text: '溫度 (°C)'
            }
        },
        plotOptions: {
             series:{
                pointStart:Date.UTC({{$date['y']}}, {{$date['m']}},{{$date['d']}}),
                pointInterval: 3600 * 1000
            }
        },
        series: [{
            name: '室內溫度',
            data: [{{$list}}]
        }]
    });
});
</script>
@stop