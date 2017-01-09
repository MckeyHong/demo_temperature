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

@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop