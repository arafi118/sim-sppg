@extends('app.pelaporan.layout.base')
@section('content')
    <div class="judul">Proposal <br>Program Makanan Bergizi Gratis Tahun {{ $tgl }}</div>
    <div class="subjudul">Yayasan .............</div>
    <ol class="nested-numbering">
        <li>BAB I PENDAHULUAN
            <ol>
                <li>LATAR BELAKANG
                    <div class="paragraf">
                        Masalah gizi buruk masih menjadi tantangan besar di berbagai daerah, terutama di desa terpencil yang
                        memiliki akses terbatas terhadap makanan bergizi. Kondisi ini dapat berdampak negatif pada kesehatan
                        dan perkembangan anak-anak serta produktivitas masyarakat. Oleh karena itu, diperlukan langkah nyata
                        untuk memberikan akses makanan bergizi kepada masyarakat yang membutuhkan, sekaligus meningkatkan
                        kesadaran akan pentingnya pola makan sehat.
                    </div>
                    <div class="paragraf">
                        Indonesia sebagai negara berkembang menghadapi berbagai tantangan di bidang kesehatan masyarakat,
                        salah satunya adalah prevalensi stunting yang tinggi akibat kekurangan gizi kronis. Data dari
                        Kementerian Kesehatan menunjukkan bahwa anak-anak di bawah usia lima tahun di beberapa wilayah
                        mengalami kekurangan gizi, yang berdampak pada tingkat kecerdasan dan daya saing mereka di masa
                        depan. Program intervensi yang terfokus pada penyediaan makanan bergizi menjadi solusi yang mendesak
                        untuk diterapkan.
                    </div>
                    <div class="paragraf">
                        Selain itu, kesadaran masyarakat tentang pentingnya pola makan sehat masih rendah. Banyak keluarga,
                        terutama di wilayah pedesaan, belum memahami pentingnya asupan makanan bergizi untuk menunjang
                        kesehatan. Hal ini diperparah oleh keterbatasan ekonomi yang membuat sebagian besar keluarga hanya
                        mampu menyediakan makanan pokok tanpa memikirkan kandungan nutrisinya.
                    </div class="paragraf">
                    <div class="paragraf">
                        Program ini dirancang untuk memberikan dampak jangka pendek dan jangka panjang. Dalam jangka pendek,
                        diharapkan masyarakat dapat merasakan manfaat langsung dari makanan bergizi yang disediakan. Dalam
                        jangka panjang, program ini bertujuan untuk meningkatkan kualitas hidup masyarakat melalui edukasi
                        berkelanjutan tentang pentingnya pola makan yang sehat.
                    </div>
                </li>
                <li>
                    Tujuan <br>Program ini bertujuan untuk:
                    <ol>
                        <li>
                            Memberikan makanan bergizi secara gratis kepada masyarakat yang membutuhkan.
                        </li>
                        <li>
                            Meningkatkan kesadaran masyarakat akan pentingnya pola makan sehat.
                        </li>
                        <li>
                            Meningkatkan akses makanan gizi seimbang masyarakat di lokasi sasaran.
                        </li>
                    </ol>
                </li>
            </ol>
        </li><br>
        <li>BAB II PELAKSANAAN KEGIATAN
            <ol>
                <li>Lokasi <br> Kegiatan ini akan dilaksanakan di:
                    <table border="0" width="100%">
                        <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="13%">Alamat</td>
                            <td width="1%">:</td>
                            <td width="89%"></td>
                        </tr>
                        <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="13%">Desa/Kelurahan </td>
                            <td width="1%">:</td>
                            <td width="89%"></td>
                        </tr>
                        <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="13%">Kecamatan </td>
                            <td width="1%">:</td>
                            <td width="89%"></td>
                        </tr>
                        <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="13%">Kabupaten/Kota </td>
                            <td width="1%">:</td>
                            <td width="89%"></td>
                        </tr>
                        <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="13%">Provinsi </td>
                            <td width="1%">:</td>
                            <td width="89%"></td>
                        </tr>
                    </table>
                </li>
                <li>
                    Sasaran <br>
                    kegiatan ini adalah (Diisi sesuai dengan jumlah penerima manfaat yang akan dilayani di sekitar SPPG,
                    dengan radius sekitar 6km dan/atau jarak tempuh maksimum 20 menit). Menetapkan sasaran jumlah penerima
                    manfaat sesuai kategori:
                    <table border="0" width="100%">
                        <tr>
                            <td width="2%">1.)</td>
                            <td width="47%">Anak Balita</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">2.)</td>
                            <td width="47%">PAUD/TK</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">3.)</td>
                            <td width="47%">SD kelas 1 sampai dengan kelas 3</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">4.)</td>
                            <td width="47%">SD kelas 4 sampai dengan kelas 6</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">5.)</td>
                            <td width="47%">SMP sederajat</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">6.)</td>
                            <td width="47%">SMA sederajat </td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">7.)</td>
                            <td width="47%">Sekolah keagamaan</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">8.)</td>
                            <td width="47%">Ibu hamil</td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                        <tr>
                            <td width="2%">9.)</td>
                            <td width="47%">Ibu Menyusui </td>
                            <td width="1%">:</td>
                            <td width="50%"> .....orang</td>
                        </tr>
                    </table>
                </li>
            </ol>
        </li>
        <li>BAB III PROFIL ORGANISASI
            <ol>
                <li>Visi <br>
                    Mewujudkan masyarakat sehat dan sejahtera melalui pola makan bergizi.
                </li>
                <li>Misi
                    <ol>
                        <li>Memberikan akses makanan bergizi kepada masyarakat kurang mampu. </li>
                        <li>Menyediakan edukasi tentang pentingnya gizi seimbang.</li>
                        <li>Bekerja sama dengan berbagai pihak untuk mendukung keberlanjutan program.</li>
                    </ol>
                </li>
                <li>PROGRAM KERJA <br>Yayasan memiliki beberapa program kerja, termasuk:
                    <ol>
                        <li>Penyiapan dapur makan bergizi gratis. </li>
                        <li>Penyediaan makanan bergizi gratis. </li>
                        <li>Monitoring dan evaluasi program makan bergizi gratis.</li>
                    </ol>
                </li>
                <li>KEGIATAN<table border="0" width="100%">
                        <tr>
                            <td width="3%" valign="top">1.</td>
                            <td width="20%" valign="top">Nama Kegiatan</td>
                            <td width="2%" valign="top">:</td>
                            <td width="75%" valign="top" class="ratakanankiri">
                                Program Makan Bergizi Gratis.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">2.</td>
                            <td valign="top">Bentuk Kegiatan</td>
                            <td valign="top">:</td>
                            <td valign="top" class="ratakanankiri">
                                Penyediaan makanan bergizi secara gratis kepada masyarakat sasaran, disertai dengan
                                edukasi singkat tentang pola makan sehat.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">3.</td>
                            <td valign="top">Dasar Pemikiran</td>
                            <td valign="top">:</td>
                            <td valign="top" class="ratakanankiri">
                                Gizi yang baik merupakan fondasi untuk kesehatan dan produktivitas masyarakat.
                                Memberikan akses makanan bergizi seimbang dapat membantu mengatasi masalah gizi
                                buruk secara langsung.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">4.</td>
                            <td valign="top">Tujuan Kegiatan</td>
                            <td valign="top">:</td>
                            <td valign="top" class="ratakanankiri">
                                Meningkatkan status gizi masyarakat dan kesadaran tentang pentingnya pola makan sehat.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">5.</td>
                            <td valign="top" colspan="2">Pelaksanaan Kegiatan</td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">a. ) Lokasi</td>
                            <td valign="top">:</td>
                            <td valign="top" class="ratakanankiri">
                                Jl. .......... Desa .........., Kecamatan
                                .........., Kabupaten/Kota ........... Provinsi
                                ..........
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">b. ) Sasaran </td>
                            <td valign="top">:</td>
                            <td valign="top" class="ratakanankiri">
                                Siswa sekolah dari semua jenjang pendidikan, Ibu
                                hamil (bumil), ibu menyusui (busui), dan anak balita.
                            </td>
                        </tr>
                    </table>
                </li>
            </ol>
        </li>
    </ol>
    <div class="ratakanankiri">BAB IV STRUKTUR ORGANISASI <br>Struktur organisasi yang terlibat dalam pelaksanaan program
        ini adalah:
        <table border="0" width="100%">
            <tr>
                <td width="2%">1.</td>
                <td width="40%">Penanggung Jawab (Ketua Yayasan) </td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
            <tr>
                <td width="2%">2.</td>
                <td width="40%">Ketua SPPG </td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
            <tr>
                <td width="2%">3.</td>
                <td width="40%">Koordinator Tim Bahan Makanan </td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
            <tr>
                <td width="2%">4.</td>
                <td width="40%">Koordinator Tim Perlengkapan Dapur</td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
            <tr>
                <td width="2%">5.</td>
                <td width="40%">Koordinator Tim Masak dan Pemorsian</td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
            <tr>
                <td width="2%">6.</td>
                <td width="40%">Koordinator Tim Distribusi </td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
            <tr>
                <td width="2%">7.</td>
                <td width="40%">Koordinator Tim Manajemen Limbah</td>
                <td width="1%">:</td>
                <td width="57%"></td>
            </tr>
        </table>
    </div>
    <div class="page-break"></div>
    <table class="border-table">
        <tr>
            <th width="5%">No</th>
            <th width="15%">Kebutuhan </th>
            <th width="10%">Jumlah paket MBG*</th>
            <th width="10%">Harga Satuan**</th>
            <th width="10%">Biaya per hari</th>
            <th width="10%">Jumlah Biaya per 2 minggu Tahap I</th>
            <th width="10%">Lebih/kurang paket MBG minggu lalu</th>
            <th width="10%">Lebih/Kurang Dana Minggu Lalu</th>
            <th width="10%">JumlahBiaya 2 Minggu Pengajuan Tahap II</th>
            <th width="10%">Keterangan </th>
        </tr>
        <tr>
            <th colspan="2">&nbsp;</th>
            <th>(Paket)<br>(3) </th>
            <th>(Rp.)<br>(4) </th>
            <th>(Rp.)<br>(5)=(3)*(4) </th>
            <th>(Rp.)<br>(6)=(5)*10 hr</th>
            <th>(Paket)</th>
            <th>(Rp.)<br>(8)</th>
            <th>(Rp.)<br>(9)=(6)/+(8) </th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <td>1.</td>
            <td>Bahan makanan anak balita, siswa TK/PAUD/RA dan SD/MI kls 1-3</td>
            <td>...</td>
            <td>8.000</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>Wilayah kemahalan dapat lebih dari Rp.8.000 (lihat lampiran 12 Indeks wilayah kemahalan)</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Bahan makanan siswa SD/MI kls 4-6, SMP/MTs, SMA/MA/SMK, ibu hamil dan ibu menyusui </td>
            <td>...</td>
            <td>10.000 </td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>Wilayah kemahalan dapat lebih dari Rp.10.000 (lihat lampiran 12 Indeks wilayah kemahalan) </td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Operasional (listrik, gas, air, honor relawan,asuransi kecelakaan kerja relawan, biaya bahan bakar
                minyak,internet,insentif kader, satpam dll); </td>
            <td>...</td>
            <td>Kisaran 2.000 – 3.000 per paket MBG </td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>Tidak ada indek kemahalan biaya operasional </td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Sewa (tanah, bangunan,peralatan dapur, peralatan masak, peralatan makan dan kendaraan dll) </td>
            <td>...</td>
            <td>Kisaran 1.500 – 2.000 per paket MBG</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>...</td>
            <td>Tidak ada indeks kemahalan biaya sewa</td>
        </tr>
        <tr>
            <td colspan="2">Total</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <div class="page-break"></div>
    <div class="ratakanankiri">BAB V RAB (Rencana Anggaran Biaya)</div>
    <div class="ratakanankiri">RAB disesuaikan harga pasar di wilayah pelaksanaan program makan bergizi gratis dan bersifat
        Lumsum. Keterangan:</div>
    <div class="ratakanankiri">*) Diisi sesuai jumlah penerima manfaat yang akan dilayani di sekitar SPPG dengan jarak
        sekitar 6 km dan/atau dengan jarak tempuh maksimal 30 menit dari lokasi SPPG.</div>
    <div class="ratakanankiri">**) Besaran harga satuan tersebut dapat disesuaikan untuk wilayah-wilayah yang memiliki
        indeks kemahalan relatif tinggi dibanding wilayah yang lain. </div>
    <div class="ratakanankiri">***) Jumlah hari makan efektif per bulan dihitung 20-23 hari (mengikuti ketentuan hari
        sekolah di wilayah masing-masing).</div>
    <div class="ratakanankiri">****) Jumlah hari makan efektif per tahun dihitung 220 hari (mengikuti ketentuan hari
        sekolah di wilayah masing-masing).</div>
    <div class="ratakanankiri">BAB VI PENUTUP </div>
    <div class="ratakanankiri">Program ini diharapkan bermanfaat bagi masyarakat. Informasi lebih lanjut: </div>
    <table border="0" width="100%">
        <tr>
            <td width="2%">&nbsp;</td>
            <td width="20%">Nama Yayasan </td>
            <td width="1%">:</td>
            <td width="79%"></td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td width="20%">Nomor Rekening Yayasan </td>
            <td width="1%">:</td>
            <td width="79%"></td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td width="20%">NPWP </td>
            <td width="1%">:</td>
            <td width="79%"></td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td width="20%">Nomor HP Contact Person </td>
            <td width="1%">:</td>
            <td width="79%"></td>
        </tr>
    </table><br><br>
    <table border="0" width="100%">
        <tr>
            <td width="2%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="79%">&nbsp;</td>
            <td width="20%">Yayasan ....... </td>
        </tr>
        <tr>
            <td colspan="4"><br><br><br></td>
        </tr>
        <tr>
            <td width="2%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="79%">&nbsp;</td>
            <td width="20%">( Ketua Yayasan )</td>
        </tr>
    </table>
@endsection
