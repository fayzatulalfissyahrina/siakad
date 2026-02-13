<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Golongan;
use App\Models\JadwalAkademik;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\PresensiAkademik;
use App\Models\QrPresensi;
use App\Support\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role ?? 'guest';

        $data = [
            'role' => $role,
            'editing' => null,
        ];

        if ($role === 'admin') {
            $query = PresensiAkademik::query()->with(['mahasiswa', 'mataKuliah']);

            $data['presensi'] = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(10)->withQueryString();
            $data['mahasiswaList'] = Mahasiswa::orderBy('nim')->get();
            $data['mataKuliahList'] = MataKuliah::orderBy('kode_mk')->get();

            if ($request->filled('edit')) {
                $data['editing'] = PresensiAkademik::with(['mahasiswa', 'mataKuliah'])->findOrFail($request->edit);
            }
        } elseif ($role === 'dosen') {
            $nip = Auth::user()->nip;
            $nip = Auth::user()->nip;
            
            $pengampu = Pengampu::with(['mataKuliah', 'dosen'])
                ->where('nip', $nip)
                ->orderBy('kode_mk')
                ->get();

            $jadwal = JadwalAkademik::with(['mataKuliah', 'golongan'])
                ->whereIn('kode_mk', $pengampu->pluck('kode_mk'))
                ->orderBy('hari')
                ->get()
                ->map(function ($item) {
                    $item->semester_kelas = AcademicHelper::semesterKelas(
                        $item->golongan->angkatan ?? null,
                        $item->tahun_akademik,
                        $item->semester_akademik
                    );
                    $item->semester_mk = $item->mataKuliah->semester ?? null;
                    return $item;
                });

            $data['pengampu'] = $pengampu;
            $data['jadwal'] = $jadwal;
            $sessionList = QrPresensi::with(['mataKuliah', 'golongan'])
                ->where('nip', $nip)
                ->orderByDesc('id')
                ->limit(10)
                ->get();

            $data['sessionList'] = $sessionList->map(function ($session) {
                $session->hadir_count = $this->countHadirBySession($session);
                $session->semester_kelas = AcademicHelper::semesterKelas(
                    $session->golongan->angkatan ?? null,
                    $session->tahun_akademik,
                    $session->semester_akademik
                );
                return $session;
            });
        } elseif ($role === 'mahasiswa') {
            $nim = Auth::user()->nim;
            $mhs = Mahasiswa::where('nim', $nim)->first();

            $data['presensi'] = PresensiAkademik::with(['mataKuliah'])
                ->where('nim', $nim)
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->paginate(10)
                ->withQueryString();

            $activeSessions = collect();
            if ($mhs) {
                $activeSessions = QrPresensi::with(['mataKuliah', 'golongan'])
                    ->where('id_gol', $mhs->id_gol)
                    ->where('status', 'aktif')
                    ->where('expires_at', '>', Carbon::now())
                    ->orderByDesc('id')
                    ->get()
                    ->filter(function ($session) use ($nim, $mhs) {
                        $semesterKelas = AcademicHelper::semesterKelas(
                            $session->golongan->angkatan ?? null,
                            $session->tahun_akademik,
                            $session->semester_akademik
                        );

                        if (!$semesterKelas || (int) $mhs->semester !== (int) $semesterKelas) {
                            return false;
                        }

                        return Krs::where('nim', $nim)
                            ->where('kode_mk', $session->kode_mk)
                            ->where('tahun_akademik', $session->tahun_akademik)
                            ->where('semester_akademik', $session->semester_akademik)
                            ->exists();
                    })
                    ->map(function ($session) {
                        $session->semester_kelas = AcademicHelper::semesterKelas(
                            $session->golongan->angkatan ?? null,
                            $session->tahun_akademik,
                            $session->semester_akademik
                        );
                        return $session;
                    });
            }

            $data['activeSessions'] = $activeSessions;
        }

        return view('pages.presensi.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'nim' => 'required|exists:mahasiswa,nim',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'status_kehadiran' => 'required|string|max:20',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after_or_equal:jam_masuk',
            'keterangan' => 'nullable|string',
            'pertemuan_ke' => 'required|integer|min:1',
            'metode_presensi' => 'required|string|max:20',
        ]);

        PresensiAkademik::create($validated);

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil ditambahkan.');
    }

    public function update(Request $request, PresensiAkademik $presensi)
    {
        $validated = $request->validate([
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'nim' => 'required|exists:mahasiswa,nim',
            'kode_mk' => 'required|exists:mata_kuliah,kode_mk',
            'status_kehadiran' => 'required|string|max:20',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after_or_equal:jam_masuk',
            'keterangan' => 'nullable|string',
            'pertemuan_ke' => 'required|integer|min:1',
            'metode_presensi' => 'required|string|max:20',
        ]);

        $presensi->update($validated);

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil diperbarui.');
    }

    public function destroy(PresensiAkademik $presensi)
    {
        $presensi->delete();

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil dihapus.');
    }

    public function openSession(Request $request)
    {
        $nip = Auth::user()->nip;

        $validated = $request->validate([
            'jadwal_id' => 'required|exists:jadwal_akademik,id',
            'tahun_akademik' => 'required|string|max:20',
            'semester_akademik' => 'required|string|max:20',
            'pertemuan_ke' => 'required|integer|min:1',
            'expires_in' => 'required|integer|min:5|max:15',
        ]);

        $jadwal = JadwalAkademik::findOrFail($validated['jadwal_id']);

        $pengampuOk = Pengampu::where('nip', $nip)
            ->where('kode_mk', $jadwal->kode_mk)
            ->exists();

        if (!$pengampuOk) {
            return back()->with('error', 'Mata kuliah tersebut tidak diajar oleh dosen ini.');
        }

        $token = Str::random(32);

        $expiresIn = (int) $validated['expires_in'];

        QrPresensi::create([
            'kode_mk' => $jadwal->kode_mk,
            'nip' => $nip,
            'id_gol' => $jadwal->id_gol,
            'tahun_akademik' => $validated['tahun_akademik'],
            'semester_akademik' => $validated['semester_akademik'],
            'tanggal' => Carbon::today(),
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes($expiresIn),
            'status' => 'aktif',
        ]);

        return redirect()->route('dosen.presensi')
            ->with('success', 'Sesi presensi berhasil dibuka.');
    }

    public function submitStatus(Request $request)
    {
        $nim = Auth::user()->nim;

        $validated = $request->validate([
            'session_id' => 'required|exists:qr_presensi,id',
            'status_kehadiran' => 'required|in:hadir,sakit,izin',
        ]);

        $session = QrPresensi::with(['mataKuliah', 'golongan'])
            ->where('id', $validated['session_id'])
            ->first();

        if (!$session) {
            return redirect()->route('mahasiswa.presensi')->with('error', 'Sesi presensi tidak ditemukan.');
        }

        if ($session->status !== 'aktif' || $session->expires_at->isPast()) {
            return redirect()->route('mahasiswa.presensi')->with('error', 'Sesi presensi sudah tidak aktif.');
        }

        $mhs = Mahasiswa::where('nim', $nim)->first();
        if (!$mhs || $mhs->id_gol !== $session->id_gol) {
            return redirect()->route('mahasiswa.presensi')->with('error', 'Sesi presensi bukan untuk kelas Anda.');
        }

        $semesterKelas = AcademicHelper::semesterKelas(
            $session->golongan->angkatan ?? null,
            $session->tahun_akademik,
            $session->semester_akademik
        );
        if (!$semesterKelas || (int) $mhs->semester !== (int) $semesterKelas) {
            return redirect()->route('mahasiswa.presensi')->with('error', 'Sesi presensi tidak sesuai dengan semester Anda.');
        }

        $krsOk = Krs::where('nim', $nim)
            ->where('kode_mk', $session->kode_mk)
            ->where('tahun_akademik', $session->tahun_akademik)
            ->where('semester_akademik', $session->semester_akademik)
            ->exists();

        if (!$krsOk) {
            return redirect()->route('mahasiswa.presensi')->with('error', 'Anda belum terdaftar di KRS untuk mata kuliah ini.');
        }

        PresensiAkademik::updateOrCreate(
            [
                'tanggal' => $session->tanggal,
                'nim' => $nim,
                'kode_mk' => $session->kode_mk,
                'pertemuan_ke' => $session->pertemuan_ke,
            ],
            [
                'hari' => $this->mapHari($session->tanggal),
                'status_kehadiran' => $validated['status_kehadiran'],
                'jam_masuk' => Carbon::now()->format('H:i'),
                'jam_keluar' => null,
                'keterangan' => $this->sessionTag($session->id),
                'metode_presensi' => 'Klik',
            ]
        );

        $statusLabels = ['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin'];
        return redirect()->route('mahasiswa.presensi')->with('success', 'Presensi (' . $statusLabels[$validated['status_kehadiran']] . ') berhasil tercatat.');
    }

    public function clickHadir(Request $request)
    {
        return $this->submitStatus($request);
    }

    public function closeSession(QrPresensi $session)
    {
        $nip = Auth::user()->nip;

        if ($session->nip !== $nip) {
            return back()->with('error', 'Sesi ini bukan milik Anda.');
        }

        $session->status = 'nonaktif';
        $session->expires_at = Carbon::now();
        $session->save();

        return redirect()->route('dosen.presensi')->with('success', 'Sesi presensi ditutup.');
    }

    private function mapHari(Carbon $tanggal): string
    {
        $map = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return $map[$tanggal->format('l')] ?? 'Senin';
    }

    private function countHadirBySession(QrPresensi $session): int
    {
        return PresensiAkademik::where('keterangan', $this->sessionTag($session->id))
            ->where('status_kehadiran', 'hadir')
            ->whereHas('mahasiswa', function ($query) use ($session) {
                $query->where('id_gol', $session->id_gol);
            })
            ->count();
    }

    private function sessionTag(int $sessionId): string
    {
        return 'session:' . $sessionId;
    }
}
