# Çağrı Grafiği

## Amaç
Bir isteğin temel çalışma zamanı çağrı akışını belgeler. Anlatımlı detaylar için `DOCS/REQUEST_LIFECYCLE.md` ve `DOCS/BOOT_PROCESS.md` dosyalarına bakın.

## Front Controller $\rightarrow$ Dağıtım (`public/index.php`)
1. `BASE_PATH` tanımlanır; `vendor/autoload.php`, `config/database.php`, `core/helpers.php` gereksinimleri yüklenir.
2. `Profiler::init()`; `UMAY_PROFILING = Profiler::isEnabled()` tanımlanır.
3. API ile web ayrımı tespit edilir (`config('middleware.api_prefix')`).
4. Yalnızca web için: güvenli oturum ini bayrakları ayarlanır, `session_name()`, `session_start()`, boşta kalma zaman aşımı kontrolü (`config('session.lifetime')`) yapılır, `last_activity` kaydedilir.
5. `date_default_timezone_set(config('app.timezone'))`.
6. Etkin olduğunda `register_shutdown_function` $\rightarrow$ `Profiler::finish()`.
7. Boot: `Application::getInstance()` $\rightarrow$ `captureRequest()` $\rightarrow$ `register(FacadeServiceProvider, EventServiceProvider, RouteServiceProvider)` $\rightarrow$ `boot()`.
8. try/catch içinde `Route::dispatch()`; `Throwable` durumunda $\rightarrow$ `$app->handleException($e)`.

## Boot (`Core\Application`)
- `getInstance()`, `Container::getInstance()` üzerinden oluşturulur.
- `captureRequest()` $\rightarrow$ `Request::capture()` bir `Request::class` örneği olarak bağlanır.
- `register($provider)`, sağlayıcıyı örnekler (bir `ServiceProvider` olmalıdır) ve `register()` metodunu çalıştırır.
- `boot()`, her sağlayıcının `boot()` metodunu bir kez çalıştırır (`$booted` koruması). `RouteServiceProvider::boot()`, `routes/web.php` + `routes/api.php` dosyalarını yükler.

## Dağıtım (`Core\Route::dispatch`)
- Metot + URI'yi derlenmiş rotalarla eşleştirir $\rightarrow$ middleware'leri çözer (`config('middleware.namespaces')` aracılığıyla) $\rightarrow$ middleware hattını çalıştırır (`handle($request, $next)`) $\rightarrow$ kontrolcü eylemini çağırır (`config('app.controller_namespace')` aracılığıyla çözülen `Controller@method` dizgisi veya bir closure) $\rightarrow$ yanıtı gönderir.

## Hata Yolu
- Herhangi bir `Throwable` $\rightarrow$ `Application::handleException()` $\rightarrow$ `ExceptionHandler::handle()` (bağlanmışsa konteynerden çözülür) $\rightarrow$ web HTML hata sayfası veya API JSON (bkz. `DOCS/ERROR_HANDLING.md`).

## İşleme (Rendering) Yolu
- Kontrolcü $\rightarrow$ `View::render($template, $data)` $\rightarrow$ Plates motoru $\rightarrow$ (etkin olduğunda profiler ölçümleri + araç çubuğu eklemesi) $\rightarrow$ echo.

## Çapraz Referanslar
- `DOCS/REQUEST_LIFECYCLE.md`, `DOCS/BOOT_PROCESS.md`, `DOCS/ROUTING_SYSTEM.md`, `DOCS/ERROR_HANDLING.md`, `DOCS/VIEW_ENGINE.md`

## Kaynak Referansları
- `public/index.php:1-125`
- `core/Application.php:52-165`
