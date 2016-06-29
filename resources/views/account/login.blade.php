@extends('account.master')
@section('title',trans('account/login.title'))
@section('additionHeader')
@endsection
@section('additionFooter')
<script>
    var login_success_title = '@trans('account/login.success_title')',
        login_success_text = '@trans('account/login.success_text')',
        login_btn_text = '@trans('account/login.success_btn')';

    function login() {
        $.ajax({
            url : '@route('a::login')',
            type : 'post',
            data : $('[name=login-form]').serializeArray(),
            dataType : 'json',
            success : function (response)
            {

                $('[name=login-form]').validate(response, ['remember'], function () {

                    swal({
                        title: login_success_title,
                        text: login_success_text,
                        type: "success",
                        confirmButtonText: login_btn_text,
                    }, function() {

                        window.location.href = response['href'];

                    });
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
        <span class="panel-title">@trans('account/login.helper')</span>

        {!! Form::open(['route' => 'a::login', 'name' => 'login-form', 'class' => 'form-horizontal m-t-20', 'onsubmit' => 'return login()']) !!}

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="username" placeholder="@trans('account/login.username')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="password" class="form-control" name="password" placeholder="@trans('account/login.password')">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <div class="checkbox checkbox-custom">
                        <input name="remember" type="checkbox" checked>
                        <label for="remember">&nbsp;@trans('account/login.remember_me')</label>
                    </div>

                </div>
            </div>

            <div class="form-group text-center m-t-40">
                <div class="col-xs-12">
                    <button class="btn btn-custom btn-default btn-block text-uppercase waves-effect waves-light" type="submit">@trans('account/login.login')</button>
                </div>
            </div>

            <div class="form-group m-t-20 m-b-0">
                <div class="col-sm-12">
                    <a href="@route('a::forgot')" class="text-dark"><i class="fa fa-lock m-r-5"></i>&nbsp;@trans('account/login.link_forgot')</a>
                </div>
            </div>

            <div class="form-group m-t-20 m-b-0">
                <div class="col-sm-12 text-center">
                    <h4><b>@trans('account/login.login_with')</b></h4>
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
            @trans('account/login.link_register_helper')&nbsp;<a href="@route('a::register')" class="text-primary m-l-5"><b>@trans('account/login.register')</b></a>
        </p>
    </div>
</div>
@endsection
