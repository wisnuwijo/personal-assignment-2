<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="padding:20px;">
                <h3>Login</h3>
                @if (session()->has('message'))
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (\Session::get("locked") !== null)
                    You are locked, please try again 30 seconds later
                @else
                    <form action="{{ url('/login' . '?attempt=' . ($_GET['attempt'] ?? 0)) }}" method="POST">
                        @csrf
                        <input type="text" name="email" placeholder="Email" class="form-control" style="margin-bottom:10px;margin-top:20px;" />
                        <input type="password" name="password" placeholder="Password" class="form-control" style="margin-bottom:10px" />
                        
                        <br/>
                            <img src="{{ $captcha_image }}" />
                        <br/>
                            <input type="text" name="captcha" placeholder="Masukkan captcha" class="form-control" style="margin-bottom:10px;margin-top:20px;" />
                            <input type="hidden" name="random" value="{{ $random }}" />
                        <br/>
                        
                        <a href="{{ url('forgot-password') }}" style="text-decoration: none">Lupa password?</a>
                        <br/>
                        <br/>

                        <input type="submit" value="Masuk" class="btn btn-primary btn-md" />
                    </form>

                @endif
            </div>
        </div>
    </div>
</body>
</html>