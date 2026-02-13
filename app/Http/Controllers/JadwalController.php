<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\JadwalAkademik;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\Ruang;
use App\Support\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role ?? 'guest';

        $query = JadwalAkademik::query()->with(['mataKuliah', 'ruang', 'golongan']);

        $data = [
            'role' => $role,
        ];

        if ($role === 'dosen') {
            $kodeMk = \App\Models\Pengampu::where('nip', Auth::user()->nip)
                ->pluck('kode_mk');
            $query->whereIn('kode_mk', $kodeMk);
            $jadwal = $query->orderBy('hari')->paginate(10)->withQueryString();
            $jadwal->getCollection()->transform(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->angkatan ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
            $data['jadwal'] = $jadwal;
        } elseif ($role === 'mahasiswa') {
            $mhs = \App\Models\Mahasiswa::with('golongan')->where('nim', Auth::user()->nim)->first();
            
            if ($mhs && $mhs->golongan) {
                $semesterMhs = (int) $mhs->semester;
                
                $jadwal = JadwalAkademik::query()
                    ->with(['mataKuliah', 'ruang', 'golongan'])
                    ->where('id_gol', $mhs->id_gol)
                    ->orderBy('hari')
                    ->get();
                
                $jadwalFiltered = $jadwal->map(function ($item) use ($semesterMhs) {
                    $item->semester_kelas = AcademicHelper::semesterKelas(
                        $item->golongan->angkatan ?? null,
                        $item->tahun_akademik,
                        $item->semester_akademik
                    );
                    return $item;
                })->filter(function ($item) use ($semesterMhs) {
                    return (int) $item->semester_kelas === $semesterMhs && $item->semester_kelas !== null;
                })->values();
                
                $data['jadwal'] = new LengthAwarePaginator(
                    $jadwalFiltered,
                    $jadwalFiltered->count(),
                    10,
                    1,
                    ['path' => request()->url()]
                );
            } else {
                $data['jadwal'] = collect();
            }
        } else {
            $jadwal = $query->orderBy('hari')->paginate(10)->withQueryString();
            $jadwal->getCollection()->transform(function ($item) {
                $item->semester_kelas = AcademicHelper::semesterKelas(
                    $item->golongan->attributes["angkatan"] ?? null,
                    $item->tahun_akademik,
                    $item->semester_akademik
                );
                return $item;
            });
            $data['jadwal'] = $jadwal;
        }

        return view('pages.jadwal.index', $data);
    }

    public function create()
    {
        return view('pages.jadwal.create', [
            'mataKuliahList' => MataKuliah::orderBy('kode_mk')->get(),
            'ruangList' => Ruang::orderBy('id_ruang')->get(),
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ]);
    }

    public function edit(JadwalAkademik $jadwal)
    {
        return view('pages.jadwal.edit', [
            'jadwal' => $jadwal,
            'mataKuliahList' => MataKuliah::orderBy('kode_mk')->get(),
            'ruangList' => Ruang::orderBy('id_ruang')->get(),
            'golonganList' => Golongan::orderBy('nama_gol')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'id_gol' => 'required|exists:golongan,id_gol',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
        ]);

        $mk = MataKuliah::find($validated['kode_mk']);
        if (!$mk) {
            return back()
                ->withErrors(['kode_mk' => 'Mata kuliah tidak ditemukan.'])
                ->withInput();
        }

        $golongan = Golongan::find($validated['id_gol']);
        if (!$golongan) {
            return back()
                ->withErrors(['id_gol' => 'Golongan tidak ditemukan.'])
                ->withInput();
        }

        $semesterKelas = AcademicHelper::semesterKelas(
            $golongan->attributes["angkatan"],
            $validated['tahun_akademik'],
            $validated['semester_akademik']
        );

        if ($semesterKelas !== (int) $mk->semester) {
            return back()
                ->withErrors(['kode_mk' => 'Mata kuliah semester ' . $mk->semester . ' tidak sesuai dengan semester kelas (' . $semesterKelas . ').'])
                ->withInput();
        }

        $conflictRuang = $this->checkRuangConflict($validated['id_ruang'], $validated['hari'], $validated['jam_mulai'], $validated['jam_selesai'], $validated['tahun_akademik'], $validated['semester_akademik']);
        
        if ($conflictRuang) {
            return back()
                ->withErrors(['id_ruang' => 'Ruangan sudah digunakan pada jam tersebut.'])
                ->withInput();
        }

        $conflictGol = $this->checkGolonganConflict($validated['id_gol'], $validated['hari'], $validated['jam_mulai'], $validated['jam_selesai'], $validated['tahun_akademik'], $validated['semester_akademik']);
        
        if ($conflictGol) {
            return back()
                ->withErrors(['id_gol' => 'Golongan sudah memiliki jadwal pada hari dan jam yang sama.'])
                ->withInput();
        }

        $pengampuAda = Pengampu::where('kode_mk', $validated['kode_mk'])
            ->exists();

        if (!$pengampuAda) {
            return back()
                ->withErrors(['kode_mk' => 'Dosen pengampu untuk mata kuliah ini belum diatur. Silakan tambah di menu Pengampu terlebih dahulu.'])
                ->withInput();
        }

        try {
            JadwalAkademik::create($validated);
            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['general' => 'Gagal menyimpan jadwal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, JadwalAkademik $jadwal)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'id_gol' => 'required|exists:golongan,id_gol',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
        ]);

        $mk = MataKuliah::find($validated['kode_mk']);
        if (!$mk) {
            return back()
                ->withErrors(['kode_mk' => 'Mata kuliah tidak ditemukan.'])
                ->withInput();
        }

        $golongan = Golongan::find($validated['id_gol']);
        if (!$golongan) {
            return back()
                ->withErrors(['id_gol' => 'Golongan tidak ditemukan.'])
                ->withInput();
        }

        $semesterKelas = AcademicHelper::semesterKelas(
            $golongan->attributes["angkatan"],
            $validated['tahun_akademik'],
            $validated['semester_akademik']
        );

        if ($semesterKelas !== (int) $mk->semester) {
            return back()
                ->withErrors(['kode_mk' => 'Mata kuliah semester ' . $mk->semester . ' tidak sesuai dengan semester kelas (' . $semesterKelas . ').'])
                ->withInput();
        }

        $conflictRuang = $this->checkRuangConflict($validated['id_ruang'], $validated['hari'], $validated['jam_mulai'], $validated['jam_selesai'], $validated['tahun_akademik'], $validated['semester_akademik'], $jadwal->id);
        
        if ($conflictRuang) {
            return back()
                ->withErrors(['id_ruang' => 'Ruangan sudah digunakan pada jam tersebut.'])
                ->withInput();
        }

        $conflictGol = $this->checkGolonganConflict($validated['id_gol'], $validated['hari'], $validated['jam_mulai'], $validated['jam_selesai'], $validated['tahun_akademik'], $validated['semester_akademik'], $jadwal->id);
        
        if ($conflictGol) {
            return back()
                ->withErrors(['id_gol' => 'Golongan sudah memiliki jadwal pada hari dan jam yang sama.'])
                ->withInput();
        }

        $pengampuAda = Pengampu::where('kode_mk', $validated['kode_mk'])
            ->exists();

        if (!$pengampuAda) {
            return back()
                ->withErrors(['kode_mk' => 'Dosen pengampu untuk mata kuliah ini belum diatur. Silakan tambah di menu Pengampu terlebih dahulu.'])
                ->withInput();
        }

        try {
            $jadwal->update($validated);
            return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['general' => 'Gagal memperbarui jadwal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function checkRuangConflict(string $id_ruang, string $hari, string $jam_mulai, string $jam_selesai, string $tahun_akademik, string $semester_akademik, ?int $excludeId = null): bool
    {
        $query = JadwalAkademik::where('id_ruang', $id_ruang)
            ->where('hari', $hari)
            ->where('tahun_akademik', $tahun_akademik)
            ->where('semester_akademik', $semester_akademik)
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->where(function ($sub) use ($jam_mulai, $jam_selesai) {
                    $sub->where('jam_mulai', '<', $jam_selesai)
                        ->where('jam_selesai', '>', $jam_mulai);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    private function checkGolonganConflict(string $id_gol, string $hari, string $jam_mulai, string $jam_selesai, string $tahun_akademik, string $semester_akademik, ?int $excludeId = null): bool
    {
        $query = JadwalAkademik::where('id_gol', $id_gol)
            ->where('hari', $hari)
            ->where('tahun_akademik', $tahun_akademik)
            ->where('semester_akademik', $semester_akademik)
            ->where(function ($q) use ($jam_mulai, $jam_selesai) {
                $q->where(function ($sub) use ($jam_mulai, $jam_selesai) {
                    $sub->where('jam_mulai', '<', $jam_selesai)
                        ->where('jam_selesai', '>', $jam_mulai);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function checkRuangAvailable(Request $request)
    {
        $id_ruang = $request->query('id_ruang');
        $hari = $request->query('hari');
        $jam_mulai = $request->query('jam_mulai');
        $jam_selesai = $request->query('jam_selesai');
        $tahun_akademik = $request->query('tahun_akademik');
        $semester_akademik = $request->query('semester_akademik');
        $excludeId = $request->query('exclude_id');

        if (!$id_ruang || !$hari || !$jam_mulai || !$jam_selesai || !$tahun_akademik || !$semester_akademik) {
            return response()->json(['available' => true, 'message' => 'Parameter tidak lengkap']);
        }

        $conflict = $this->checkRuangConflict($id_ruang, $hari, $jam_mulai, $jam_selesai, $tahun_akademik, $semester_akademik, $excludeId);

        return response()->json([
            'available' => !$conflict,
            'message' => $conflict ? 'Ruangan sudah digunakan pada jam tersebut' : 'Ruangan tersedia'
        ]);
    }

    public function destroy(JadwalAkademik $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function getMataKuliahBySemester(Request $request)
    {
        $semester = $request->query('semester');
        
        if (!$semester) {
            return response()->json(['mata_kuliah' => [], 'message' => 'Semester tidak boleh kosong']);
        }

        $mataKuliah = MataKuliah::where('semester', $semester)
            ->orderBy('kode_mk')
            ->get(['kode_mk', 'nama_mk', 'sks', 'semester']);

        return response()->json([
            'mata_kuliah' => $mataKuliah,
            'message' => $mataKuliah->isEmpty() ? 'Tidak ada mata kuliah untuk semester ini' : 'OK'
        ]);
    }

    public function getSemesterByGolongan(Request $request)
    {
        $idGol = $request->query('id_gol');
        $tahunAkademik = $request->query('tahun_akademik');
        $semesterAkademik = $request->query('semester_akademik');
        
        if (!$idGol || !$tahunAkademik || !$semesterAkademik) {
            return response()->json(['semester' => null, 'message' => 'Parameter tidak lengkap']);
        }

        $golongan = Golongan::find($idGol);
        if (!$golongan) {
            return response()->json(['semester' => null, 'message' => 'Golongan tidak ditemukan']);
        }

        $semester = AcademicHelper::semesterKelas($golongan->attributes["angkatan"], $tahunAkademik, $semesterAkademik);
        
        return response()->json([
            'semester' => $semester,
            'message' => $semester ? 'OK' : 'Gagal menghitung semester'
        ]);
    }
}
