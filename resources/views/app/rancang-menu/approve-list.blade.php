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
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php
                    $rencanganMenu = [];
                @endphp
                @foreach ($rancangan as $r)
                    @php
                        $rencanganMenu[$r->id] = $r;
                    @endphp
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input" name="id[]" value="{{ $r->id }}">
                        </td>
                        <td>{{ $r->tanggal }}</td>
                        <td>{{ $r->data_pemanfaat }}</td>
                        <td>{{ $r->jumlah }}</td>
                        <td>
                            <button type="button" id="{{ $r->id }}"
                                class="btn btn-sm btn-warning btn-detail-rancangan">Detail</button>

                            <div class="modal fade" id="detailRancangan{{ $r->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Rancangan Menu</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="detailRancangan{{ $r->id }}Body">
                                            <table border="0" cellspacing="0" cellpadding="0" class="w-100">
                                                <tr>
                                                    <td width="18%">Tanggal</td>
                                                    <td width="2%">:</td>
                                                    <td>{{ $r->tanggal }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Kelompok</td>
                                                    <td>:</td>
                                                    <td>{{ $r->data_pemanfaat }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Penerima</td>
                                                    <td>:</td>
                                                    <td>{{ $r->jumlah }}</td>
                                                </tr>
                                            </table>

                                            <ol class="list-group list-group-numbered">
                                                @foreach ($r->rancanganMenu as $rancangan)
                                                    <li class="list-group-item">
                                                        {{ $rancangan->menu->nama }}

                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Bahan</th>
                                                                        <th>Harga</th>
                                                                        <th>Gramasi</th>
                                                                        <th>Jumlah</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $no = 1;
                                                                        $total = 0;
                                                                    @endphp
                                                                    @foreach ($rancangan->menu->resep as $resep)
                                                                        <tr>
                                                                            <td>{{ $no++ }}</td>
                                                                            <td>{{ $resep->bahanPangan->nama }}</td>
                                                                            <td>
                                                                                Rp.
                                                                                {{ number_format($resep->bahanPangan->harga_jual) }}/{{ $resep->bahanPangan->satuan }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $resep->gramasi }}
                                                                                {{ $resep->bahanPangan->satuan }}
                                                                            </td>
                                                                            <td>
                                                                                Rp.
                                                                                {{ number_format($resep->bahanPangan->harga_jual * $resep->gramasi) }}
                                                                            </td>
                                                                        </tr>

                                                                        @php
                                                                            $total +=
                                                                                $resep->bahanPangan->harga_jual *
                                                                                $resep->gramasi;
                                                                        @endphp
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="4">Total</td>
                                                                        <td>
                                                                            Rp.
                                                                            {{ number_format($total) }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ol>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

<script id="rancangan-script">
    if (!window.rancanganScriptLoaded) {
        window.rancanganScriptLoaded = true;

        var RENCANA = @json($rencanganMenu);

        $(document).on('click', '.btn-detail-rancangan', function(e) {
            e.preventDefault();

            var id = $(this).attr('id');
            $('#detailRancangan' + id).modal('show');
        })
    }
</script>
