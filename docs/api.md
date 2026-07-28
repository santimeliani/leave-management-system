# Panduan API & Endpoint

## Base URL

```
http://localhost:8000
```

## Autentikasi

Semua endpoint memerlukan autentikasi kecuali `/`, `/login`, `/register`.

## Routes

### Public Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Halaman welcome |
| GET | `/login` | Form login |
| POST | `/login` | Proses login |
| GET | `/register` | Form registrasi |
| POST | `/register` | Proses registrasi |

### Profile Routes (Auth Required)

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/profile` | Edit profil |
| PATCH | `/profile` | Update profil |
| DELETE | `/profile` | Hapus akun |

### Admin Routes (role:admin)

| Method | URI | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/admin/dashboard` | `admin.dashboard` | Dashboard admin |
| GET | `/leave-types` | `leave-types.index` | Daftar jenis cuti |
| GET | `/leave-types/create` | `leave-types.create` | Form tambah jenis cuti |
| POST | `/leave-types` | `leave-types.store` | Simpan jenis cuti |
| GET | `/leave-types/{id}` | `leave-types.show` | Detail jenis cuti |
| GET | `/leave-types/{id}/edit` | `leave-types.edit` | Form edit jenis cuti |
| PUT | `/leave-types/{id}` | `leave-types.update` | Update jenis cuti |
| DELETE | `/leave-types/{id}` | `leave-types.destroy` | Hapus jenis cuti |
| GET | `/admin/leave-requests` | `admin.leave-requests.index` | Daftar semua pengajuan |
| POST | `/admin/leave-requests/{id}/approve` | `admin.leave-requests.approve` | Setujui pengajuan |
| GET | `/admin/leave-requests/{id}/reject` | `admin.leave-requests.reject.form` | Form tolak pengajuan |
| POST | `/admin/leave-requests/{id}/reject` | `admin.leave-requests.reject` | Tolak pengajuan |

### Karyawan Routes (role:karyawan)

| Method | URI | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/karyawan/dashboard` | `karyawan.dashboard` | Dashboard karyawan |
| GET | `/leave-requests` | `leave-requests.index` | Daftar pengajuan saya |
| GET | `/leave-requests/create` | `leave-requests.create` | Form ajukan cuti |
| POST | `/leave-requests` | `leave-requests.store` | Simpan pengajuan cuti |
| GET | `/leave-requests/{id}` | `leave-requests.show` | Detail pengajuan |
| GET | `/leave-requests/{id}/edit` | `leave-requests.edit` | Form edit pengajuan |
| PUT | `/leave-requests/{id}` | `leave-requests.update` | Update pengajuan |
| DELETE | `/leave-requests/{id}` | `leave-requests.destroy` | Hapus pengajuan |

## Validasi

### Leave Request

| Field | Rules |
|-------|-------|
| `leave_type_id` | required, exists:leave_types,id |
| `start_date` | required, date |
| `end_date` | required, date, after_or_equal:start_date |
| `reason` | required, string |

### Leave Type

| Field | Rules |
|-------|-------|
| `name` | required, max:255 |
| `description` | nullable |

### Reject Reason

| Field | Rules |
|-------|-------|
| `reject_reason` | required, string, max:1000 |

## Status Pengajuan

| Status | Deskripsi |
|--------|-----------|
| `pending` | Menunggu persetujuan admin |
| `approved` | Disetujui oleh admin |
| `rejected` | Ditolak oleh admin |

## Error Responses

### 403 - Unauthorized
```json
{
    "message": "Anda tidak memiliki akses untuk melakukan aksi ini."
}
```

### 422 - Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field": ["Error message"]
    }
}
```

### 422 - Insufficient Leave Balance
```json
{
    "message": "Kuota cuti tidak mencukupi. Sisa kuota: 3 hari, diperlukan: 5 hari."
}
```
