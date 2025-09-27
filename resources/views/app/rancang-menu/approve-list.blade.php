<div class="card mb-6">
    <h5 class="card-header">Daftar Rancangan Menu</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th>Tanggal</th>
                    <th>Kelompok</th>
                    <th>Jumlah Penerima</th>
                    <th>Menu</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($rancangan as $r)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input" name="id[]" value="{{ $r->id }}">
                        </td>
                        <td>{{ $r->tanggal }}</td>
                        <td>{{ $r->dataPemanfaat->nama }}</td>
                        <td>{{ $r->jumlah }}</td>
                        <td>
                            @foreach ($r->rancanganMenu as $rm)
                                <span class="badge bg-label-primary me-1">{{ $rm->menu->nama }}</span>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end p-3">
        <button class="btn btn-primary" id="btnApprove">Approve</button>
    </div>
</div>
