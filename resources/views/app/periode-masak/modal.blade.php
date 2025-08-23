    <div class="modal fade" id="PM-Masak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" id="formTitle"></h4>
                        <p>“Masukkan Periode Masak”</p>
                    </div>
                    <form action="" method="post" id="FormPeriodeMasak" autocomplete="off">
                        @csrf

                        <input type="hidden" id="id_PM" name="id_PM">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="periode_ke">Periode Ke</label>
                                <input type="number" id="periode_ke" name="periode_ke" class="form-control"
                                    placeholder="Masukkan periode ke" />
                                <small id="msg_periode_ke" class="text-danger"></small>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="tanggal_awal">Tanggal Mulai</label>
                                <input type="text" id="tanggal_awal" name="tanggal_awal"
                                    class="form-control dob-picker" placeholder="Masukkan tanggal awal" />
                                <small id="msg_tanggal_awal" class="text-danger"></small>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="tanggal_akhir">Tanggal Selesai</label>
                                <input type="text" id="tanggal_akhir" name="tanggal_akhir"
                                    class="form-control dob-picker" placeholder="Masukkan tanggal akhir" />
                                <small id="msg_tanggal_akhir" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="SimpanPeriodeMasak" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
