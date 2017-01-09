{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>風扇控制紀錄</h1>
@stop

@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-sm-12">
                    <form method="get">
                <div class="pull-left">時間：</div>
                <div class="input-group date pull-left" id="startPicker">
                    <input type="text" class="form-control" name="start" data-date-format="YYYY-MM-DD HH:mm:ss" value="{{$get['start']}}"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class="pull-left" style="line-height: 32px;margin:0 5px;"> ~ </div>
                <div class="input-group date pull-left" id="endPicker">
                    <input type="text" class="form-control" name="end" data-date-format="YYYY-MM-DD HH:mm:ss" value="{{$get['end']}}" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class="pull-left">
                        、
                        開關：<select name="status" class="form-control">
                            <option value="all" @if($get['status'] == 'all') selected @endif>全部</option>
                            <option value="1" @if($get['status'] == '1') selected @endif>啟用</option>
                            <option value="0" @if($get['status'] == '0') selected @endif>關閉</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-search"></i> 搜尋</button>
                    </div>
                    </form>
                </div>
            </div>
            <div class="row"><div class="col-sm-12"><table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
                <tr>
                    <th class="text-center">時間</th>
                    <th class="text-center">開啟/關閉</th>
                    <th class="text-center">溫度</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $fan)
                <tr>
                    <td class="text-center">{{$fan['time']}}</td>
                    <td class="text-center">
                        @if ($fan['status'] == 1)
                            開啟
                        @else
                            關閉
                        @endif
                    </td>
                    <td class="text-center">{{$fan['value']}}</td>
                </tr>
                @endforeach
                @if ($list->total() == 0)
                <tr>
                    <td colspan="3" class="text-center">無資料</td>
                </tr>
                @endif
            </tbody>
            </table></div></div></div>
                <div class="clearfix"></div>
                <div class="row pull-right page-block">
                    @if ($list->total() > 0)
                    {{ $list->links() }}
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.44/css/bootstrap-datetimepicker.min.css" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.44/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript">
            $(function () {
                $('#startPicker, #endPicker').datetimepicker();
            });
        </script>
@stop