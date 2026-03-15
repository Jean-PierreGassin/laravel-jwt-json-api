# API

Base URL: `http://localhost:8000/api`

Register:
```bash
curl -X POST http://localhost:8000/api/user/register \
  -H "Content-Type: application/json" \
  -d '{"data":{"type":"user","attributes":{"name":"Jane","email":"jane@example.com","password":"secret123"}}}'
```

Login:
```bash
curl -X POST http://localhost:8000/api/user/login \
  -H "Content-Type: application/json" \
  -d '{"data":{"type":"user","attributes":{"email":"jane@example.com","password":"secret123"}}}'
```

Response includes a JWT:
```json
{
  "data": [
    {
      "type": "authtoken",
      "attributes": {
        "token": "..."
      }
    }
  ]
}
```

Current user:
```bash
curl http://localhost:8000/api/me \
  -H "Authorization: Bearer <token>"
```
