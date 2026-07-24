<?php

namespace App\Exports;

use App\Enums\ItemStatus;
use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @param array<string,mixed> $filters
     */
    public function __construct(
        private readonly array $filters = []
    ) {
    }

    public function collection(): Collection
    {
        $items = Item::query()
            ->with(['category', 'lab', 'labTable.group'])
            ->when($this->filters['category_id'] ?? null,
                fn($q, $v) => $q->where('category_id', $v))
            ->when($this->filters['lab_id'] ?? null,
                fn($q, $v) => $q->where('lab_id', $v))
            ->when($this->filters['status'] ?? null,
                fn($q, $v) => $q->where('status', $v))
            ->when($this->filters['q'] ?? null,
                fn($q, $v) => $q->search($v))
            ->get();

        $labOrder = [
            'Lab A' => 1,
            'Lab B' => 2,
            'TEFA' => 3,
        ];

        $statusOrder = [
            ItemStatus::BAIK->value => 1,
            ItemStatus::RUSAK->value => 2,
            ItemStatus::HILANG->value => 3,
            ItemStatus::PERBAIKAN->value => 4,
        ];

        $items = $items
            ->sort(function ($a, $b) use ($labOrder, $statusOrder) {

                $labCompare =
                    ($labOrder[$a->lab->nama] ?? 99)
                    <=>
                    ($labOrder[$b->lab->nama] ?? 99);

                if ($labCompare != 0) {
                    return $labCompare;
                }

                $statusCompare =
                    ($statusOrder[$a->status->value] ?? 99)
                    <=>
                    ($statusOrder[$b->status->value] ?? 99);

                if ($statusCompare != 0) {
                    return $statusCompare;
                }

                return strcmp($a->nama, $b->nama);

            })
            ->values();

        $result = collect();

        foreach ($items as $item) {

            /*
            |--------------------------------------------------------------------------
            | STATUS BAIK
            |--------------------------------------------------------------------------
            | Digabung berdasarkan
            | Lab + Kategori + Nama Barang
            */

            if ($item->status === ItemStatus::BAIK) {

                $key = implode('|', [
                    $item->lab_id,
                    $item->category_id,
                    $item->nama,
                    ItemStatus::BAIK->value,
                ]);

                if (! isset($result[$key])) {

                    $result[$key] = (object) [

                        'nama' => $item->nama,

                        'kategori' => $item->category?->nama,

                        'lokasi' => $item->lab->nama,

                        'jumlah' => $item->jumlah_total,

                        'status' => $item->status,

                        'keterangan' => $item->keterangan,

                        'updated_at' => $item->updated_at,

                    ];

                } else {

                    $result[$key]->jumlah += $item->jumlah_total;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | STATUS RUSAK / HILANG / PERBAIKAN
            |--------------------------------------------------------------------------
            */

            else {

                $result->push((object)[

                    'nama' => $item->nama,

                    'kategori' => $item->category?->nama,

                    'lokasi' => $item->lokasi_lengkap,

                    'jumlah' => $item->jumlah_total,

                    'status' => $item->status,

                    'keterangan' => $item->keterangan,

                    'updated_at' => $item->updated_at,

                ]);

            }

        }

        return collect($result)->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Kategori',
            'Lokasi',
            'Jumlah',
            'Status',
            'Keterangan',
            'Diperbarui',
        ];
    }

    public function map($item): array
    {
        static $no = 0;

        return [

            ++$no,

            $item->nama,

            $item->kategori,

            $item->lokasi,

            $item->jumlah,

            $item->status->label(),

            $item->keterangan,

            optional($item->updated_at)->format('d-m-Y H:i'),

        ];
    }
}
