# Profiler (Debug Toolbar)

Umay Framework has a built-in application profiling system to speed up the development process.

## What is the Profiler?

The Profiler tracks events occurring during each HTTP request (SQL queries, Route matches, Middleware execution times, memory usage) and presents them as a report.

## Activation

To activate the Profiler, set the following in your `.env` file:

```env
PROFILER_ENABLED=true
```

Or manage it via `config/profiler.php`.

## Usage and Monitoring

When the Profiler is enabled, a compact toolbar appears at the bottom of web pages.

1. **Toolbar**: Displays instant metrics at the bottom of the page.
2. **Detailed Report**: When you click the link on the toolbar, the HTML version of the detailed JSON report created for that request opens (`/_profiler/{token}`).

## Technical Details

- **Data Storage**: A unique token is created for each request and data is saved as JSON in the `storage/profiler/` directory.
- **Automatic Cleanup**: The Profiler performs automatic cleanup when too many records are created or for old records.
- **Performance**: Controlled via the `UMAY_PROFILING` constant, it creates almost zero load on the system when disabled.
