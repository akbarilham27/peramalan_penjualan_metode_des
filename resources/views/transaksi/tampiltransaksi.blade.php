@extends('layout.admin')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1 class="m-0">Update Data Transaksi</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                      <li class="breadcrumb-item active">Update Transaksi</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
<div class="container mt-5 center">
  <div class="row justify-content-center">
    <div class="col-8">
      <div class="card">
        <div class="card-body">
          <form action="/updatetransaksi/{{ $data_transaksi->id_transaksi }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">ID Transaksi</label>
              <input type="text" name="id_transaksi" class="form-control" id="exampleInputEmail1"
                  aria-describedby="emailHelp" value="{{ $data_transaksi->id_transaksi }}" readonly>
              <div id="id_transaksi" class="form-text"></div>
          </div>
          <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nama Produk</label>
              <select class="form-control" name="id_produk" aria-label="Default select example">
                  @foreach ($data_produk as $row)
                      <option value="{{ $row->id_produk }}"
                          {{ $row->id_produk == $data_transaksi->id_produk ? 'selected' : '' }}>
                          {{ $row->nama_produk }}
                      </option>
                  @endforeach
              </select>
          </div>
          
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">jumlah</label>
            <input type="integer" name="jumlah_penjualan" class="form-control" id="exampleInputEmail1"
              aria-describedby="emailHelp" value="{{ $data_transaksi->jumlah_penjualan }}">
            <div id="jumlah_penjualan" class="form-text"></div>
          </div>
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Tanggal Pengajuan</label>
              <input type="date" name="tanggal_pengajuan" class="form-control" id="exampleInputEmail1"
                aria-describedby="emailHelp" value="{{ $data_transaksi->tanggal_pengajuan }}">
              <div id="tanggal_pengajuan" class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- <button type="button" class="btn btn-outline-secondary">Tambah +</button> --}}

</div>
</div>
@endsection