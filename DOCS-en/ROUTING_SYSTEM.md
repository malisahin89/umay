# Routing System

This report explains the implementation and functionality of the routing engine in the Umay framework.

## 1. Route Definition
Routes are defined using a fluent static API provided by `Core\Route`.

### Supported Methods
The router provides helper methods for standard HTTP verbs:
- `Route::get($uri, $action)`
- `Route::post($uri, $action)`
- `Route::put($uri, $action)`
- `Route::patch($uri, $action)`
- `Route::delete($uri, $action)`
- `Route::match($methods, $uri, $action)`: Defines a route for multiple methods.
- `Route::any($uri, $action)`: Defines a route for all methods.

### Specialized Routes
- **View Routes**: `Route::view($uri, $view, $data)` renders a view directly.
- **Redirect Routes**: `Route::redirect($from, $to, $status)` performs a direct HTTP redirect.
- **Resource Routes**: `Route::resource()` and `Route::apiResource()` generate a set of RESTful routes for a controller using the `Core\ResourceRegistrar`.

## 2. Route Grouping & Organization
The router supports logical grouping of routes to share common attributes.

### Prefixes
`Route::prefix($prefix)` adds a URI segment to all routes defined within the following group.

### Middleware
`Route::middleware($name)` attaches one or more middleware to the route or group.

### Groups
`Route::group($callback)` wraps routes and restores the prefix and middleware stacks after the callback executes.

### Named Routes
Routes can be named via `->name($name)`, allowing for reverse URL generation using `Route::url($name, $params)`.

## 3. Dispatching Process
When `Route::dispatch()` is called, the following steps occur:

### URI Normalization & Redirection
- The request URI is trimmed of trailing slashes.
- If a GET/HEAD request has a trailing slash and a matching slashless route exists, a 301 redirect is issued to the canonical URI.

### Route Matching
1. **Exact Match**: The router first checks if the URI exists as a literal key in the routes table.
2. **Pattern Match**: If no exact match is found, the router iterates through routes containing placeholders (e.g., `{id}`). These are matched using pre-compiled regular expressions.

### Action Resolution
The router determines how to handle the request based on the action type:
- **Closures**: Executed directly.
- **Controllers**: Resolved via `config('app.controller_namespace')` and instantiated through the `Core\Container`.
- **Pseudo-actions**: `_view` and `_redirect` are handled by internal logic.

## 4. Middleware Pipeline
Umay implements a "Pipeline" pattern using `array_reduce`. 
1. **Collection**: The router gathers global, group, and route-specific middleware.
2. **Wrapping**: Middleware are wrapped in closures, creating a nested chain (onion architecture).
3. **Execution**: The request passes through each middleware. Each middleware must call the `$next` closure to proceed to the next layer or the final action.

## 5. Parameter Injection
The router uses PHP Reflection to analyze the action's method parameters:
- **Request Injection**: `Core\Request` is automatically injected.
- **Form Request**: Subclasses of `Request` are instantiated and validated.
- **Route Parameters**: Placeholders from the URI are cast to the declared scalar type (`int`, `float`, `bool`) and injected.
- **Container Resolution**: Any other type-hinted classes are resolved via the `Core\Container`.
