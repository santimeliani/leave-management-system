# Diagram UML

Semua diagram UML dibuat menggunakan format **PlantUML** (.puml). Untuk merender diagram:

1. Install PlantUML: https://plantuml.com/
2. Gunakan VS Code extension: `PlantUML` by jebbs
3. Atau gunakan online: https://www.plantuml.com/plantuml/

## Daftar Diagram

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Use Case | `usecase.puml` | Aktor dan use case sistem |
| 2 | Class | `class.puml` | Struktur kelas dan relasi |
| 3 | Sequence | `sequence.puml` | Alur interaksi objek |
| 4 | Activity | `activity.puml` | Alur kerja proses bisnis |
| 5 | Component | `component.puml` | Arsitektur komponen sistem |
| 6 | State | `state.puml` | Status transisi pengajuan cuti |
| 7 | ERD | `erd.puml` | Struktur database |

## Cara Render

### Menggunakan CLI
```bash
plantuml -tsvg docs/uml/*.puml
```

### Menggunakan VS Code
1. Buka file `.puml`
2. Press `Alt + D` untuk preview
3. Export sebagai PNG/SVG

### Menggunakan Online
Kunjungi https://www.plantuml.com/plantuml/ dan paste isi file `.puml`
