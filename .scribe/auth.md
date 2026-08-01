# Authenticating requests

This API uses **Laravel Sanctum** token-based authentication.

To authenticate, include your API token in the `Authorization` header:

```
Authorization: Bearer {your-token}
```

You can obtain a token via `POST /api/login` with your `email` and `password`.
