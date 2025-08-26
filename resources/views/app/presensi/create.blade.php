@extends('app.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Upload Presensi</h5>

            <form action="" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File (Excel/CSV)</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
@endsection
