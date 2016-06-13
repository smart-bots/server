@extends('account.master')
@section('title',trans('forgot.title'))
@section('additionHeader')
@endsection
@section('additionFooter')
<script>
    var forgot_success_title = '@trans('forgot.success_title')',
        forgot_success_text = '@trans('forgot.success_text')',
        forgot_btn_text = '@trans('forgot.success_btn')';

    function forgot() {
        $.ajax({
            url : '@route('a::forgot')',
            type : 'post',
            data : $('[name=forgot-form]').serializeArray(),
            dataType : 'json',
            success : function (response)
            {

                $('[name=forgot-form]').validate(response, ['remember'], function (response) {

                    swal({
                        title: forgot_success_title,
                        text: forgot_success_text,
                        type: "success",
                        confirmButtonText: forgot_btn_text,
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
        <span class="panel-title">@trans('forgot.helper')</span>
        <p class="m-t-10">@trans('forgot.mini_helper')</p>
        {!! Form::open(['route' => 'a::forgot', 'name' => 'forgot-form', 'class' => 'form-horizontal m-t-20', 'onsubmit' => 'return forgot()']) !!}

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="email" placeholder="@trans('forgot.email')">
                </div>
            </div>

            <div class="form-group text-center ">
                <div class="col-xs-12">
                    <button class="btn btn-custom btn-default btn-block text-uppercase waves-effect waves-light" type="submit">@trans('forgot.reset')</button>
                </div>
            </div>

            <div class="form-group m-t-20 m-b-0">
                <div class="col-sm-12 text-center">
                    <h4><b>@trans('forgot.2nd-helper')</b></h4>
                </div>
            </div>

            <div class="form-group m-b-0 text-center">
                <div class="col-sm-12">
                    <a href="@route('a::login')">
                        <button type="button" class="btn btn-default waves-effect waves-light m-t-20">
                           <i class="fa fa-sign-in m-r-10"></i>@trans('forgot.login')
                        </button>
                    </a>

                    <a href="@route('a::register')">
                        <button type="button" class="btn btn-primary waves-effect waves-light m-t-20">
                           <i class="fa fa-user-plus m-r-10"></i>@trans('forgot.register')
                        </button>
                    </a>
                </div>
            </div>

        {!! Form::close() !!}

    </div>
</div>
@endsection
