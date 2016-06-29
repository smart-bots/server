@extends('hub.master')
@section('title',trans('account/change-pass.title'))
@section('additionHeader')
@endsection
@section('additionFooter')
    <script>
        function changePass() {
            $.ajax({
                url : '@route('a::changePass')',
                type : 'post',
                data : $('[name=change-pass-form]').serializeArray(),
                dataType : 'json',
                success : function (response)
                {
                    $('[name=change-pass-form]').validate(response);
                }
            });
            return false;
        }
    </script>
@endsection
@section('body')
@header(trans('account/change-pass.title'), [
    'Account' => 'active'])
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
        {!! Form::open(['route' => 'a::changePass','name'=> 'change-pass-form', 'class' => 'form-horizontal', 'onsubmit' => 'return changePass()']) !!}
            <div class="form-group">
                {!! Form::label('oldpass', trans('account/change-pass.cur_pass'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::password('currentpass', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('password', trans('account/change-pass.new_pass'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('password_confirmation', trans('account/change-pass.confirm_pass'), ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>
            </div>
            {!! Form::button('<span class="btn-label"><i class="fa fa-floppy-o" aria-hidden="true"></i></span>'.trans('account/change-pass.change_pass'), ['type' => 'submit', 'class' => 'btn btn-primary waves-effect waves-light']) !!}
        {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
