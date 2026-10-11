# CSRF Protection (Cross-Site Request Forgery)

Umay Framework automatically prevents your forms from being filled with fake requests (CSRF) by malicious actors.

The system automatically checks for the presence and validity of a token named `csrf_token` in POST, PUT, PATCH, and DELETE requests.

## Adding CSRF Token

In your HTML forms, you must add a CSRF token field for the request to be accepted as secure by Umay.

Use the `$this->csrf()` method in Plates view files:

```html
<form method="POST" action="/profile/update">
    <!-- Token field for security -->
    <?= $this->csrf() ?>
    
    <label>Name:</label>
    <input type="text" name="name">
    
    <button type="submit">Update</button>
</form>
```

`$this->csrf()` produces the following HTML output in the background:
```html
<input type="hidden" name="csrf_token" value="b4f6...9a21">
```

> [!CAUTION]  
> You do not need to add a CSRF token to forms that work with the GET method (e.g., Search forms), as GET requests should not cause permanent data changes on the server.

## CSRF in AJAX Requests

When sending AJAX requests in the background using Javascript (e.g., Fetch API, Axios, jQuery), you can send the token as a `Header` instead of adding it to the form data. Umay automatically recognizes the `X-CSRF-TOKEN` header.

Keep the token in a meta tag in the `<head>` section of your page:
```html
<meta name="csrf-token" content="<?= csrf_token() ?>">
```

Then, retrieve this token and send it as a header using libraries like Axios:
```javascript
let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

axios.post('/api/endpoint', data, {
    headers: {
        'X-CSRF-TOKEN': token
    }
});
```

If the sent token is invalid, missing, or the session has expired, the application automatically throws a `TokenMismatchException` and blocks the request.

(End of file - total 53 lines)
