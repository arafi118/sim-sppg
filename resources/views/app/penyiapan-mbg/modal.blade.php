    <div class="modal fade" id="PM-Mekanisme" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" id="formTitle"></h4>
                        <p>“Masukkan Tanggal Penyiapan MBG”</p>
                    </div>
                    <form action="" method="post" id="FormPenyiapanMekanisme" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id_PM" name="id_PM">

                        <div class="row">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="tanggal">Tanggal Penyiapan</label>
                                <input type="text" id="tanggal" name="tanggal" class="form-control"
                                    placeholder="Masukkan tanggal penyiapan" />
                                <small id="msg_tanggal" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="simpanPenyiapan" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
