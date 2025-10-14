<table border="0" cellspacing="0" cellpadding="0" class="w-100">
    <tr>
        <td width="18%">Tanggal</td>
        <td width="2%">:</td>
        <td>{{ $rancang_menu->tanggal }}</td>
    </tr>
    <tr>
        <td>Kelompok</td>
        <td>:</td>
        <td>{{ $rancang_menu->data_pemanfaat }}</td>
    </tr>
    <tr>
        <td>Jumlah Penerima</td>
        <td>:</td>
        <td>{{ $rancang_menu->jumlah }}</td>
    </tr>
</table>

<ol class="list-group list-group-numbered">
    @php
        $total_harga = 0;
    @endphp
    @foreach ($rancang_menu->rancanganMenu as $rancangan)
        <li class="list-group-item">
            {{ $rancangan->menu->nama }}

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bahan</th>
                            <th>Gramasi</th>
                            <th>Harga</th>
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
                                    {{ $resep->gramasi }}
                                    {{ $resep->bahanPangan->satuan }}
                                </td>
                                <td>
                                    Rp.
                                    {{ number_format($resep->bahanPangan->harga_jual) }}/{{ $resep->bahanPangan->satuan }}
                                </td>
                                <td class="text-end">
                                    Rp.
                                    {{ number_format($resep->bahanPangan->harga_jual * $resep->gramasi) }}
                                </td>
                            </tr>

                            @php
                                $total += $resep->bahanPangan->harga_jual * $resep->gramasi;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">Total</td>
                            <td class="text-end">
                                Rp.
                                {{ number_format($total) }}
                                @php
                                    $total_harga += $total;
                                @endphp
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </li>
    @endforeach
</ol>

<div class="d-flex justify-content-end">
    <i>ESTIMASI TOTAL HARGA PER PORSI Rp. {{ number_format($total_harga) }}
    </i>
</div>
