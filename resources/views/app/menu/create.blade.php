@extends('app.layouts.app')

@section('content')
    <form action="/app/menu" method="post">
        @csrf

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-6">
                            <label class="form-label" for="nama_menu">Nama Menu</label>
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu"
                                placeholder="Nama Menu">
                        </div>
                    </div>

                    <div class="col-12 form-repeater">
                        <div data-repeater-list="group-a">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                        <label for="form-repeater-1-1" class="form-label">Basic</label>
                                        <select id="form-repeater-1-1" class="select2 form-select form-select-lg"
                                            data-allow-clear="true">
                                            <option value="AK">Alaska</option>
                                            <option value="HI">Hawaii</option>
                                            <option value="CA">California</option>
                                            <option value="NV">Nevada</option>
                                            <option value="OR">Oregon</option>
                                            <option value="WA">Washington</option>
                                            <option value="AZ">Arizona</option>
                                            <option value="CO">Colorado</option>
                                            <option value="ID">Idaho</option>
                                            <option value="MT">Montana</option>
                                            <option value="NE">Nebraska</option>
                                            <option value="NM">New Mexico</option>
                                            <option value="ND">North Dakota</option>
                                            <option value="UT">Utah</option>
                                            <option value="WY">Wyoming</option>
                                            <option value="AL">Alabama</option>
                                            <option value="AR">Arkansas</option>
                                            <option value="IL">Illinois</option>
                                            <option value="IA">Iowa</option>
                                            <option value="KS">Kansas</option>
                                            <option value="KY">Kentucky</option>
                                            <option value="LA">Louisiana</option>
                                            <option value="MN">Minnesota</option>
                                            <option value="MS">Mississippi</option>
                                            <option value="MO">Missouri</option>
                                            <option value="OK">Oklahoma</option>
                                            <option value="SD">South Dakota</option>
                                            <option value="TX">Texas</option>
                                            <option value="TN">Tennessee</option>
                                            <option value="WI">Wisconsin</option>
                                            <option value="CT">Connecticut</option>
                                            <option value="DE">Delaware</option>
                                            <option value="FL">Florida</option>
                                            <option value="GA">Georgia</option>
                                            <option value="IN">Indiana</option>
                                            <option value="ME">Maine</option>
                                            <option value="MD">Maryland</option>
                                            <option value="MA">Massachusetts</option>
                                            <option value="MI">Michigan</option>
                                            <option value="NH">New Hampshire</option>
                                            <option value="NJ">New Jersey</option>
                                            <option value="NY">New York</option>
                                            <option value="NC">North Carolina</option>
                                            <option value="OH">Ohio</option>
                                            <option value="PA">Pennsylvania</option>
                                            <option value="RI">Rhode Island</option>
                                            <option value="SC">South Carolina</option>
                                            <option value="VT">Vermont</option>
                                            <option value="VA">Virginia</option>
                                            <option value="WV">West Virginia</option>
                                        </select>
                                    </div>
                                    <div class="mb-6 col-lg-12 col-xl-2 col-12 d-flex align-items-end mb-0">
                                        <button type="button" class="btn btn-label-danger" data-repeater-delete>
                                            <i class="icon-base bx bx-x me-1"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="mb-0">
                            <button type="button" class="btn btn-primary" data-repeater-create>
                                <i class="icon-base bx bx-plus me-1"></i>
                                <span class="align-middle">Add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/cleave-zen@0.0.17/dist/cleave-zen.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
        integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        const repeaterForm = $(".form-repeater");
        if (repeaterForm.length) {
            let groupIndex = 2;
            let fieldIndex = 1;

            repeaterForm.repeater({
                show: function() {
                    let hasSelect2 = false;

                    $(this)
                        .find(".form-control, .form-select")
                        .each((i, el) => {
                            const id = `form-repeater-${groupIndex}-${fieldIndex}`;
                            $(el).attr("id", id);
                            $(this).find(".form-label").eq(i).attr("for", id);

                            if ($(el).hasClass("select2")) {
                                hasSelect2 = true;
                            }
                            fieldIndex++;
                        });

                    groupIndex++;
                    $(this).slideDown();

                    if (hasSelect2) setSelect2();
                },
                hide: function(e) {
                    Swal.fire({
                        title: "Hapus input?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).slideUp(e);
                        }
                    })
                },
            });
        }
    </script>
@endsection
