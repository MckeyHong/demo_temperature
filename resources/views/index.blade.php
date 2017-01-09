{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>室內溫度紀錄</h1>
@stop

@section('content')
<section class="content">
    <div class="row">
        <div class="box">
            <div style="padding:10px;">
                <form id="search-form" method="get">
                    日期：<select name="date" onchange="$('#search-form').submit();">
                        @for($day = 0 ; $day <= 6 ; $day++)
                        <option value="{{date('Y-m-d', strtotime($date['now']. " - ".$day." days"))}}" @if($get == date('Y-m-d', strtotime($date['now']. " - ".$day." days"))) selected @endif>{{date('Y-m-d', strtotime($date['now']. " - ".$day." days"))}}</option>
                        @endfor
                    </select>
                </form>
            </div>
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</section>
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
                pointInterval: 3600 * 1000,
                cursor: 'pointer',
                events: {
                    click: function (event) {
                        console.log(event.point.index);
                        console.log(event.point.y);
                    }
                }
            }
        },
        series: [{
            name: '室內溫度(小時)',
            data: [{{$list}}]
        }]
    });
});
</script>
@stop