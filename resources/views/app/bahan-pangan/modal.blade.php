    <div class="modal fade" id="BP-Pangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" id="formTitle"></h4>
                        <p>“Masukkan nama bahan pangan, misal: Protein”</p>
                    </div>
                    <form action="" method="post" id="FormBahanPangan" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id_BP" name="id_BP">

                        <input type="hidden" id="id_BP" name="id_BP">
                        <div class="row">
                            <div class="col-12 col-md-6 kp-wrapper">
                                <label class="form-label" for="kelompok_pangan_id">Kelompok Pangan</label>
                                <select id="kelompok_pangan_id" name="kelompok_pangan_id"
                                    class="form-control select2"></select>
                                <div class="kp-info mt-1 text-success"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nama">Nama</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="Protein" />
                                <small id="msg_nama" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="satuan">Satuan</label>
                                    <select id="satuan" name="satuan" class="form-control select2">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="kg">Kilogram (kg)</option>
                                        <option value="g">Gram (g)</option>
                                        <option value="liter">Liter (L)</option>
                                        <option value="pcs">Pcs</option>
                                        <option value="pack">Pack</option>
                                        <option value="bungkus">Bungkus</option>
                                        <option value="dus">Dus</option>
                                        <option value="box">Box</option>
                                        <option value="ekor">Ekor</option>
                                        <option value="ikat">Ikat</option>
                                    </select>
                                    <small id="msg_satuan" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="harga_jual">Harga</label>
                                <input type="text" id="harga_jual" name="harga_jual" class="form-control"
                                    placeholder="Harga Jual" />
                                <small id="msg_harga_jual" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="SimpanBahanPangan" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
