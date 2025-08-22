    <div class="modal fade" id="NP-Pemanfaat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" id="formTitle"></h4>
                        <p>“Masukkan Pemanfaat”</p>
                    </div>
                    <form action="" method="post" id="FormNamaPemanfaat" autocomplete="off">
                        @csrf

                        <input type="hidden" id="id_NP" name="id_NP">
                        <div class="row">
                            <div class="col-12 col-md-6 kp-wrapper">
                                <label class="form-label" for="data_pemanfaat_id">Nama Lembaga</label>
                                <select id="data_pemanfaat_id" name="data_pemanfaat_id"
                                    class="form-control data_pemanfaat_id select2"></select>
                                <div class="kp-info mt-1 text-success"></div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nama">Nama</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="masukkan nama anda" />
                                <small id="msg_nama" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                    placeholder="Masukkan tempat lahir" />
                                <small id="msg_tempat_lahir" class="text-danger"></small>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="text" id="tanggal_lahir" name="tanggal_lahir"
                                    class="form-control dob-picker" placeholder="Masukkan tanggal lahir" />
                                <small id="msg_tanggal_lahir" class="text-danger"></small>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="status">Status</label>
                                <input type="text" id="status" name="status" class="form-control"
                                    placeholder="Masukkan status" />
                                <small id="msg_status" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="SimpanNamaPemanfaat" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
