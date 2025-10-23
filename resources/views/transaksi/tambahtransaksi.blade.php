@extends('layout.admin')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Data transaksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
            <li class="breadcrumb-item active">Tambah Transaksi</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="container mt-10 center">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <form action="/inserttransaksi" method="POST" enctype="multipart/form-data" id="transaksiForm">
                        @csrf
                        <div id="transaksi-container">
                            <div class="transaksi-item">
                                <div class="mb-3">
                                    <label for="id_transaksi" class="form-label">ID Transaksi</label>
                                    <input type="text" name="id_transaksi[]" class="form-control" value="{{ $newIdTransaksi }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="id_produk" class="form-label">Nama Produk</label>
                                    <select name="id_produk[]" class="form-control" id="id_produk" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach ($data_produk as $row)
                                            <option value="{{$row->id_produk}}">{{$row->nama_produk}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_penjualan" class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah_penjualan[]" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                                    <input type="date" name="tanggal_pengajuan[]" class="form-control" required>
                                </div>
                                <button type="button" class="btn btn-danger hapus-item">Hapus</button>
                                <hr>
                            </div>
                        </div>
                        <button type="button" id="addTransaksi" class="btn btn-outline-secondary mt-3">Tambah +</button>
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Bootstrap JS (Opsional, untuk komponen interaktif) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
      const addButton = document.getElementById('addTransaksi');
      const container = document.getElementById('transaksi-container');

      let idCounter = {{ $newIdTransaksi }}; // Mulai dari ID transaksi terakhir

      // Fungsi untuk menambah form baru
      addButton.addEventListener('click', function () {
          idCounter++; // Tambahkan ID transaksi secara dinamis
          const newItem = document.createElement('div');
          newItem.classList.add('transaksi-item');
          newItem.innerHTML = `
              <div class="mb-3">
                  <label for="id_transaksi" class="form-label">ID Transaksi</label>
                  <input type="text" name="id_transaksi[]" class="form-control" value="${idCounter}" readonly>
              </div>
              <div class="mb-3">
                  <label for="id_produk" class="form-label">Nama Produk</label>
                  <select name="id_produk[]" class="form-control" required>
                      <option value="">Pilih Produk</option>
                      @foreach ($data_produk as $row)
                          <option value="{{$row->id_produk}}">{{$row->nama_produk}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="mb-3">
                  <label for="jumlah_penjualan" class="form-label">Jumlah</label>
                  <input type="number" name="jumlah_penjualan[]" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                  <input type="date" name="tanggal_pengajuan[]" class="form-control" required>
              </div>
              <button type="button" class="btn btn-danger hapus-item">Hapus</button>
              <hr>
          `;
          container.appendChild(newItem);

          // Tambahkan event listener untuk tombol hapus
          const removeButtons = document.querySelectorAll('.hapus-item');
          removeButtons.forEach(button => {
              button.addEventListener('click', function () {
                  this.parentElement.remove();
              });
          });
      });
  });
</script>

@endsection