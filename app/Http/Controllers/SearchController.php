<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Golongan;
use App\Models\JadwalAkademik;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\PresensiAkademik;
use App\Models\Ruang;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request, string $type)
    {
        $q = $request->query('q', '');
        
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $results = match ($type) {
            'mahasiswa' => $this->searchMahasiswa($q),
            'dosen' => $this->searchDosen($q),
            'mata-kuliah' => $this->searchMataKuliah($q),
            'pengampu' => $this->searchPengampu($q),
            'jadwal' => $this->searchJadwal($q),
            'ruang' => $this->searchRuang($q),
            'presensi' => $this->searchPresensi($q),
            default => [],
        };

        return response()->json($results);
    }

    public function filter(Request $request, string $type)
    {
        $q = $request->query('q', '');
        
        $view = match ($type) {
            'mahasiswa' => $this->filterMahasiswa($q),
            'dosen' => $this->filterDosen($q),
            'mata-kuliah' => $this->filterMataKuliah($q),
            'pengampu' => $this->filterPengampu($q),
            'jadwal' => $this->filterJadwal($q),
            'ruang' => $this->filterRuang($q),
            'presensi' => $this->filterPresensi($q),
            default => ['html' => '', 'pagination' => ''],
        };

        return response()->json($view);
    }

    private function searchMahasiswa(string $q): array
    {
        return Mahasiswa::where('nim', 'like', "%{$q}%")
            ->orWhere('nama', 'like', "%{$q}%")
            ->limit(10)
            ->get(['nim', 'nama', 'semester', 'status'])
            ->toArray();
    }

    private function filterMahasiswa(string $q): array
    {
        $query = Mahasiswa::query()->with('golongan');

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nim', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('nim')->paginate(10);
        
        $html = view('partials.table-mahasiswa', ['mahasiswa' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }

    private function searchDosen(string $q): array
    {
        return Dosen::where('nip', 'like', "%{$q}%")
            ->orWhere('nama', 'like', "%{$q}%")
            ->limit(10)
            ->get(['nip', 'nama', 'email'])
            ->toArray();
    }

    private function filterDosen(string $q): array
    {
        $query = Dosen::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('nama')->paginate(10);
        
        $html = view('partials.table-dosen', ['dosen' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }

    private function searchMataKuliah(string $q): array
    {
        return MataKuliah::where('kode_mk', 'like', "%{$q}%")
            ->orWhere('nama_mk', 'like', "%{$q}%")
            ->limit(10)
            ->get(['kode_mk', 'nama_mk', 'sks', 'semester'])
            ->toArray();
    }

    private function filterMataKuliah(string $q): array
    {
        $query = MataKuliah::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_mk', 'like', "%{$q}%")
                    ->orWhere('nama_mk', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('kode_mk')->paginate(10);
        
        $html = view('partials.table-mata-kuliah', ['mataKuliah' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }

    private function searchPengampu(string $q): array
    {
        return Pengampu::with(['dosen', 'mataKuliah'])
            ->whereHas('dosen', function ($sub) use ($q) {
                $sub->where('nip', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            })
            ->orWhereHas('mataKuliah', function ($sub) use ($q) {
                $sub->where('kode_mk', 'like', "%{$q}%")
                    ->orWhere('nama_mk', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function filterPengampu(string $q): array
    {
        $query = Pengampu::query()->with(['dosen', 'mataKuliah']);

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('dosen', function ($s) use ($q) {
                    $s->where('nip', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%");
                })->orWhereHas('mataKuliah', function ($s) use ($q) {
                    $s->where('kode_mk', 'like', "%{$q}%")
                        ->orWhere('nama_mk', 'like', "%{$q}%");
                });
            });
        }

        $data = $query->orderByDesc('id')->paginate(10);
        
        $html = view('partials.table-pengampu', ['pengampu' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }

    private function searchJadwal(string $q): array
    {
        return JadwalAkademik::with(['mataKuliah', 'golongan', 'ruang'])
            ->whereHas('mataKuliah', function ($sub) use ($q) {
                $sub->where('kode_mk', 'like', "%{$q}%")
                    ->orWhere('nama_mk', 'like', "%{$q}%");
            })
            ->orWhereHas('golongan', function ($sub) use ($q) {
                $sub->where('nama_gol', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function filterJadwal(string $q): array
    {
        $query = JadwalAkademik::query()->with(['mataKuliah', 'golongan', 'ruang']);

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('mataKuliah', function ($s) use ($q) {
                    $s->where('kode_mk', 'like', "%{$q}%")
                        ->orWhere('nama_mk', 'like', "%{$q}%");
                })->orWhereHas('golongan', function ($s) use ($q) {
                    $s->where('nama_gol', 'like', "%{$q}%");
                });
            });
        }

        $data = $query->orderBy('hari')->paginate(10);

        $data->getCollection()->transform(function ($item) {
            $item->semester_kelas = \App\Support\AcademicHelper::semesterKelas(
                $item->golongan->angkatan ?? null,
                $item->tahun_akademik,
                $item->semester_akademik
            );
            return $item;
        });
        
        $html = view('partials.table-jadwal', ['jadwal' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }

    private function searchRuang(string $q): array
    {
        return Ruang::where('id_ruang', 'like', "%{$q}%")
            ->orWhere('nama_ruang', 'like', "%{$q}%")
            ->orWhere('gedung', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id_ruang', 'nama_ruang', 'gedung', 'kapasitas'])
            ->toArray();
    }

    private function filterRuang(string $q): array
    {
        $query = Ruang::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('id_ruang', 'like', "%{$q}%")
                    ->orWhere('nama_ruang', 'like', "%{$q}%")
                    ->orWhere('gedung', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('id_ruang')->paginate(10);
        
        $html = view('partials.table-ruang', ['ruang' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }

    private function searchPresensi(string $q): array
    {
        return PresensiAkademik::with(['mahasiswa', 'mataKuliah'])
            ->whereHas('mahasiswa', function ($sub) use ($q) {
                $sub->where('nim', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function filterPresensi(string $q): array
    {
        $query = PresensiAkademik::query()->with(['mahasiswa', 'mataKuliah']);

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('mahasiswa', function ($s) use ($q) {
                    $s->where('nim', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%");
                });
            });
        }

        $data = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(10);
        
        $html = view('partials.table-presensi', ['presensi' => $data])->render();
        
        return [
            'html' => $html,
            'pagination' => $data->links('vendor.pagination.bootstrap-5')->toHtml(),
            'total' => $data->total()
        ];
    }
}
