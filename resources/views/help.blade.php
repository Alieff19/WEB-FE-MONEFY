@extends('layouts.app')

@section('title', 'Monefy - Help Center')

@section('content')
<!-- Main Help Center Container -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white p-5 rounded-4 shadow-sm" style="color: var(--text-dark);">
                <h3 class="fw-bold mb-4" style="color: var(--primary-purple);">Help Center</h3>
                <p class="text-muted mb-5">Selamat datang di Pusat Bantuan Monefy. Berikut adalah jawaban dari beberapa pertanyaan yang umum ditanyakan.</p>

                <!-- FAQ Item 1 -->
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-question-circle text-muted me-2"></i> Bagaimana cara menambahkan dompet baru?</h5>
                    <p class="text-muted ms-4">Anda dapat menuju ke halaman Profile, lalu klik opsi "Add your wallet". Isi nama dompet dan saldo awal Anda di sana.</p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-question-circle text-muted me-2"></i> Apakah data transaksi saya aman?</h5>
                    <p class="text-muted ms-4">Ya, data Anda disimpan di dalam database sistem kami sendiri dan tidak dibagikan ke pihak ketiga mana pun.</p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-question-circle text-muted me-2"></i> Bagaimana cara melihat ringkasan pengeluaran bulanan?</h5>
                    <p class="text-muted ms-4">Anda dapat mengakses halaman Analytic melalui menu navigasi utama. Di sana Anda akan melihat grafik atau ringkasan total uang yang masuk dan keluar.</p>
                </div>

                <hr class="my-5">

                <!-- Contact Info -->
                <h5 class="fw-bold mb-3">Butuh bantuan lebih lanjut?</h5>
                <p class="text-muted">Hubungi kami melalui email: <a href="mailto:support@monefy.com" style="color: var(--primary-purple); text-decoration: none; font-weight: 600;">support@monefy.com</a></p>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
