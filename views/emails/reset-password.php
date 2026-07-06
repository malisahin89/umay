<?php declare(strict_types=1); ?>
<div style="font-family:Arial,sans-serif;max-width:520px;margin:0 auto;color:#111">
    <h2 style="color:#4f46e5">Şifre Sıfırlama</h2>
    <p>Merhaba <?= $this->e($name) ?>,</p>
    <p>Hesabınız için bir şifre sıfırlama talebi aldık. Aşağıdaki bağlantıya tıklayarak yeni şifrenizi belirleyebilirsiniz:</p>
    <p style="margin:24px 0">
        <a href="<?= $this->e($resetUrl) ?>"
           style="background:#4f46e5;color:#fff;padding:12px 20px;border-radius:6px;text-decoration:none">
            Şifremi Sıfırla
        </a>
    </p>
    <p style="color:#6b7280;font-size:13px">Bu talebi siz yapmadıysanız bu e-postayı yok sayabilirsiniz.</p>
</div>
