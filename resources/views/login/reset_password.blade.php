<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="padding:20px;">
                <h3>Reset Password</h3>
                @if (session()->has('message'))
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                @endif
                <form method="post" action="{{ url('reset-password') }}">
                    @csrf
                    <p>Kami telah mengirim kode reset password ke email Anda, mohon masukkan kode pada form dibawah ini</p>
                    <input type="hidden" name="email" value="{{ $email }}" />
                    <input type="text" name="code" placeholder="Kode" class="form-control" style="margin-bottom:10px;margin-top:20px;" />
                    <input type="password" name="password" min="8" placeholder="Password baru" class="form-control" style="margin-bottom:10px;margin-top:20px;" required />
                    <input type="submit" value="Reset" class="btn btn-primary btn-md" />
                </form>
            </div>
        </div>
    </div>
</body>
</html>