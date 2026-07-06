public function run(): void
{
    $categories = Category::pluck('id', 'nama');

    /*
    |=================================================
    | LAB A & LAB B
    |=================================================
    */

    foreach (['Lab A' => 'LAB_A', 'Lab B' => 'LAB_B'] as $nama => $kode) {

        $lab = Lab::updateOrCreate(
            ['kode' => $kode],
            [
                'nama' => $nama,
                'has_groups' => true,
                'keterangan' => 'Laboratorium praktik TKJ'
            ]
        );

        for ($g = 1; $g <= 6; $g++) {

            $group = $lab->groups()->updateOrCreate(
                ['nomor' => $g],
                ['nama' => "Kelompok {$g}"]
            );

            for ($m = 1; $m <= 6; $m++) {

                $table = $group->tables()->updateOrCreate(
                    ['nomor' => $m],
                    ['nama' => "Meja {$m}"]
                );

                // 1 SET PC PER MEJA (INI INTI SISTEM)
                Item::updateOrCreate(
                    [
                        'lab_id' => $lab->id,
                        'lab_table_id' => $table->id,
                        'nama' => "PC Set Kelompok {$g} Meja {$m}"
                    ],
                    [
                        'category_id' => $categories['Komputer'] ?? $categories->first(),
                        'jumlah_total' => 1,
                        'status' => ItemStatus::BAIK,
                    ]
                );

                // OPTIONAL: detail komponen bisa dipisah kalau nanti mau upgrade
            }
        }
    }

    /*
    |=================================================
    | TEFA
    |=================================================
    */

    $tefa = Lab::updateOrCreate(
        ['kode' => 'TEFA'],
        [
            'nama' => 'TEFA',
            'has_groups' => true,
            'keterangan' => 'Teaching Factory'
        ]
    );

    for ($l = 1; $l <= 6; $l++) {

        $lemari = $tefa->groups()->updateOrCreate(
            ['nomor' => $l],
            ['nama' => "Lemari {$l}"]
        );

        for ($r = 1; $r <= 6; $r++) {

            $lemari->tables()->updateOrCreate(
                ['nomor' => $r],
                ['nama' => "Rak {$r}"]
            );
        }
    }

    $tefaItems = [
        ['Switch Cisco 24 Port', 'Switch', 4],
        ['Router MikroTik RB951', 'MikroTik', 3],
        ['Access Point UniFi', 'Access Point', 5],
        ['Proyektor Epson', 'Proyektor', 2],
        ['Printer Laserjet', 'Printer', 2],
        ['UPS 1200VA', 'UPS', 3],
        ['Rak Server 19"', 'Rak Server', 1],
        ['Crimping Tool', 'Tang Crimping', 6],
        ['LAN Tester', 'LAN Tester', 4],
        ['Kabel UTP Cat6 (box)', 'Kabel LAN', 10],
        ['Konektor RJ45 (pack)', 'RJ45', 20],
    ];

    foreach ($tefaItems as [$namaBarang, $kategori, $jumlah]) {

        Item::updateOrCreate(
            [
                'lab_id' => $tefa->id,
                'nama' => $namaBarang
            ],
            [
                'category_id' => $categories[$kategori] ?? $categories->first(),
                'lab_table_id' => null,
                'jumlah_total' => $jumlah,
                'status' => ItemStatus::BAIK,
            ]
        );
    }
}
