<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/base', ['title' => 'Umay Framework — Modern PHP MVC']) ?>

<?php $this->start('body') ?>

<style nonce="<?= $this->nonce() ?>">
/* ── Animated gradient background ─────────────────────────────────────── */
.umay-hero {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 40%, #24243e 100%);
    position: relative;
    overflow: hidden;
}
.umay-hero::before {
    content: '';
    position: absolute;
    width: 200%;
    height: 200%;
    top: -50%;
    left: -50%;
    background: radial-gradient(circle at 30% 40%, rgba(99,102,241,0.12) 0%, transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(168,85,247,0.10) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(236,72,153,0.08) 0%, transparent 50%);
    animation: aurora 20s ease-in-out infinite alternate;
    will-change: transform;
}
@keyframes aurora {
    0%   { transform: translate(0, 0) rotate(0deg); }
    50%  { transform: translate(-5%, 3%) rotate(3deg); }
    100% { transform: translate(3%, -2%) rotate(-2deg); }
}

/* ── Floating grid lines ──────────────────────────────────────────────── */
.grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

/* ── Glassmorphism card ───────────────────────────────────────────────── */
.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform, box-shadow;
}
.glass-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-4px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* ── Logo pulse ring ──────────────────────────────────────────────────── */
.logo-ring {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.logo-ring::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid rgba(129, 140, 248, 0.3);
    animation: pulse-ring 3s ease-out infinite;
}
@keyframes pulse-ring {
    0%   { transform: scale(1);   opacity: 0.6; }
    100% { transform: scale(1.8); opacity: 0; }
}

/* ── Navbar logo image ───────────────────────────────────────────────── */
.nav-logo-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    transition: transform 0.3s ease, filter 0.3s ease;
}
.nav-logo-img:hover {
    transform: scale(1.1);
    filter: brightness(0) invert(1) drop-shadow(0 0 8px rgba(129, 140, 248, 0.6));
}

/* ── Hero logo image ─────────────────────────────────────────────────── */
.hero-logo {
    width: 220px;
    height: 220px;
    object-fit: contain;
    filter: brightness(0) invert(1) drop-shadow(0 0 30px rgba(129, 140, 248, 0.3));
    animation: logoFloat 6s ease-in-out infinite, logoGlow 4s ease-in-out infinite alternate;
}
@keyframes logoFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-10px); }
}
@keyframes logoGlow {
    0%   { filter: brightness(0) invert(1) drop-shadow(0 0 20px rgba(129, 140, 248, 0.2)); }
    100% { filter: brightness(0) invert(1) drop-shadow(0 0 40px rgba(168, 85, 247, 0.5)); }
}

/* ── Feature icon ─────────────────────────────────────────────────────── */
.feature-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.glass-card:hover .feature-icon {
    transform: scale(1.1) rotate(-3deg);
}

/* ── Animated border gradient for version badge ───────────────────────── */
.version-badge {
    background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(168,85,247,0.15));
    border: 1px solid rgba(129,140,248,0.3);
    border-radius: 9999px;
    padding: 6px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    color: #c4b5fd;
    text-transform: uppercase;
}

/* ── Link hover animation ─────────────────────────────────────────────── */
.nav-link {
    position: relative;
    color: rgba(255,255,255,0.7);
    transition: color 0.3s;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
}
.nav-link:hover { color: #fff; }
.nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #818cf8, #a78bfa);
    border-radius: 2px;
    transition: width 0.3s ease;
}
.nav-link:hover::after { width: 100%; }

/* ── CTA button ───────────────────────────────────────────────────────── */
.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    text-decoration: none;
}
.cta-primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.35);
}
.cta-primary:hover {
    box-shadow: 0 6px 30px rgba(99, 102, 241, 0.5);
    transform: translateY(-2px);
}
.cta-outline {
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.85);
}
.cta-outline:hover {
    border-color: rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.05);
    color: #fff;
}

/* ── Fade-in animation ────────────────────────────────────────────────── */
.fade-up {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeUp 0.8s ease forwards;
}
.fade-up-d1 { animation-delay: 0.1s; }
.fade-up-d2 { animation-delay: 0.25s; }
.fade-up-d3 { animation-delay: 0.4s; }
.fade-up-d4 { animation-delay: 0.55s; }
.fade-up-d5 { animation-delay: 0.7s; }
.fade-up-d6 { animation-delay: 0.85s; }

@keyframes fadeUp {
    to { opacity: 1; transform: translateY(0); }
}

/* ── Code snippet styling ─────────────────────────────────────────────── */
.code-block {
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 24px 28px;
    font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
    font-size: 0.85rem;
    line-height: 1.8;
    color: #e2e8f0;
    overflow-x: auto;
}
.code-block .keyword { color: #c084fc; }
.code-block .string  { color: #86efac; }
.code-block .comment { color: #64748b; }
.code-block .func    { color: #67e8f9; }
.code-block .arrow   { color: #f472b6; }
</style>

<div class="umay-hero">
    <div class="grid-overlay"></div>

    <!-- ── Navigation ──────────────────────────────────────────────────────── -->
    <nav class="relative z-10 flex items-center justify-between max-w-6xl mx-auto px-6 py-6 fade-up fade-up-d1">
        <div class="flex items-center gap-3">
            <div class="logo-ring">
                <img src="/umay.png" alt="Umay Logo" class="nav-logo-img">
            </div>
            <span class="text-white font-bold text-xl tracking-tight">Umay</span>
        </div>

        <div class="hidden sm:flex items-center gap-8">
            <a href="https://github.com/malisahin89/umay" target="_blank" class="nav-link">
                <i class="fab fa-github mr-1"></i> GitHub
            </a>
            <a href="https://github.com/malisahin89/umay#readme" target="_blank" class="nav-link">Dökümanlar</a>
        </div>
    </nav>

    <!-- ── Hero Content ────────────────────────────────────────────────────── -->
    <div class="relative z-10 max-w-6xl mx-auto px-6 pt-12 sm:pt-24 pb-20">

        <div class="text-center mb-16">
            <div class="fade-up fade-up-d2" style="margin-bottom: 24px;">
                <div class="logo-ring" style="display: inline-flex;">
                    <img src="/umay.png" alt="Umay Framework Logo" class="hero-logo">
                </div>
            </div>

            <div class="fade-up fade-up-d2">
                <span class="version-badge">
                    <i class="fas fa-rocket mr-1"></i>
                    v<?= htmlspecialchars(config('app.version', '1.0.0')) ?> · PHP <?= PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION ?>
                </span>
            </div>

            <h1 class="mt-8 text-4xl sm:text-6xl lg:text-7xl font-extrabold text-white leading-tight tracking-tight fade-up fade-up-d3">
                Keep It Simple
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Yet Powerful
                </span>
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed fade-up fade-up-d4">
                Umay, sıfırdan inşa edilmiş minimal ve güçlü bir PHP MVC framework'üdür.
                Eloquent ORM, middleware pipeline, service container ve daha fazlası — hepsi kutunun içinde.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 fade-up fade-up-d5">
                <a href="https://github.com/malisahin89/umay" target="_blank" class="cta-btn cta-primary">
                    <i class="fab fa-github"></i> Keşfet
                </a>
                <a href="https://github.com/malisahin89/umay#readme" target="_blank" class="cta-btn cta-outline">
                    <i class="fas fa-book"></i> Dökümanlar
                </a>
            </div>
        </div>

        <!-- ── Code Preview (Tabbed) ──────────────────────────────────────── -->
        <div class="max-w-6xl mx-auto mb-20 fade-up fade-up-d5">
            <div class="code-block" style="padding: 0; overflow: hidden;">
                <!-- Title bar with tabs -->
                <div style="display:flex; align-items:center; background:rgba(0,0,0,0.3); border-bottom:1px solid rgba(255,255,255,0.06);">
                    <div style="display:flex; align-items:center; gap:8px; padding:14px 16px; flex-shrink:0;">
                        <span style="width:12px;height:12px;border-radius:50%;background:#ff5f57;"></span>
                        <span style="width:12px;height:12px;border-radius:50%;background:#febc2e;"></span>
                        <span style="width:12px;height:12px;border-radius:50%;background:#28c840;"></span>
                    </div>
                    <div style="display:flex; margin-left:4px; overflow-x:auto; -webkit-overflow-scrolling:touch;" id="codeTabs">
                        <button type="button" class="code-tab active" data-tab="web">web.php</button>
                        <button type="button" class="code-tab" data-tab="api">api.php</button>
                        <button type="button" class="code-tab" data-tab="controller">Controller</button>
                        <button type="button" class="code-tab" data-tab="model">Model</button>
                        <button type="button" class="code-tab" data-tab="request">FormRequest</button>
                        <button type="button" class="code-tab" data-tab="middleware">Middleware</button>
                        <button type="button" class="code-tab" data-tab="mail">Mail</button>
                        <button type="button" class="code-tab" data-tab="migration">Migration</button>
                    </div>
                </div>

                <!-- web.php -->
                <div id="code-web" class="code-panel" style="display:flex;">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div></div>
                    <div class="code-content">
<div><span class="comment">// Elegant & expressive routing</span></div>
<div><span class="keyword">Route</span>::<span class="func">view</span>(<span class="string">'/'</span>, <span class="string">'welcome'</span>)<span class="arrow">-></span><span class="func">name</span>(<span class="string">'home'</span>);</div>
<div><span class="keyword">Route</span>::<span class="func">get</span>(<span class="string">'/dashboard'</span>, <span class="string">'DashboardController@index'</span>)<span class="arrow">-></span><span class="func">middleware</span>(<span class="string">'auth'</span>);</div>
<div style="height:2.2em;"></div>
<div><span class="keyword">Route</span>::<span class="func">prefix</span>(<span class="string">'/admin'</span>)<span class="arrow">-></span><span class="func">middleware</span>(<span class="string">'admin'</span>)<span class="arrow">-></span><span class="func">group</span>(<span class="keyword">function</span> () {</div>
<div>    <span class="keyword">Route</span>::<span class="func">resource</span>(<span class="string">'users'</span>, <span class="string">'UserController'</span>);</div>
<div>});</div>
                    </div>
                </div>

                <!-- api.php -->
                <div id="code-api" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div></div>
                    <div class="code-content">
<div><span class="comment">// Auto-prefixed with /api — stateless & fast</span></div>
<div><span class="keyword">Route</span>::<span class="func">apiResource</span>(<span class="string">'products'</span>, <span class="string">'Api\ProductController'</span>);</div>
<div><span class="keyword">Route</span>::<span class="func">get</span>(<span class="string">'/me'</span>, <span class="string">'Api\UserController@me'</span>)<span class="arrow">-></span><span class="func">middleware</span>(<span class="string">'api-auth'</span>);</div>
                    </div>
                </div>

                <!-- Controller -->
                <div id="code-controller" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div></div>
                    <div class="code-content">
<div><span class="keyword">class</span> <span class="func">DashboardController</span></div>
<div>{</div>
<div>    <span class="keyword">public function</span> <span class="func">index</span>()</div>
<div>    {</div>
<div>        <span class="keyword">$user</span> = <span class="func">auth</span>();</div>
<div style="height:2.2em;"></div>
<div>        <span class="keyword">View</span>::<span class="func">render</span>(<span class="string">'dashboard'</span>, [</div>
<div>            <span class="string">'title'</span> <span class="arrow">=></span> <span class="string">'Dashboard'</span>,</div>
<div>            <span class="string">'user'</span>  <span class="arrow">=></span> <span class="keyword">$user</span>,</div>
<div>        ]);</div>
<div>    }</div>
                    </div>
                </div>

                <!-- Model -->
                <div id="code-model" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div><div>12</div><div>13</div></div>
                    <div class="code-content">
<div><span class="keyword">class</span> <span class="func">User</span> <span class="keyword">extends</span> <span class="func">Model</span></div>
<div>{</div>
<div>    <span class="keyword">protected</span> <span class="keyword">$fillable</span> = [<span class="string">'name'</span>, <span class="string">'email'</span>, <span class="string">'password'</span>];</div>
<div>    <span class="keyword">protected</span> <span class="keyword">$hidden</span>   = [<span class="string">'password'</span>];</div>
<div style="height:2.2em;"></div>
<div>    <span class="comment">// Mutator: auto-hash password</span></div>
<div>    <span class="keyword">public function</span> <span class="func">setPasswordAttribute</span>(<span class="keyword">$value</span>) {</div>
<div>        <span class="keyword">$this</span><span class="arrow">-></span>attributes[<span class="string">'password'</span>] = <span class="func">password_hash</span>(<span class="keyword">$value</span>, PASSWORD_DEFAULT);</div>
<div>    }</div>
<div style="height:2.2em;"></div>
<div>    <span class="comment">// Relation: one-to-many</span></div>
<div>    <span class="keyword">public function</span> <span class="func">posts</span>() {</div>
<div>        <span class="keyword">return</span> <span class="keyword">$this</span><span class="arrow">-></span><span class="func">hasMany</span>(<span class="func">Post</span>::<span class="keyword">class</span>);</div>
                    </div>
                </div>

                <!-- FormRequest -->
                <div id="code-request" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div><div>12</div><div>13</div><div>14</div></div>
                    <div class="code-content">
<div><span class="keyword">class</span> <span class="func">StoreUserRequest</span> <span class="keyword">extends</span> <span class="func">FormRequest</span></div>
<div>{</div>
<div>    <span class="keyword">public function</span> <span class="func">rules</span>(): <span class="keyword">array</span></div>
<div>    {</div>
<div>        <span class="keyword">return</span> [</div>
<div>            <span class="string">'name'</span>     <span class="arrow">=></span> <span class="string">'required|min:2'</span>,</div>
<div>            <span class="string">'email'</span>    <span class="arrow">=></span> <span class="string">'required|email|unique:users'</span>,</div>
<div>            <span class="string">'password'</span> <span class="arrow">=></span> <span class="string">'required|min:8|confirmed'</span>,</div>
<div>        ];</div>
<div>    }</div>
<div>}</div>
<div style="height:2.2em;"></div>
<div><span class="comment">// Controller — inject edilince otomatik validate edilir</span></div>
<div><span class="keyword">public function</span> <span class="func">store</span>(<span class="func">StoreUserRequest</span> <span class="keyword">$request</span>) {</div>
<div>    <span class="keyword">User</span>::<span class="func">create</span>(<span class="keyword">$request</span><span class="arrow">-></span><span class="func">validated</span>());</div>
                    </div>
                </div>

                <!-- Middleware -->
                <div id="code-middleware" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div></div>
                    <div class="code-content">
<div><span class="keyword">class</span> <span class="func">AuthMiddleware</span> <span class="keyword">implements</span> <span class="func">MiddlewareInterface</span></div>
<div>{</div>
<div>    <span class="keyword">public function</span> <span class="func">handle</span>(<span class="func">Request</span> <span class="keyword">$request</span>, <span class="func">Closure</span> <span class="keyword">$next</span>): <span class="keyword">mixed</span></div>
<div>    {</div>
<div>        <span class="keyword">if</span> (<span class="func">empty</span>(<span class="keyword">$_SESSION</span>[<span class="string">'user_id'</span>])) {</div>
<div>            <span class="func">redirect</span>(<span class="string">'login.show'</span>);</div>
<div>            <span class="keyword">return null</span>;</div>
<div>        }</div>
<div style="height:2.2em;"></div>
<div>        <span class="keyword">return</span> <span class="keyword">$next</span>(<span class="keyword">$request</span>);</div>
                    </div>
                </div>

                <!-- Mail -->
                <div id="code-mail" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div><div>12</div><div>13</div></div>
                    <div class="code-content">
<div><span class="keyword">class</span> <span class="func">WelcomeMail</span> <span class="keyword">extends</span> <span class="func">Mailable</span></div>
<div>{</div>
<div>    <span class="keyword">public function</span> <span class="func">__construct</span>(<span class="keyword">private</span> <span class="func">User</span> <span class="keyword">$user</span>) {}</div>
<div style="height:2.2em;"></div>
<div>    <span class="keyword">public function</span> <span class="func">build</span>(): <span class="keyword">static</span></div>
<div>    {</div>
<div>        <span class="keyword">return</span> <span class="keyword">$this</span><span class="arrow">-></span><span class="func">subject</span>(<span class="string">'Hoş geldin!'</span>)</div>
<div>                    <span class="arrow">-></span><span class="func">view</span>(<span class="string">'emails/welcome'</span>, [<span class="string">'user'</span> <span class="arrow">=></span> <span class="keyword">$this</span><span class="arrow">-></span>user]);</div>
<div>    }</div>
<div>}</div>
<div style="height:2.2em;"></div>
<div><span class="comment">// Gönder</span></div>
<div><span class="keyword">Mailer</span>::<span class="func">to</span>(<span class="keyword">$user</span><span class="arrow">-></span>email)<span class="arrow">-></span><span class="func">send</span>(<span class="keyword">new</span> <span class="func">WelcomeMail</span>(<span class="keyword">$user</span>));</div>
                    </div>
                </div>

                <!-- Migration -->
                <div id="code-migration" class="code-panel">
                    <div class="line-numbers"><div>1</div><div>2</div><div>3</div><div>4</div><div>5</div><div>6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div><div>12</div></div>
                    <div class="code-content">
<div><span class="keyword">return new class extends</span> <span class="func">Migration</span></div>
<div>{</div>
<div>    <span class="keyword">public function</span> <span class="func">up</span>(): <span class="keyword">void</span></div>
<div>    {</div>
<div>        <span class="keyword">DB</span>::<span class="func">schema</span>()<span class="arrow">-></span><span class="func">create</span>(<span class="string">'posts'</span>, <span class="keyword">function</span> (<span class="func">Blueprint</span> <span class="keyword">$table</span>) {</div>
<div>            <span class="keyword">$table</span><span class="arrow">-></span><span class="func">increments</span>(<span class="string">'id'</span>);</div>
<div>            <span class="keyword">$table</span><span class="arrow">-></span><span class="func">string</span>(<span class="string">'title'</span>);</div>
<div>            <span class="keyword">$table</span><span class="arrow">-></span><span class="func">text</span>(<span class="string">'body'</span>);</div>
<div>            <span class="keyword">$table</span><span class="arrow">-></span><span class="func">timestamps</span>();</div>
<div>        });</div>
<div>    }</div>
<div>};</div>
                    </div>
                </div>

            </div>
        </div>

        <style nonce="<?= $this->nonce() ?>">
        .code-tab {
            padding: 10px 18px;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.02em;
            border: none;
            cursor: pointer;
            background: transparent;
            color: rgba(255,255,255,0.3);
            border-bottom: 2px solid transparent;
            border-radius: 6px 6px 0 0;
            transition: all 0.25s ease;
            white-space: nowrap;
            font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
        }
        .code-tab:hover { color: rgba(255,255,255,0.6); }
        .code-tab.active {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
            border-bottom-color: #818cf8;
        }
        .code-panel {
            display: none;
            padding: 24px 0;
        }
        .code-panel .line-numbers {
            padding: 0 16px 0 20px;
            text-align: right;
            user-select: none;
            border-right: 1px solid rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.15);
            font-size: 0.8rem;
            line-height: 1.6;
        }
        .code-panel .code-content {
            padding: 0 24px;
            font-size: 0.85rem;
            line-height: 1.6;
            overflow-x: auto;
            flex: 1;
        }
        .code-panel .code-content div {
            white-space: pre;
        }
        </style>

        <script nonce="<?= $this->nonce() ?>">
        (function () {
            function switchTab(tab) {
                var panels = document.querySelectorAll('.code-panel');
                var tabs = document.querySelectorAll('.code-tab');

                panels.forEach(function(p) { p.style.display = 'none'; });
                tabs.forEach(function(t) { t.classList.remove('active'); });

                document.getElementById('code-' + tab).style.display = 'flex';
                document.querySelector('[data-tab="' + tab + '"]').classList.add('active');
            }

            document.querySelectorAll('#codeTabs .code-tab').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    switchTab(btn.getAttribute('data-tab'));
                });
            });
        })();
        </script>

        <!-- ── Feature Cards ───────────────────────────────────────────────── -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 fade-up fade-up-d6">

            <div class="glass-card p-6">
                <div class="feature-icon bg-indigo-500/15 text-indigo-400 mb-4">
                    <i class="fas fa-route"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Expressive Routing</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Named routes, prefix groups, resource & apiResource, middleware ve method spoofing — sezgisel syntax.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-purple-500/15 text-purple-400 mb-4">
                    <i class="fas fa-database"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Eloquent ORM</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    illuminate/database entegrasyonu ile migration, seeder, factory, soft deletes ve güçlü model ilişkileri.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-cyan-500/15 text-cyan-400 mb-4">
                    <i class="fas fa-cube"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Service Container</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Otomatik dependency injection, singleton binding, facade katmanı ve service provider pattern desteği.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-rose-500/15 text-rose-400 mb-4">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Authentication</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Eloquent user provider, session tabanlı login, "beni hatırla" (remember me) ve password hashing — kutunun içinde.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-sky-500/15 text-sky-400 mb-4">
                    <i class="fas fa-key"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">API Tokens</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Sanctum benzeri personal access token'lar — ability/scope desteği, expiry ve <code class="text-sky-300">HasApiTokens</code> trait'i.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-teal-500/15 text-teal-400 mb-4">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Validation</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Zengin kural seti, özel mesajlar ve <code class="text-teal-300">FormRequest</code> ile otomatik doğrulama + authorize.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-pink-500/15 text-pink-400 mb-4">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Middleware Pipeline</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Auth, Throttle, CORS, RememberMe, ApiAuth — zincirleme middleware ile her isteği filtreleyin.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-red-500/15 text-red-400 mb-4">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Security by Default</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    CSRF koruması, nonce tabanlı CSP, güvenlik başlıkları ve rate limiting — production'da strict politika.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-violet-500/15 text-violet-400 mb-4">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Mail</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Fluent <code class="text-violet-300">Mailable</code> API, view tabanlı gövde, ekler ve takılabilir transport'lar.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-fuchsia-500/15 text-fuchsia-400 mb-4">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Events & Listeners</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Dispatcher tabanlı event sistemi, listener kaydı ve <code class="text-fuchsia-300">EventServiceProvider</code> ile gevşek bağlılık.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-emerald-500/15 text-emerald-400 mb-4">
                    <i class="fas fa-terminal"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">CLI Console</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    make:controller, make:model, migrate — artisan benzeri komut satırı araçları ile üretkenliğinizi artırın.
                </p>
            </div>

            <div class="glass-card p-6">
                <div class="feature-icon bg-amber-500/15 text-amber-400 mb-4">
                    <i class="fas fa-gauge-high"></i>
                </div>
                <h3 class="text-white font-semibold text-lg mb-2">Debug Profiler</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Dahili profiler toolbar ile query, route, middleware ve memory kullanımını gerçek zamanlı izleyin.
                </p>
            </div>

        </div>
    </div>

    <!-- ── Footer ──────────────────────────────────────────────────────────── -->
    <footer class="relative z-10 border-t border-white/5 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-sm">
                &copy; <?= date('Y') ?> Umay Framework v<?= htmlspecialchars(config('app.version', '1.0.0')) ?>
                · Crafted by
                <a href="https://malisahin.com" target="_blank" class="text-indigo-400 hover:text-indigo-300 transition">Muhammet Ali Şahin</a>
            </p>
            <div class="flex items-center gap-6">
                <a href="https://github.com/malisahin89/umay" target="_blank"
                   class="text-gray-500 hover:text-white transition text-sm">
                    <i class="fab fa-github mr-1"></i> Source
                </a>
                <?php // Tam PHP sürümü yalnızca local'de — production'da sürüm ifşası
                      // (bilinen CVE'lerle eşleştirme) kolaylaştırmamalı.
                      // Full PHP version only in local — in production it would ease
                      // version disclosure (matching against known CVEs). ?>
                <?php if (config('app.env') === 'local') { ?>
                <span class="text-gray-600 text-sm">
                    PHP <?= PHP_VERSION ?>
                </span>
                <?php } ?>
            </div>
        </div>
    </footer>
</div>

<?php $this->end() ?>
