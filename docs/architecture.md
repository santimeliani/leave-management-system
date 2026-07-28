# Arsitektur Sistem

##Arsitektur Berlapis (Layered Architecture)

Sistem ini mengikuti pola **Service-Repository Pattern** dengan pemisahan yang jelas antara lapisan presentasi, bisnis, dan data.

```
┌─────────────────────────────────────────────┐
│              PRESENTATION LAYER              │
│  Controllers, Views (Blade), Middleware       │
├─────────────────────────────────────────────┤
│              AUTHORIZATION LAYER             │
│  Policies (LeaveRequestPolicy,               │
│            LeaveTypePolicy)                   │
├─────────────────────────────────────────────┤
│              BUSINESS LOGIC LAYER            │
│  Services (LeaveRequestService,              │
│            LeaveBalanceService,               │
│            LeaveTypeService)                  │
├─────────────────────────────────────────────┤
│              EVENT LAYER                     │
│  Events & Listeners                          │
│  (LeaveRequestApproved,                      │
│   LeaveRequestRejected)                      │
├─────────────────────────────────────────────┤
│              DATA ACCESS LAYER               │
│  Eloquent Models (User, LeaveType,           │
│  LeaveRequest, LeaveBalance)                 │
├─────────────────────────────────────────────┤
│              EXCEPTION LAYER                 │
│  Custom Exceptions                           │
│  (InsufficientLeaveBalanceException,         │
│   UnauthorizedActionException)               │
└─────────────────────────────────────────────┘
```

## Deskripsi Setiap Layer

### 1. Presentation Layer
- **Controllers** - Menerima request HTTP, memanggil Service, mengembalikan response
- **Views** - Template Blade dengan Tailwind CSS
- **Middleware** - RoleMiddleware untuk otorisasi berbasis role

### 2. Authorization Layer (Policies)
- **LeaveRequestPolicy** - Mengontrol aksi edit/delete/approve pada pengajuan cuti
- **LeaveTypePolicy** - Mengontrol aksi CRUD pada jenis cuti

### 3. Business Logic Layer (Services)
- **LeaveRequestService** - Logika bisnis pengajuan cuti (create, approve, reject)
- **LeaveBalanceService** - Logika kuota cuti (cek sisa, kurangi kuota)
- **LeaveTypeService** - Logika manajemen jenis cuti

### 4. Event Layer
- **Events** - LeaveRequestApproved, LeaveRequestRejected
- **Listeners** - UpdateLeaveBalance (mengurangi kuota saat disetujui)

### 5. Data Access Layer (Models)
- **User** - Model user dengan role
- **LeaveType** - Jenis cuti
- **LeaveRequest** - Pengajuan cuti
- **LeaveBalance** - Kuota cuti per user per jenis per tahun

### 6. Exception Layer
- **InsufficientLeaveBalanceException** - Kuota cuti tidak mencukupi
- **UnauthorizedActionException** - Aksi tidak diizinkan

## Alur Request

```
HTTP Request
    │
    ▼
Route (web.php)
    │
    ▼
Middleware (auth, role)
    │
    ▼
Controller
    │
    ▼
Policy (authorize)
    │
    ▼
Service (business logic)
    │
    ▼
Model (Eloquent ORM)
    │
    ▼
Database
    │
    ▼
Event (dispatch)
    │
    ▼
Listener (handle)
    │
    ▼
Response (View/JSON)
```

## Database Schema

Lihat [ERD Diagram](uml/erd.puml) untuk visualisasi lengkap.
