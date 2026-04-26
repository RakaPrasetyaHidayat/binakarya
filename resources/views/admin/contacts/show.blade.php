@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.contacts.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="text-gray-500">Nama</p>
                <p class="font-medium text-gray-800">{{ $contact->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Email</p>
                <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">{{ $contact->email }}</a>
            </div>
            <div>
                <p class="text-gray-500">Subjek</p>
                <p class="font-medium text-gray-800">{{ $contact->subject ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal</p>
                <p class="text-gray-800">{{ $contact->created_at->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-5">
            <p class="text-gray-500 text-sm mb-2">Pesan</p>
            <div class="bg-gray-50 rounded-lg p-4 text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $contact->message }}</div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="mailto:{{ $contact->email }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Balas via Email
            </a>
            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="border border-red-300 text-red-500 px-5 py-2.5 rounded-lg text-sm hover:bg-red-50 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
