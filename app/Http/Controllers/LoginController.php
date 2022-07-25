<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Helper\CaptchasDotNet;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;

class LoginController extends Controller
{
    public function index(Request $req)
    {
        if (session()->has("user")) {
            return redirect("loggedin");
        }

        date_default_timezone_set('Asia/Jakarta');

        $now = date("Y-m-d h:i:s");
        $lockedUntil = null;
        if ($req->session()->get("locked") !== null) {
            $lockedUntil = date("Y-m-d h:i:s", strtotime($req->session()->get("locked")["until"]));
        }

        if ($lockedUntil != null && $now > $lockedUntil) {
            $req->session()->forget("locked");
            $req->session()->forget("attempt");

            return redirect("/login");
        }

        $captcha = new CaptchasDotNet('demo', 'secret');

        $randomString = Str::random(15);
        return view("login.index", [
            "random" => $randomString,
            "captcha_image" => $captcha->image_url($randomString)
        ]);
    }

    public function processLogin(Request $req)
    {
        $email = $req->email;
        $password = $req->password;
        $captchaText = $req->captcha;
        $random = $req->random;

        $attemptSession = $req->session()->get("attempt");

        $checkUser = User::where("email", $email)->first();
        $captcha = new CaptchasDotNet('demo', 'secret');

        if (!$captcha->verify($captchaText, $random)) {
            session()->flash('message', 'Captcha salah');
            return redirect("/login");
        }
        
        if (isset($checkUser) && Hash::check($password, $checkUser->password)) {
            $req->session()->forget("locked");
            $req->session()->forget("attempt");

            session(['user' => $checkUser]);

            return redirect("/loggedin");
        }

        $attempt = $attemptSession ?? 0;
        if (isset($attemptSession)) {
            $attempt += 1;
        } else {
            $attempt = 1;
        }

        session(["attempt" => $attempt]);

        if ($attempt > 3) {
            date_default_timezone_set('Asia/Jakarta');
            $duration = 30;
            $date = date("Y-m-d H:i:s", strtotime("+$duration sec"));

            session(["locked" => [
                "ip" => $req->ip(),
                "until" => $date
            ]]);
        }
        
        session()->flash('message', 'Email / password is wrong');
        return redirect("/login");
    }

    public function logout(Request $req)
    {
        $req->session()->forget("user");
        return redirect("login");
    }

    public function loggedin(Request $req)
    {
        if ($req->session()->get("user") == null) return redirect("/login");
        return view("loggedin");
    }

    public function forgotPassword()
    {
        return view("login.forgot_password");
    }

    public function processForgotPassword(Request $req)
    {
        $email = $req->email;

        $user = User::where("email", $email)->first();
        if (!isset($user)) {
            session()->flash('message', 'Email does not exist');
            return redirect("/forgot-password");
        }

        $resetPasswordCode = rand(10000,99999);

        $user->code = $resetPasswordCode;
        $user->notify(new ResetPasswordNotification($user));
        PasswordReset::insert([
            "email" => $user->email,
            "token" => $resetPasswordCode
        ]);

        return view("login.reset_password", [
            "email" => $user->email
        ]);
    }

    public function processResetPassword(Request $req)
    {
        $code = $req->code;
        $password = $req->password;
        $email = $req->email;

        $verifyCode = PasswordReset::where("token", $code)->first();

        User::where("email", $email)->update([
            "password" => Hash::make($password)
        ]);

        session()->flash('success', 'Kata sandi berhasil diubah');
        
        return redirect("/login");
    }
}
