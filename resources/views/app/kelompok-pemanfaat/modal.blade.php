    <div class="modal fade" id="MK-Pangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" id="formTitle"></h4>
                        <p>“Masukkan nama bahan pangan, misal: Protein”</p>
                    </div>
                    <form action="" method="post" id="FormKelompokPangan" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id_MK" name="id_MK">

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="nama">Name</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Protein" />
                            <small id="msg_nama" class="text-danger"></small>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="SimpanKelompokPangan" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
