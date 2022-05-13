$(document).ready(function () {

    $(document).on('click', '.form-subscribe .subscribeBtn', function (e) {
        if ($('.form-subscribe .subscribeEmail').val() !== '') {
            e.preventDefault();
            $('.form-subscribe .step1,.form-subscribe .step3').addClass('d-none');
            $('.form-subscribe .step2').removeClass('d-none');

        }
    });

    $(document).on('click', '.form-subscribe .sendCodeBtn', function (e) {
        e.preventDefault();
        var code = $('.form-subscribe .subscribeVerifyCode').val();
        var email = $('.form-subscribe .subscribeEmail').val();
        if(code == undefined || email == undefined) return false;
        btnLoading(this);

        $.ajax({
            url: base_url + '/api/subscribe_code_verification',
            method: 'post',
            dataType: 'json',
            data: {email: email, code: code},
            success: function (data) {
                $('.form-subscribe .step4').addClass('d-none');
                if (data.error !== undefined) {
                    $('.form-subscribe .alert-danger').html(data.error).removeClass('d-none');
                    grecaptcha.reset();
                    setTimeout(function () {
                        $('.form-subscribe .alert-danger').addClass('d-none');
                        $('.form-subscribe .step1').removeClass('d-none').val('');
                    }, 4000);
                }
                if (data.success !== undefined) {
                    $('.form-subscribe .alert-success').html(data.message).removeClass('d-none');
                }
            }
        })
    });
});

function recaptchaCallback(token) {
    $('.form-subscribe .step2').addClass('d-none');
    $('.form-subscribe .step3').removeClass('d-none');
    $.ajax({
        url: base_url + '/api/subscribe_email',
        method: 'post',
        dataType: 'json',
        data: {email: $('.form-subscribe .subscribeEmail').val(), recaptcha_code: token},
        success: function (data) {
            $('.form-subscribe .step3').addClass('d-none');
            if (data.error !== undefined) {
                $('.form-subscribe .alert-danger').html(data.error).removeClass('d-none');
                grecaptcha.reset();
                setTimeout(function () {
                    $('.form-subscribe .alert-danger').addClass('d-none');
                    $('.form-subscribe .step1').removeClass('d-none').val('');
                }, 4000);
            }
            if (data.success !== undefined) {
                $('.form-subscribe .step4').removeClass('d-none');
            }
            if (data.exists !== undefined) {
                $('.form-subscribe .alert-success').html(data.message).removeClass('d-none');
            }
        }
    });
}