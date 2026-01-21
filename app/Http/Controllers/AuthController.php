<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Важно: по условию демо-экзамена — без серверной валидации,
        // поэтому просто берем значения из формы и сохраняем.

        $user = new User();
        $user->login = $request->input('login');
        $user->password = $request->input('password');
        $user->full_name = $request->input('full_name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->is_admin = false;
        $user->save();

        Auth::login($user);

        return redirect()->route('applications.index');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        // Отдельная обработка администратора по условию:
        // логин Admin, пароль KorokNET (без проверки БД).
        if ($login === 'Admin' && $password === 'KorokNET') {
            // Гарантируем, что пользователь Admin существует и имеет права администратора
            // (на случай, если кто-то зарегистрировал логин Admin как обычного пользователя).
            $admin = User::updateOrCreate(
                ['login' => 'Admin'],
                [
                    'password' => 'KorokNET',
                    'full_name' => 'Администратор портала',
                    'phone' => '8(000)000-00-00',
                    'email' => 'admin@example.com',
                    'is_admin' => true,
                ]
            );

            Auth::login($admin);

            return redirect()->route('admin.panel');
        }

        // Обычный пользователь: простая проверка логина и пароля "в лоб"
        $user = User::where('login', $login)
            ->where('password', $password)
            ->first();

        if (!$user) {
            return back()->with('error', 'Неверный логин или пароль');
        }

        Auth::login($user);

        return redirect()->route('applications.index');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login.form');
    }
}


