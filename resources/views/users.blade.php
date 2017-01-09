{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>使用者</h1>
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
                <div class="col-sm-6">
                    <form method="get">
                        帳號：<input type="email" name="email" class="form-control" value="{{$get['email']}}">
                        、
                        狀態：<select name="active" class="form-control">
                            <option value="">全部</option>
                            <option value="1" @if($get['active'] == '1') selected @endif>啟用</option>
                            <option value="2" @if($get['active'] == '2') selected @endif>停用</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-search"></i> 搜尋</button>
                    </form>
                </div>
                <div class="col-sm-6 pull-right">
                    <button id="btn-add" type="button" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> 新增</button>
                </div>
            </div>
            <div class="row"><div class="col-sm-12"><table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
                <tr>
                    <th>帳號</th>
                    <th width="15%">名稱</th>
                    <th width="10%" class="text-center">帳號狀態</th>
                    <th width="20%" class="text-center">新增時間</th>
                    <th width="15%" class="text-center">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $user)
                <tr>
                    <td>{{$user['email']}}</td>
                    <td>{{$user['name']}}</td>
                    <td class="text-center">@if($user['active'] == '1') 啟用 @else <span class="text-danger">停用</span> @endif</td>
                    <td class="text-center">{{$user['created_at']}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="{{$user['id']}}" style="margin-right: 5px;"><i class="fa fa-pencil"></i> 修改</button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{$user['id']}}" data-email="{{$user['email']}}" style="margin-right: 5px;"><i class="fa fa-trash"></i> 刪除</button>
                        <input type="hidden" id="name_{{$user['id']}}" value="{{$user['name']}}">
                        <input type="hidden" id="email_{{$user['id']}}" value="{{$user['email']}}">
                        <input type="hidden" id="active_{{$user['id']}}" value="{{$user['active']}}">
                    </td>
                </tr>
                @endforeach
                @if ($list->total() == 0)
                <tr>
                    <td colspan="5" class="text-center">無資料</td>
                </tr>
                @endif
            </tbody>
            </table></div></div></div>
                <div class="clearfix"></div>
                <div class="row pull-right page-block">
                    @if ($list->total() > 0)
                    {{ $list->appends(['email' => $get['email'], 'active' => $get['active']])->links() }}
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
{{-- Model --}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modelTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="user-form" method="post">
      <input type="hidden" id="id" name="id" value="0">
      <div class="modal-body">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" laceholder="請輸入 email">
          </div>
          <div class="form-group">
            <label for="password">密碼</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="請輸入密碼">
          </div>
          <div class="form-group">
            <label for="password">密碼</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="密碼再次輸入確認">
          </div>
          <div class="form-group">
            <label for="name">名稱</label>
            <input type="email" class="form-control" id="name" name="name" placeholder="請輸入名稱">
          </div>
          <div class="form-group">
            <label for="active-block">帳號狀態</label>
            <div id="active-block">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="active" id="active-1" value="1" checked>
                    啟用
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="active" id="active-2" value="2" checked>
                    停用
                  </label>
                </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" onclick="$('#user-form').submit();" class="btn btn-primary">送出</button>
      </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>
$(function (){
    $('.btn-delete').click(function () {
        var _self = $(this);
        swal({
          title: "你確定嗎?",
          text: "刪除此使用者 " + _self.data('email') + " 帳號!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "刪除",
          cancelButtonText: "取消",
          closeOnConfirm: false
        },
        function(){
            $.ajax({
                url: url + '/users/' + _self.data('id'),
                type: 'post',
                dataType: 'json',
                data: {
                    _method: 'delete',
                },
                success: function (response) {
                    location.reload();
                    swal("刪除成功!", "此使用者已被刪除", "success");
                }
            });
        });
    });

    $('#btn-add').click(function () {
        $('#myModal #modelTitle').html('新增使用者');
        $('#user-form #id').val(0);
        $('#user-form').find("input[type=text], input[type=email], input[type=password]").val("");
        $('#user-form #email').attr('disabled', false);
        $('input[name=active]').get(0).checked = true;
        $('#myModal').modal('show');
    });

    $('.btn-edit').click(function () {
        $('#user-form').find("input[type=text], input[type=email], input[type=password]").val("");
        var id = $(this).data('id');
        $('#myModal #modelTitle').html('編輯使用者');
        $('#user-form #id').val(id);
        $('#user-form #email').val($('#email_' + id).val()).attr('disabled', true);
        $('#user-form #name').val($('#name_' + id).val());
        if ($('#active_' + id).val() == 1) {
            $('input[name=active]').get(0).checked = true;
        } else {
            $('input[name=active]').get(1).checked = true;
        }
        $('#myModal').modal('show');
    });

    $('#user-form').submit(function (event) {
        event.preventDefault();
        if ($('#id').val() == '0') {
            $.ajax({
                url: url + '/users',
                type: 'post',
                dataType: 'json',
                data: $('#user-form').serialize(),
                success: function (response) {
                    if (response.result == true) {
                        location.reload();
                    } else {
                        swal("新增錯誤", "請確認資料是否填寫正確", "error");
                        return false;
                    }
                }
            });
        } else {
            $.ajax({
                url: url + '/users/' + $('#id').val(),
                type: 'post',
                dataType: 'json',
                data: {
                    _method: 'put',
                    name: $('#name').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val(),
                    active: $('input[name=active]:checked').val()
                },
                success: function (response) {
                    if (response.result == true) {
                        location.reload();
                    } else {
                        swal("更新錯誤", "請確認資料是否填寫正確", "error");
                        return false;
                    }
                }
            });
        }
    });
})
</script>
@stop