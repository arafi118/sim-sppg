@php
    $profilePicture =
        $karyawan->foto && file_exists(storage_path('app/public/' . $karyawan->foto))
            ? asset('storage/' . $karyawan->foto)
            : asset('/assets/img/landing-page/default.png');
@endphp

@extends('app.layouts.app')

@section('content')
    <style>
        .id-card {
            position: relative;
            width: 302.36px;
            height: 468.66px;
            overflow: hidden;
            background: #FCFCFF;
        }

        .header-id-card {
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1;
        }

        .header-id-card img {
            width: 100%;
            height: auto;
        }

        .title-id-card {
            position: absolute;
            top: 0;
            left: 0;
            padding: 4px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 4px;
        }

        .title-id-card img {
            width: 32px;
            height: 32px;
        }

        .title-id-card .business-name {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
        }

        .business-name .main-title {
            font-size: 10px;
            font-weight: bold;
            color: #FCFCFF;
            text-transform: uppercase;
        }

        .business-name .sub-title {
            font-size: 8px;
            font-weight: 600;
            color: #FCFCFF;
            text-transform: uppercase;
        }

        .body-id-card {
            margin-top: 48px;
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .profile-id-card {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .profile-id-card .avatar {
            position: relative;
            width: 128px;
            height: 128px;
            border-radius: 4px;
        }

        .profile-id-card .avatar::before {
            content: "";
            position: absolute;
            top: 8px;
            left: -8px;
            width: 100%;
            height: 100%;
            border: 1px solid #11122A;
            border-radius: 4px;
            box-sizing: border-box;
            z-index: 2;
        }

        .profile-id-card .avatar img {
            position: relative;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
            z-index: 3;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
        }

        .profile-id-card .profile-info {
            margin-top: 32px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .profile-info .profile-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .profile-info .profile-role {
            font-size: 14px;
            color: #11122A;
        }

        .profile-info .line {
            margin-top: 12px;
            width: 106px;
            height: 1px;
            background: #11122A;
            border-radius: 99px;
        }

        .profile-id-card .qr-id-card {
            margin-top: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-id-card .qr-container {
            position: relative;
            padding: 6px;
            border-left: 1px solid #11122A;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .qr-container img {
            width: 42px;
            height: 42px;
        }

        .qr-container .profile-data {
            font-size: 12px;
            color: #11122A;
        }

        .footer-id-card {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1;
        }

        .footer-id-card img {
            width: 100%;
            height: auto;
        }

        .middle-line {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 0;
        }

        .middle-line img {
            width: 100%;
            height: auto;
        }
    </style>

    <div class="id-card">
        {{-- Header --}}
        <div class="header-id-card">
            <img src="{{ asset('assets/img/id-card/Header.png') }}" alt="Header">
            <div class="title-id-card">
                <img src="{{ asset('assets/img/Logo.png') }}" alt="Logo">
                <div class="business-name">
                    <div class="main-title">BADAN GIZI NASIONAL</div>
                    <div class="sub-title">SATUAN PELAYANAN PEMENUHAN GIZI</div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="body-id-card">
            <div class="profile-id-card">
                <div class="avatar">
                    <img src="{{ $profilePicture }}" alt="Profile">
                </div>

                <div class="profile-info">
                    <div class="profile-name text-primary">
                        {{ $karyawan->nama }}
                    </div>
                    <div class="profile-role">
                        {{ $karyawan->level->nama }}
                    </div>
                    <div class="line"></div>
                </div>

                <div class="qr-id-card">
                    <div class="qr-container">
                        <img src="{{ $dataUri }}" alt="QR Code">
                        <div class="profile-data">
                            <div>Telp : {{ $karyawan->telpon }}</div>
                            <div>Join : {{ $karyawan->tanggal_masuk }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer-id-card">
            <img src="{{ asset('assets/img/id-card/Footer.png') }}" alt="Footer">
        </div>

        {{-- Overlay --}}
        <div class="middle-line">
            <img src="{{ asset('assets/img/id-card/Body Overlay.png') }}" alt="Middle Line">
        </div>
    </div>
@endsection
