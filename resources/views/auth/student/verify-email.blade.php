<!doctype html>
<html lang="en">

<head>
    <title>Reset Password</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="m-4">
                <h4>https://www.k3library.com/ K3Library Password Reset</h3>
            </div>
            <div class="card-body">
                <p>Hello <b>{{ $name }}</b>,</p>
                <p>We received a request to reset the password for your account at <a href="https://www.k3library.com/">K3Library</a> .</p>
                <p>If you did not request this change, please ignore this email. No further action is needed.</p>

                <p>However, if you did request a password reset, please use the link below to set a new password:</p>

                <p><a href="{{ route('student.passwordReset', $token) }}" target="_blank">Reset Your Password</a></p>

                <p>This unique link will expire in [24 Hours], so please reset your password as soon as possible. Once used, it will no longer be valid.</p>

                <p>If you're having trouble clicking the password reset link, you can copy and paste the following URL into your browser:</p>
                <p>{{ route('student.passwordReset', $token) }}</p>

                <p>Thank you,</p>
                <p>The K3Library Team</p>
                <p>Website: <a href="https://www.k3library.com/ " target="_blank">https://www.k3library.com/ </a></p>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>


