    <div class="modal fade" id="KP-Pemanfaat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" id="formTitle"></h4>
                        <p>“Masukkan nama kelompok pemanfaat, misal: sekolah”</p>
                    </div>
                    <form action="" method="post" id="FormKelompokPemanfaat" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id_KP" name="id_KP">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nama">Kelompok Pemanfaat</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="sekolah" />
                                <small id="msg_nama" class="text-danger"></small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="kode">Kode</label>
                                <input type="text" id="kode" name="kode" class="form-control"
                                    placeholder="sekolah" />
                                <small id="msg_kode" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="SimpanKelompokPemanfaat" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
