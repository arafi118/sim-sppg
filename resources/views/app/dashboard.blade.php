@extends('app.layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-6">
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="icon-base bx bxs-truck icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">42</h4>
                            </div>
                            <p class="mb-2">On route vehicles</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-warning"><i
                                            class="icon-base bx bx-error icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">8</h4>
                            </div>
                            <p class="mb-2">Vehicles with errors</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-danger h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                            class="icon-base bx bx-git-repo-forked icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">27</h4>
                            </div>
                            <p class="mb-2">Deviated from route</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="icon-base bx bx-time-five icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">13</h4>
                            </div>
                            <p class="mb-2">Late vehicles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
