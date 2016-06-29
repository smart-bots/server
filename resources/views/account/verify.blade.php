@extends('account.master')
@section('title',trans('account/verify.title'))
@section('additionHeader')
    <link rel="stylesheet" href="@asset('public/libs/intl-tel-input/css/intlTelInput.css')">
    <style>
        .intl-tel-input {
            width: 100%;
        }
    </style>
@endsection
@section('additionFooter')
<script src="@asset('public/libs/intl-tel-input/js/intlTelInput.js')"></script>
<script>
    function logout() {
        swal({
            title: '@trans('account/logout.title')',
            text: '@trans('account/logout.text')',
            type: "warning",
            showCancelButton: true,
            confirmButtonText: '@trans('account/logout.confirm')',
            cancelButtonText: '@trans('account/logout.cancel')',
            closeOnConfirm: false }, function() {
                $.ajax({
                    url : '@route('a::logout')',
                    type : 'get',
                    success : function (response)
                    {
                        window.location.href=response.href;
                    }
                });
            });
    }

    $("[name=phone]").intlTelInput({
      initialCountry: "auto",
      geoIpLookup: function(callback) {
        $.get('http://ipinfo.io', function() {}, "jsonp").always(function(resp) {
          var countryCode = (resp && resp.country) ? resp.country : "";
          callback(countryCode);
        });
      }
    });

    $("[name=phone]").bind('change', function() {
        var dialCode = $("[name=phone]").intlTelInput("getSelectedCountryData").dialCode;
        var phoneNumber = $("[name=phone").val();
        if (phoneNumber.charAt(0) != '+') {
            if (phoneNumber.charAt(0) != '0') {
                $("[name=phone").val('+'+dialCode+phoneNumber);
            } else {
                $("[name=phone").val(phoneNumber.replace('0','+'+dialCode));
            }
        }
    });

    function verify() {
        $.ajax({
            url : '@route('a::verify')',
            type : 'post',
            data : $('[name=verify-form]').serializeArray(),
            dataType : 'json',
            success : function (response)
            {

                $('[name=verify-form]').validate(response, [], function () {

                    swal({
                        title: response['number_to_dial'],
                        text: '@trans('account/verify.info')',
                        type: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 1.6 * 60 * 1000
                    });

                    var stream = new EventSource("@route('a::verifyStatus')");

                    stream.onmessage = function(e) {
                        console.log(e);
                    }

                    stream.addEventListener("expired", function foo(event) {
                        swal('@trans('account/verify.expired_title')', '@trans('account/verify.expired_text')', "error");
                        this.removeEventListener("expired", foo);
                        stream.close();
                    });

                    stream.addEventListener("verified", function foo(event) {
                        swal('@trans('account/verify.verified_title')', '@trans('account/verify.verified_text')', "success");
                        this.removeEventListener("verified", foo);
                        stream.close();
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
        <span class="panel-title">@trans('account/verify.helper')</span>
        <p class="m-t-10">@trans('account/verify.mini_helper')</p>
        {!! Form::open(['route' => 'a::verify', 'name' => 'verify-form', 'class' => 'form-horizontal m-t-20', 'onsubmit' => 'return verify()']) !!}

            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" name="phone" placeholder="@trans('account/verify.phone')" value="{{ $phone }}">
                </div>
            </div>

            <div class="form-group text-center ">
                <div class="col-xs-12">
                    <button class="btn btn-custom btn-default btn-block text-uppercase waves-effect waves-light" type="submit">@trans('account/verify.verify')</button>
                </div>
            </div>

            <div class="form-group m-t-20 m-b-0">
                <div class="col-sm-12">
                    <a href="javascript:logout()" class="text-dark"><i class="fa fa-lock m-r-5"></i>@trans('account/verify.logout')</a>
                </div>
            </div>

        {!! Form::close() !!}

    </div>
</div>
@endsection
