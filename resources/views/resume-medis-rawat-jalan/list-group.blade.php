<style>
    /* CSS untuk meniru tampilan link yang dinonaktifkan */
    .disabled-link {
        color: #999; /* Warna teks link yang dinonaktifkan */
        cursor: not-allowed; /* Mengubah kursor menjadi ikon tidak diizinkan */
        pointer-events: none; /* Menonaktifkan peristiwa klik */
        text-decoration: none; /* Menghapus dekorasi tautan */
    }
</style>
<div class="list-group" style="overflow-y: auto; max-height: 270px;">
    <a class="list-group-item list-group-item-action {{ request()->is(['resume-medis-rawat-jalan/pendaftaran-pendataan-pasien', 'resume-medis-rawat-jalan/pendaftaran-pendataan-pasien/*']) ? 'bg-teal' : '' }}" href="{{ route('resume-medis-rawat-jalan.pendaftaran-pendataan-pasien.index')}}">1. Pendaftaran Identitas Pasien & Pendataan Kunjungan</a>
    <a class="list-group-item list-group-item-action disabled-link {{ request()->is(['resume-medis-rawat-jalan/keluhan-utama', 'resume-medis-rawat-jalan/keluhan-utama/*', 'resume-medis-rawat-jalan/riwayat-alergi', 'resume-medis-rawat-jalan/riwayat-alergi/*']) ? 'bg-olive' : '' }}" href="">2. Anamnesis</a>
    <a class="list-group-item list-group-item-action {{ request()->is(['resume-medis-rawat-jalan/keluhan-utama', 'resume-medis-rawat-jalan/keluhan-utama/*']) ? 'bg-teal' : '' }}" href="{{ route('resume-medis-rawat-jalan.keluhan-utama.index')}}"> <span class=" ml-3">- Keluhan Utama</span></a>
    <a class="list-group-item list-group-item-action disabled-link {{ request()->is(['resume-medis-rawat-jalan/riwayat-alergi', 'resume-medis-rawat-jalan/riwayat-alergi/*']) ? 'bg-teal' : '' }}" href="{{route('resume-medis-rawat-jalan.riwayat-alergi.index')}}"> <span class=" ml-3">- Riwayat Alergi (Segera Hadir)</span></a>
    <a class="list-group-item list-group-item-action {{ request()->is(['resume-medis-rawat-jalan/hasil-pemeriksaan-fisik', 'resume-medis-rawat-jalan/hasil-pemeriksaan-fisik/*']) ? 'bg-teal' : '' }}" href="{{route('resume-medis-rawat-jalan.hasil-pemeriksaan-fisik.index')}}">3. Hasil Pemeriksaan Fisik</a>
    <a class="list-group-item list-group-item-action disabled-link {{ request()->is(['resume-medis-rawat-jalan/laboratorium/permintaan-pemeriksaan-penunjang', 'resume-medis-rawat-jalan/laboratorium/permintaan-pemeriksaan-penunjang/*']) ? 'bg-olive' : '' }}" href=""># Pemeriksaan Laboratorium</a>
    <a class="list-group-item list-group-item-action {{ request()->is(['resume-medis-rawat-jalan/laboratorium/permintaan-pemeriksaan-penunjang', 'resume-medis-rawat-jalan/laboratorium/permintaan-pemeriksaan-penunjang/*']) ? 'bg-teal' : '' }}" href="{{route('permintaan-pemeriksaan-penunjang-laboratorium.index')}}"> <span class=" ml-3">4. Permintaan Pemeriksaan Penunjang Laboratorium</span></a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">5. Spesimen Laboratorium</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">6. Hasil Pemeriksaan Penunjang Laboratorium</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">7. Laporan Hasil Pemeriksaan Penunjang Laboratorium</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">8. Tindakan/Prosedur Medis Diagnosis</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">9. Diagnosis</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">10. Tindakan/Prosedur Medis Terapetik</a>
    <a class="list-group-item list-group-item-action {{ request()->is(['resume-medis-rawat-jalan/peresepan-obat', 'resume-medis-rawat-jalan/peresepan-obat/*']) ? 'bg-olive' : '' }}" href="">11. Tata Laksana</a>
    <a class="list-group-item list-group-item-action" href="#list-item-3"> <span class=" ml-3">- Diet</span></a>
    <a class="list-group-item list-group-item-action" href="#list-item-4"> <span class=" ml-3">- Edukasi</span></a>
    <a class="list-group-item list-group-item-action" href="#list-item-4"> <span class=" ml-3">- Obat</span></a>
    <a class="list-group-item list-group-item-action {{ request()->is(['resume-medis-rawat-jalan/peresepan-obat', 'resume-medis-rawat-jalan/peresepan-obat/*']) ? 'bg-teal' : '' }}" href="{{ route('resume-medis-rawat-jalan.peresepan-obat.index')}}"> <span class=" ml-5">* Peresepan Obat</span></a>
    <a class="list-group-item list-group-item-action" href="#list-item-4"> <span class=" ml-5">* penegeluaran Obat</span></a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">12. Prognosis</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">13. Tindak Lanjut (Transportasi Rujuk, Instruksi Tindak Lanjut, Rencana Tindak Lanjut)</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">14. Kondisi Saat Meninggalkan RS</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">15. Cara Keluar dari RS</a>
    <a class="list-group-item list-group-item-action" href="#list-item-2">16. Cara Keluar dari RS / Pasien Pulang</a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil elemen list yang aktif
        var activeListItem = document.querySelector('.list-group-item.bg-teal');

        // Menggeser posisi scroll ke elemen list yang aktif
        activeListItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
</script>
