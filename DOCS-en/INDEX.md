# Project Documentation Index

Complete reverse-engineering documentation of the **Umay** framework — a minimal, from-scratch PHP MVC framework (PHP ≥ 8.2). This index links every generated report.

## General Reports

### Group A — Architecture & Lifecycle
- [Architecture](ARCHITECTURE.md)
- [Framework Features](FRAMEWORK_FEATURES.md)
- [Request Lifecycle](REQUEST_LIFECYCLE.md)
- [Boot Process](BOOT_PROCESS.md)
- [Package Structure](PACKAGE_STRUCTURE.md)

### Group B — Core Subsystems
- [Routing System](ROUTING_SYSTEM.md)
- [Container](CONTAINER.md)
- [Service Providers](SERVICE_PROVIDERS.md)
- [Middleware](MIDDLEWARE.md)
- [Database](DATABASE.md)
- [ORM](ORM.md)

### Group C — Runtime & Configuration Services
- [Configuration](CONFIGURATION.md)
- [Cache](CACHE.md)
- [Session](SESSION.md)
- [Cookies](COOKIE.md)
- [Filesystem](FILESYSTEM.md)

### Group D — Security & Access Control
- [Security](SECURITY.md)
- [Authentication](AUTHENTICATION.md)
- [Authorization](AUTHORIZATION.md)
- [Validation](VALIDATION.md)

### Group E — Presentation
- [View Engine](VIEW_ENGINE.md)
- [Template Engine](TEMPLATE_ENGINE.md)

### Group F — Diagnostics & Performance
- [Error Handling](ERROR_HANDLING.md)
- [Logging](LOGGING.md)
- [Performance & Profiling](PERFORMANCE.md)

### Group G — Graphs
- [Dependency Graph](DEPENDENCY_GRAPH.md)
- [Class Graph](CLASS_GRAPH.md)
- [Call Graph](CALL_GRAPH.md)

## Index & Matrix Reports
- [Class Index](CLASS_INDEX.md)
- [Method Index](METHOD_INDEX.md)
- [Route Matrix](ROUTE_MATRIX.md)
- [Configuration Matrix](CONFIGURATION_MATRIX.md)
- [Service Matrix](SERVICE_MATRIX.md)
- [Glossary](GLOSSARY.md)

## Root
- [Root Directory Report](ROOT.md)
- Files: [.env.example](.env.example.md) · [.gitattributes](.gitattributes.md) · [.gitignore](.gitignore.md) · [.htaccess](.htaccess.md) · [composer.json](composer.json.md) · [composer.lock](composer.lock.md) · [LICENSE](LICENSE.md) · [phpstan.neon](phpstan.neon.md) · [phpstan-baseline.neon](phpstan-baseline.neon.md) · [phpunit.xml](phpunit.xml.md)

## Directory & File Reports

### app/ — [dir](app/index.md)
- [.htaccess](app/.htaccess.md)
- Controllers/ — [dir](app/Controllers/index.md) · [Controller](app/Controllers/Controller.md)
- Middleware/ — [dir](app/Middleware/index.md) · [ThrottleMiddleware](app/Middleware/ThrottleMiddleware.md)
- Models/ — [dir](app/Models/index.md) · [User](app/Models/User.md)
- Providers/ — [dir](app/Providers/index.md) · [EventServiceProvider](app/Providers/EventServiceProvider.md) · [RouteServiceProvider](app/Providers/RouteServiceProvider.md)
- Services/ — [dir](app/Services/index.md)

### config/ — [dir](config/index.md)
- [.htaccess](config/.htaccess.md) · [app](config/app.md) · [auth](config/auth.md) · [cache](config/cache.md) · [database](config/database.md) · [mail](config/mail.md) · [middleware](config/middleware.md) · [profiler](config/profiler.md) · [session](config/session.md)

### core/ — [dir](core/index.md)
- [.htaccess](core/.htaccess.md) · [Application](core/Application.md) · [Auth](core/Auth.md) · [Cache](core/Cache.md) · [Container](core/Container.md) · [Csp](core/Csp.md) · [Csrf](core/Csrf.md) · [CsrfException](core/CsrfException.md) · [Database](core/Database.md) · [DebugBar](core/DebugBar.md) · [EventServiceProvider](core/EventServiceProvider.md) · [ExceptionHandler](core/ExceptionHandler.md) · [Factory](core/Factory.md) · [FileUpload](core/FileUpload.md) · [FormRequest](core/FormRequest.md) · [helpers](core/helpers.md) · [Logger](core/Logger.md) · [Middleware (deprecated)](core/Middleware.md) · [Migration](core/Migration.md) · [Migrator](core/Migrator.md) · [Model](core/Model.md) · [Paginator](core/Paginator.md) · [RateLimiter](core/RateLimiter.md) · [Redirect](core/Redirect.md) · [RedirectException](core/RedirectException.md) · [Request](core/Request.md) · [ResourceRegistrar](core/ResourceRegistrar.md) · [Response](core/Response.md) · [ResponseBuilder](core/ResponseBuilder.md) · [Route](core/Route.md) · [Seeder](core/Seeder.md) · [ServiceProvider](core/ServiceProvider.md) · [TerminateException](core/TerminateException.md) · [Validator](core/Validator.md) · [View](core/View.md)
- Auth/ — [dir](core/Auth/index.md) · [EloquentUserProvider](core/Auth/EloquentUserProvider.md) · [HasApiTokens](core/Auth/HasApiTokens.md) · [PersonalAccessToken](core/Auth/PersonalAccessToken.md)
- Concerns/ — [dir](core/Concerns/index.md) · [SoftDeletes](core/Concerns/SoftDeletes.md)
- Console/ — [dir](core/Console/index.md) · [Kernel](core/Console/Kernel.md)
- Contracts/ — [dir](core/Contracts/index.md) · [Authenticatable](core/Contracts/Authenticatable.md) · [MailTransport](core/Contracts/MailTransport.md) · [MiddlewareInterface](core/Contracts/MiddlewareInterface.md) · [UserProvider](core/Contracts/UserProvider.md)
- Events/ — [dir](core/Events/index.md) · [Dispatcher](core/Events/Dispatcher.md) · [Event](core/Events/Event.md) · [Listener](core/Events/Listener.md)
- Exceptions/ — [dir](core/Exceptions/index.md) · [ContainerException](core/Exceptions/ContainerException.md) · [EntryNotFoundException](core/Exceptions/EntryNotFoundException.md) · [HttpException](core/Exceptions/HttpException.md)
- Facades/ — [dir](core/Facades/index.md) · [Auth](core/Facades/Auth.md) · [Cache](core/Facades/Cache.md) · [DB](core/Facades/DB.md) · [Event](core/Facades/Event.md) · [Log](core/Facades/Log.md) · [RateLimiter](core/Facades/RateLimiter.md) · [Route](core/Facades/Route.md) · [Validator](core/Facades/Validator.md) · [View](core/Facades/View.md)
- Mail/ — [dir](core/Mail/index.md) · [Mailable](core/Mail/Mailable.md) · [Mailer](core/Mail/Mailer.md) · Transport/ — [dir](core/Mail/Transport/index.md) · [LogTransport](core/Mail/Transport/LogTransport.md)
- Middleware/ — [dir](core/Middleware/index.md) · [ApiAuth](core/Middleware/ApiAuth.md) · [Cors](core/Middleware/Cors.md) · [RememberMe](core/Middleware/RememberMe.md) · [SecurityHeaders](core/Middleware/SecurityHeaders.md) · [VerifyCsrfToken](core/Middleware/VerifyCsrfToken.md)
- Profiler/ — [dir](core/Profiler/index.md) · [Profiler](core/Profiler/Profiler.md) · [ProfilerController](core/Profiler/ProfilerController.md) · [ProfilerStorage](core/Profiler/ProfilerStorage.md) · Contracts/ — [dir](core/Profiler/Contracts/index.md) · [DataCollectorInterface](core/Profiler/Contracts/DataCollectorInterface.md) · Views/ — [dir](core/Profiler/Views/index.md) · [toolbar](core/Profiler/Views/toolbar.md)
- Providers/ — [dir](core/Providers/index.md) · [FacadeServiceProvider](core/Providers/FacadeServiceProvider.md)
- Support/ — [dir](core/Support/index.md) · [Facade](core/Support/Facade.md)

### database/ — [dir](database/index.md)
- [.htaccess](database/.htaccess.md)
- factories/ — [dir](database/factories/index.md) · [UserFactory](database/factories/UserFactory.md)
- migrations/ — [dir](database/migrations/index.md) · [create_users_table](database/migrations/2026_02_28_000001_create_users_table.md) · [create_personal_access_tokens_table](database/migrations/2026_06_30_000001_create_personal_access_tokens_table.md)
- seeders/ — [dir](database/seeders/index.md) · [DatabaseSeeder](database/seeders/DatabaseSeeder.md)

### public/ — [dir](public/index.md)
- [.htaccess](public/.htaccess.md) · [index.php](public/index.php.md)
- css/ — [dir](public/css/index.md) · [style.css](public/css/style.css.md)

### routes/ — [dir](routes/index.md)
- [web](routes/web.md) · [api](routes/api.md)

### storage/ — [dir](storage/index.md)
- [.htaccess](storage/.htaccess.md)

### stubs/ — [dir](stubs/index.md)
- [controller](stubs/controller.stub.md) · [event](stubs/event.stub.md) · [factory](stubs/factory.stub.md) · [listener](stubs/listener.stub.md) · [mail](stubs/mail.stub.md) · [middleware](stubs/middleware.stub.md) · [migration](stubs/migration.stub.md) · [migration-soft-deletes](stubs/migration-soft-deletes.stub.md) · [model](stubs/model.stub.md) · [request](stubs/request.stub.md) · [test](stubs/test.stub.md)

### tests/ — [dir](tests/index.md)
- [bootstrap.php](tests/bootstrap.md) · [TestCase](tests/TestCase.md)
- Feature/ — [dir](tests/Feature/index.md) · [AuthTest](tests/Feature/AuthTest.md)
- Unit/ — [dir](tests/Unit/index.md) · [ApplicationTest](tests/Unit/ApplicationTest.md) · [ApiAuthTest](tests/Unit/ApiAuthTest.md) · [CacheTest](tests/Unit/CacheTest.md) · [ContainerFixTest](tests/Unit/ContainerFixTest.md) · [CsrfTest](tests/Unit/CsrfTest.md) · [DatabaseFixTest](tests/Unit/DatabaseFixTest.md) · [EventDispatcherTest](tests/Unit/EventDispatcherTest.md) · [ExceptionClassesTest](tests/Unit/ExceptionClassesTest.md) · [ExceptionFixTest](tests/Unit/ExceptionFixTest.md) · [FacadeServiceProviderTest](tests/Unit/FacadeServiceProviderTest.md) · [FacadeTest](tests/Unit/FacadeTest.md) · [FileUploadTest](tests/Unit/FileUploadTest.md) · [HelpersTest](tests/Unit/HelpersTest.md) · [LoggerTest](tests/Unit/LoggerTest.md) · [MailerTest](tests/Unit/MailerTest.md) · [MiddlewareInterfaceTest](tests/Unit/MiddlewareInterfaceTest.md) · [PaginatorTest](tests/Unit/PaginatorTest.md) · [RateLimiterTest](tests/Unit/RateLimiterTest.md) · [RequestFixTest](tests/Unit/RequestFixTest.md) · [RequestTest](tests/Unit/RequestTest.md) · [ResponseBuilderTest](tests/Unit/ResponseBuilderTest.md) · [RouteExtendedTest](tests/Unit/RouteExtendedTest.md) · [RouteFixTest](tests/Unit/RouteFixTest.md) · [ValidatorExtendedTest](tests/Unit/ValidatorExtendedTest.md) · [ValidatorTest](tests/Unit/ValidatorTest.md) · [ViewTest](tests/Unit/ViewTest.md)

### views/ — [dir](views/index.md)
- [welcome](views/welcome.md)
- errors/ — [dir](views/errors/index.md) · [403](views/errors/403.md) · [404](views/errors/404.md) · [500](views/errors/500.md)
- layouts/ — [dir](views/layouts/index.md) · [base](views/layouts/base.md)
- partials/ — [dir](views/partials/index.md) · [alert](views/partials/alert.md) · [button](views/partials/button.md) · [card](views/partials/card.md) · [input](views/partials/input.md)
