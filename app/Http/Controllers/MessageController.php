<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public const TOPICS = [
        'Kirim resep atau kisah',
        'Kerja sama perajin / UMKM',
        'Pertanyaan tentang pesanan',
        'Lainnya',
    ];

    public function store(Request $request): JsonResponse
    {
        if (filled($request->input('website'))) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'contact' => ['required', 'string', 'min:5', 'max:120'],
            'topic' => ['required', 'string', 'in:'.implode(',', self::TOPICS)],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'required' => ':attribute wajib diisi.',
            'min' => ':attribute terlalu pendek.',
            'max' => ':attribute terlalu panjang.',
            'in' => 'Pilih salah satu topik yang tersedia.',
        ], [
            'name' => 'Nama',
            'contact' => 'Kontak',
            'topic' => 'Topik',
            'body' => 'Pesan',
        ]);

        Message::create([
            'name' => trim($data['name']),
            'contact' => trim($data['contact']),
            'topic' => $data['topic'],
            'body' => trim($data['body']),
            'ip' => $request->ip(),
        ]);

        return response()->json(['ok' => true], 201);
    }
}
