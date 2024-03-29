<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend') }}/css/plugin.min.css">
    <link rel="stylesheet" href="{{ asset('backend') }}/css/style.css">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend') }}/img/favicon.png">
    <style>
        .edit-profile__body{
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<main class="main-content">
    <div class="signUP-admin">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-5 p-0">
                    <div class="signUP-admin-left signIn-admin-left position-relative">
                        <div class="signUP-admin-left__content">
                            <div class="text-capitalize mb-md-30 mb-15 d-flex align-items-center justify-content-md-start justify-content-center">
                                <a class="wh-36 bg-primary text-white radius-md mr-10 content-center" href="index.html">a</a>
                                <span class="text-dark">admin</span>
                            </div>
                        </div><!-- End: .signUP-admin-left__content  -->
                        <div class="signUP-admin-left__img">
                            <img class="img-fluid svg" src="{{ asset('backend') }}/img/svg/signupIllustration.svg" alt="img" />
                        </div><!-- End: .signUP-admin-left__img  -->
                    </div><!-- End: .signUP-admin-left  -->
                </div><!-- End: .col-xl-4  -->
                <div class="col-xl-8 col-lg-7 col-md-7 col-sm-8">
                    <div class="signUp-admin-right signIn-admin-right  p-md-40 p-10">
                        <div class="row justify-content-center">
                            <div class="col-xl-7 col-lg-8 col-md-12">
                                <div class="edit-profile mt-md-25 mt-0">
                                    <div class="card border-0">
                                        <div class="card-header border-0  pb-md-15 pb-10 pt-md-20 pt-10 ">
                                            <div class="edit-profile__title">
                                                <h6>Sign up to <span class="color-primary">Admin</span></h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('admin.login.submit') }}" method="post">
                                                @csrf
                                                <div class="edit-profile__body">
                                                    <div class="form-group mb-20">
                                                        <label for="email">Username or Email Address</label>
                                                        <input type="text" name="email" class="form-control" id="email" placeholder="email">
                                                    </div>
                                                    <div class="form-group mb-15">
                                                        <label for="password-field">password</label>
                                                        <div class="position-relative">
                                                            <input id="password-field" type="password" class="form-control" name="password" placeholder="password">
                                                            <div class="fa fa-fw fa-eye-slash text-light fs-16 field-icon toggle-password2" id="toggle-password"></div>
                                                        </div>
                                                    </div>
                                                    <div class="button-group d-flex pt-1 justify-content-md-start justify-content-center">
                                                        <button type="submit" class="btn btn-primary btn-default btn-squared mr-15 text-capitalize lh-normal px-50 py-15 signIn-createBtn ">
                                                            sign in
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div><!-- End: .card-body -->
                                    </div><!-- End: .card -->
                                </div><!-- End: .edit-profile -->
                            </div><!-- End: .col-xl-5 -->
                        </div>
                    </div><!-- End: .signUp-admin-right  -->
                </div><!-- End: .col-xl-8  -->
            </div>
        </div>
    </div>
</main>
<div id="overlayer">
    <span class="loader-overlay">
        <div class="atbd-spin-dots spin-lg">
            <span class="spin-dot badge-dot dot-primary"></span>
            <span class="spin-dot badge-dot dot-primary"></span>
            <span class="spin-dot badge-dot dot-primary"></span>
            <span class="spin-dot badge-dot dot-primary"></span>
        </div>
    </span>
</div>

<script src="{{ asset('backend') }}/js/plugins.min.js"></script>
<script src="{{ asset('backend') }}/js/script.min.js"></script>
<script>
    $(document).ready(function() {
        $("#toggle-password").click(function() {
            let passwordField = $("#password-field");
            let icon = $(this);

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });
    });
</script>

</body>
</html>
