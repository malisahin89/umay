# Validation

Umay Framework has a powerful and flexible `Validator` engine to quickly verify incoming data.

## Basic Usage

The `validate()` helper function is used to verify data:

```php
$data = $_POST;
$rules = [
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8',
    'age' => 'numeric|min:18|max:99',
];

$messages = [
    'email.required' => 'Email address is required.',
    'email.unique' => 'This email is already registered.',
];

if (!validate($data, $rules, $messages)) {
    // In case of error, redirect or return errors
    return redirect('register.show')->withErrors(errors());
}
```

## Supported Rules

| Rule | Description |
| :--- | :--- |
| `required` | Forces the field to be present and not empty. |
| `sometimes` | Validate if the field is present, otherwise skip. |
| `nullable` | Skips the remaining rules on an empty value (null/''/[]). |
| `array` | Value must be an array. |
| `email` | Must be a valid email format. |
| `numeric` | Value must be numeric. |
| `min:value` | Minimum length or value. |
| `max:value` | Maximum length or value. |
| `unique:table,column` | Must be unique in the database. |
| `exists:table,column` | Record must exist in the database. |
| `confirmed` | Must match the `field_confirmation` field. |
| `regex:pattern` | Must match the specified regular expression. |

> [!NOTE]
> An unsupported or misspelled rule (e.g. `between`) throws a `LogicException` at runtime; typos do not pass silently.

## Using FormRequest

For cleaner controllers, you can create custom request classes derived from the `Core\FormRequest` class. These classes automatically run validation before entering the controller method.
