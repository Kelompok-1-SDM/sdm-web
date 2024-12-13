<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }

        .header {
            text-align: left;
            margin-bottom: 20px;
        }

        .header div {
            margin: 5px 0;
        }

        .title {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }

        .content {
            margin-bottom: 20px;
            line-height: 120%;
        }

        .table-container {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
        }

        .signature div {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img src="{{ asset('polinema-bw.png') }}" style="width: 60; height= 60;">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN
                    PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI
                    MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang
                    65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101
                    105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <div class="header">
        <p>Nomor: _______</p>
        <p>Lampiran: -</p>
        <p>Perihal: Surat Tugas</p>
    </div>


    <div class="content">
        Kepada Yth.<br>
        Pembantu Direktur I Politeknik Negeri Malang<br>
        di Tempat<br><br>
        <br>
        Sehubungan dengan Kegiatan Peningkatan Kompetensi Sumber Daya Manusia diselenggarakan pelatihan tentang
        “{{ $kegiatan['judul'] }}” di {{ $kegiatan['lokasi'] }} Malang pada
        {{ \Carbon\Carbon::parse($kegiatan['tanggalMulai'])->format('m-F-Y H:i') }} sampai dengan
        {{ \Carbon\Carbon::parse($kegiatan['tanggalAkhir'])->format('m-F-Y H:i') }}. Maka dengan ini kami mohon dapat
        diterbitkan Surat Tugas kepada yang mengikuti yaitu:
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NIP</th>
                    <th>NAMA</th>
                    <th>JABATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kegiatan['users'] as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['nip'] }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['namaJabatan'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="content">
        Demikian permohonan ini atas perhatiannya kami sampaikan terima kasih.
    </div>

    <div class="signature">
        <div>Ketua Jurusan</div><br><br><br>
        <div>Rudy Ariyanto, ST., M.Cs</div>
        <div>NIP. 197111101999031002</div>
    </div>
</body>

</html>
