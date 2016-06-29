@extends('account.master')
@section('title',trans('account/register.title'))
@section('additionHeader')
@endsection
@section('additionFooter')
<script>
    var register_success_title = '@trans('account/register.success_title')',
        register_success_text = '@trans('account/register.success_text')',
        register_confirm_text = '@trans('account/register.success_confirm')',
        register_cancel_text = '@trans('account/register.success_cancel')';

    function register() {
        $.ajax({
            url : '@route('a::register')',
            type : 'post',
            data : $('[name=register-form]').serializeArray(),
            dataType : 'json',
            success : function (response)
            {

                $('[name=register-form]').validate(response, ['remember'], function (response) {

                    swal({
                        title: register_success_title,
                        text: register_success_text,
                        type: "success",
                        showCancelButton: true,
                        confirmButtonText: register_confirm_text,
                        cancelButtonText: register_cancel_text,
                    }, function(isConfirm){
                            if (isConfirm) {
                                window.location.href = '@route('a::login')';
                            }
                        }
                    );
                });
            }
        });
        return false;
    }
</script>
@endsection
@section('body')
<div class="card-box animated bounceIn">

    <div class="panel-heading animated">
        <h1 class="smartbot text-center text-custom"><span class="fa fa-signal"></span> <span class="smart">Smart</span>bots</h1>
    </div>

    <div class="panel-body animated">
        <span class="panel-title">@trans('account/register.helper')</span>

        {!! Form::open(['route' => 'a::register', 'name' => 'register-form', 'class' => 'form-horizontal m-t-20', 'onsubmit' => 'return register()']) !!}

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="username" placeholder="@trans('account/register.username')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="name" placeholder="@trans('account/register.name')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="email" placeholder="@trans('account/register.email')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="password" class="form-control" name="password" placeholder="@trans('account/register.password')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="@trans('account/register.password_confirmation')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <div class="checkbox checkbox-custom">
                        <input name="agree_with_terms" type="checkbox">
                        <label for="agree_with_terms">@trans('account/register.agree_to')<a href="#">  @trans('account/register.terms')</a></label>
                    </div>

                </div>
            </div>

            <div class="form-group text-center m-t-40">
                <div class="col-xs-12">
                    <button class="btn btn-custom btn-default btn-block text-uppercase waves-effect waves-light" type="submit">@trans('account/register.register')</button>
                </div>
            </div>

            <div class="form-group m-t-20 m-b-0">
                <div class="col-sm-12">
                    <a href="@route('a::forgot')" class="text-dark"><i class="fa fa-lock m-r-5"></i>&nbsp;@trans('account/register.link_forgot')</a>
                </div>
            </div>

            <div class="form-group m-t-20 m-b-0">
                <div class="col-sm-12 text-center">
                    <h4><b>@trans('account/register.register_with')</b></h4>
                </div>
            </div>

            <div class="form-group m-b-0 text-center">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-facebook waves-effect waves-light m-t-20">
                       <i class="fa fa-facebook m-r-5"></i> Facebook
                    </button>

                    <button type="button" class="btn btn-twitter waves-effect waves-light m-t-20">
                       <i class="fa fa-twitter m-r-5"></i> Twitter
                    </button>

                    <button type="button" class="btn btn-googleplus waves-effect waves-light m-t-20">
                       <i class="fa fa-google-plus m-r-5"></i> Google+
                    </button>
                </div>
            </div>

        {!! Form::close() !!}

    </div>
</div>
<div class="row animated bounceIn">
    <div class="col-sm-12 text-center">
        <p>
            @trans('account/register.link_login_helper')&nbsp;<a href="@route('a::login')" class="text-primary m-l-5"><b>@trans('account/register.login')</b></a>
        </p>
    </div>
</div>
@endsection
