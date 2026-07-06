<?php declare(strict_types=1); ?>
<?php
/**
 * Front-end popup modal. Server sends every scheduled+targeted popup; the JS
 * shows the first one the visitor hasn't dismissed according to its frequency:
 *   once    → localStorage   (never again on this device)
 *   session → sessionStorage (once per browser session)
 *   always  → shown on every page load
 *
 * Ön yüz popup modalı. Sunucu programlanmış+hedeflenmiş tüm popup'ları yollar;
 * JS, sıklığına göre ziyaretçinin kapatmadığı ilkini gösterir.
 *
 * @var iterable<\App\Models\Popup> $popups
 */
$asset = static fn ($p): ?string => (is_string($p) && $p !== '')
    ? ((str_starts_with($p, 'http') || str_starts_with($p, '/')) ? $p : '/storage/'.ltrim($p, '/'))
    : null;

$items = [];
foreach ($popups as $popup) {
    $title = (string) ($popup->title ?? '');
    $content = (string) ($popup->content ?? '');
    if ($title === '' && $content === '') {
        continue; // No translated copy in this locale → nothing to show.
    }
    $items[] = $popup;
}

if ($items === []) {
    return;
}
?>
<div id="umayPopupRoot" class="hidden" aria-hidden="true">
    <?php foreach ($items as $popup) { ?>
        <?php
        $img = $asset($popup->image);
        $btnText = (string) ($popup->button_text ?? '');
        $btnUrl = (string) ($popup->button_url ?? '');
        ?>
        <div class="umay-popup"
             data-id="<?= (int) $popup->id ?>"
             data-frequency="<?= $this->e((string) $popup->display_frequency) ?>">
            <div class="umay-popup__overlay"></div>
            <div class="umay-popup__card" role="dialog" aria-modal="true" aria-label="<?= $this->e((string) ($popup->title ?? 'Popup')) ?>">
                <button type="button" class="umay-popup__close" aria-label="Kapat" data-popup-close>
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <?php if ($img !== null) { ?>
                    <div class="umay-popup__media">
                        <img src="<?= $this->e($img) ?>" alt="<?= $this->e((string) ($popup->title ?? '')) ?>" loading="lazy">
                    </div>
                <?php } ?>
                <div class="umay-popup__body">
                    <?php if (($popup->title ?? '') !== '') { ?>
                        <h3 class="umay-popup__title font-display"><?= $this->e((string) $popup->title) ?></h3>
                    <?php } ?>
                    <?php if (($popup->content ?? '') !== '') { ?>
                        <div class="umay-popup__content"><?= $popup->content /* richtext HTML (admin-trusted) */ ?></div>
                    <?php } ?>
                    <?php if ($btnText !== '') { ?>
                        <a class="umay-popup__btn"
                           <?php if ($btnUrl !== '') { ?>href="<?= $this->e($btnUrl) ?>"<?php } else { ?>href="#" data-popup-close<?php } ?>>
                            <?= $this->e($btnText) ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<style nonce="<?= $this->nonce() ?>">
.umay-popup{position:fixed;inset:0;z-index:100;display:none;align-items:center;justify-content:center;padding:1rem}
.umay-popup.is-open{display:flex}
.umay-popup__overlay{position:absolute;inset:0;background:rgba(24,24,27,.55);backdrop-filter:blur(4px)}
.umay-popup__card{position:relative;z-index:1;width:100%;max-width:26rem;overflow:hidden;border-radius:1.25rem;background:#fff;box-shadow:0 20px 60px -15px rgba(0,0,0,.45);animation:umayPopIn .28s cubic-bezier(.16,1,.3,1)}
@keyframes umayPopIn{from{opacity:0;transform:translateY(12px) scale(.97)}to{opacity:1;transform:none}}
.umay-popup__close{position:absolute;top:.75rem;right:.75rem;z-index:2;display:flex;height:2rem;width:2rem;align-items:center;justify-content:center;border-radius:9999px;background:rgba(255,255,255,.85);color:#3f3f46;box-shadow:0 1px 3px rgba(0,0,0,.15);transition:background .2s}
.umay-popup__close:hover{background:#fff}
.umay-popup__media img{display:block;width:100%;max-height:16rem;object-fit:cover}
.umay-popup__body{padding:1.5rem}
.umay-popup__title{margin:0 0 .5rem;font-size:1.35rem;font-weight:600;color:#18181b}
.umay-popup__content{font-size:.925rem;line-height:1.6;color:#52525b}
.umay-popup__content p{margin:0 0 .5rem}
.umay-popup__btn{display:inline-block;margin-top:1.1rem;border-radius:9999px;background:linear-gradient(135deg,#6366f1,#8b5cf6,#d946ef);padding:.6rem 1.4rem;font-size:.9rem;font-weight:600;color:#fff;text-decoration:none;transition:filter .2s}
.umay-popup__btn:hover{filter:brightness(1.08)}
</style>
<script nonce="<?= $this->nonce() ?>">
(function () {
    var root = document.getElementById('umayPopupRoot');
    if (!root) return;
    var popups = Array.prototype.slice.call(root.querySelectorAll('.umay-popup'));

    function seen(id, freq) {
        try {
            if (freq === 'once') return localStorage.getItem('umay_popup_' + id) === '1';
            if (freq === 'session') return sessionStorage.getItem('umay_popup_' + id) === '1';
        } catch (e) {}
        return false; // 'always' (or storage blocked) → always show
    }
    function remember(id, freq) {
        try {
            if (freq === 'once') localStorage.setItem('umay_popup_' + id, '1');
            else if (freq === 'session') sessionStorage.setItem('umay_popup_' + id, '1');
        } catch (e) {}
    }

    // Show the first popup this visitor is still eligible to see.
    var target = null;
    for (var i = 0; i < popups.length; i++) {
        var el = popups[i];
        if (!seen(el.getAttribute('data-id'), el.getAttribute('data-frequency'))) { target = el; break; }
    }
    if (!target) return;

    function close() {
        target.classList.remove('is-open');
        remember(target.getAttribute('data-id'), target.getAttribute('data-frequency'));
        document.body.style.overflow = '';
    }

    target.querySelectorAll('[data-popup-close]').forEach(function (b) {
        b.addEventListener('click', function (e) {
            if (b.tagName === 'A' && !b.getAttribute('href')) e.preventDefault();
            close();
        });
    });
    var ov = target.querySelector('.umay-popup__overlay');
    if (ov) ov.addEventListener('click', close);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

    root.classList.remove('hidden');
    root.removeAttribute('aria-hidden');
    target.classList.add('is-open');
    document.body.style.overflow = 'hidden';
})();
</script>
