@extends('app.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Upload Presensi</h5>
                <div class="card-body">
                    <form id="formValidationExamples" class="row g-3">
                        <div class="col-md-6 form-control-validation mb-3">
                            <label class="form-label" for="formValidationSelect2">Nama Karyawan</label>
                            <select id="formValidationSelect2" name="formValidationSelect2" class="form-select select2"
                                data-allow-clear="true">
                                <option value="">-- Pilih Karyawan --</option>
                                <option value="Australia">Australia</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="United States">United States</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="flatpickr-date" class="form-label">Tanggal Presensi</label>
                            <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="flatpickr-date" />
                        </div>

                        <div class="col-md-6 form-control-validation mb-3">
                            <label class="form-label" for="formValidationSelect2">11111</label>
                            <select id="formValidationSelect2" name="formValidationSelect2" class="form-select select2"
                                data-allow-clear="true">
                                <option value="">Select</option>
                                <option value="Australia">Australia</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="United States">United States</option>
                            </select>
                        </div>

                        <div class="col-md-6 col-12 mb-6">
                            <label for="flatpickr-time" class="form-label">Jam Masuk</label>
                            <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr-time" />
                        </div>

                        {{-- <div class="col-md-6 form-control-validation mb-3">
                            <label class="form-label" for="formValidationLang">Languages</label>
                            <input type="text" value="" class="form-control" name="formValidationLang"
                                id="formValidationLang" />
                        </div>

                        <div class="col-md-6 form-control-validation mb-3">
                            <label class="form-label" for="formValidationTech">Tech</label>
                            <input class="form-control typeahead" type="text" id="formValidationTech"
                                name="formValidationTech" autocomplete="off" />
                        </div> --}}

                        <div class="col-12 form-control-validation text-end">
                            <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                flatpickr("#flatpickr-date", {
                    dateFormat: "Y-m-d"
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Date picker
                flatpickr("#flatpickr-date", {
                    dateFormat: "Y-m-d"
                });

                // DOB (Date of Birth)
                flatpickr("#formValidationDob", {
                    dateFormat: "Y-m-d"
                });

                // Time picker
                flatpickr("#flatpickr-time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
            });
        </script>
    @endsection
