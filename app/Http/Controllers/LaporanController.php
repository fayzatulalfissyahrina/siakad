<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pages.laporan.index');
    }

    public function export(string $type): Response
    {
        if (!in_array($type, ['csv', 'xlsx', 'pdf'], true)) {
            abort(404);
        }

        $content = "Laporan akademik ($type) akan dihasilkan di sini.";
        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
