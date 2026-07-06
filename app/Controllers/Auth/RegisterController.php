<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Core\Facades\Auth;
use Core\Facades\DB;
use Core\Facades\Log;
use Core\Facades\View;
use Core\Request;

class RegisterController
{
    public function show(): void
    {
        if (Auth::check()) {
            redirect('dashboard');

            return;
        }

        View::render('auth/register');
    }

    public function store(Request $request): void
    {
        if (Auth::check()) {
            redirect('dashboard');

            return;
        }

        $postData = $request->all();

        // Validation / Validasyon
        $errors = validate($postData, [
            'name' => 'required|min:2|max:100',
            'surname' => 'required|min:2|max:100',
            'username' => 'required|min:3|max:100|alphanumeric|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:255',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($errors) {
            // PRG: keep errors + old input for one render, strip secrets.
            // PRG: hataları + eski input'u bir render için sakla, sırları çıkar.
            $_SESSION['_flash_errors'] = $errors;
            $_SESSION['_old'] = $postData;
            unset($_SESSION['_old']['password'], $_SESSION['_old']['password_confirmation']);
            redirect('register.show');

            return;
        }

        try {
            // Transaction + row lock guarantees the "first user is admin" rule
            // under concurrent registrations.
            // Transaction + satır kilidi, eşzamanlı kayıtlarda "ilk kullanıcı admin"
            // kuralını garanti eder.
            DB::beginTransaction();

            try {
                $userCount = User::query()->lockForUpdate()->count();
                $role = ($userCount === 0) ? 'admin' : 'member';

                $user = new User;
                $user->name = trim(is_string($n = $request->post('name')) ? $n : '');
                $user->surname = trim(is_string($s = $request->post('surname')) ? $s : '');
                $user->username = trim(is_string($u = $request->post('username')) ? $u : '');
                $user->email = trim(is_string($em = $request->post('email')) ? $em : '');
                $user->password = is_string($pw = $request->post('password')) ? $pw : ''; // Mutator hashes // Mutator hash'ler
                $user->role = $role;       // guarded — set explicitly // guarded — açıkça atanır
                $user->status = 'active';
                $user->save();

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->post('email', ''),
            ]);

            flash('error', 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.');
            $_SESSION['_old'] = $postData;
            unset($_SESSION['_old']['password'], $_SESSION['_old']['password_confirmation']);
            redirect('register.show');

            return;
        }

        // User is committed. Side effects (welcome mail etc.) must not break a
        // successful registration — swallow & log listener errors, then continue.
        // Kullanıcı commit edildi. Yan etkiler (welcome mail vb.) başarılı kaydı
        // bozmamalı — listener hatasını yut, logla ve akışı sürdür.
        try {
            event(new UserRegistered($user));
        } catch (\Throwable $e) {
            Log::error('Post-registration event failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Strip password so redirect()'s $_POST snapshot can't leak it into _old.
        // redirect()'in $_POST snapshot'ı _old'a sızdırmasın diye şifreyi çıkar.
        unset($_POST['password'], $_POST['password_confirmation']);

        flash('success', 'Kayıt başarılı! Giriş yapabilirsiniz.');
        redirect('login.show');
    }
}
