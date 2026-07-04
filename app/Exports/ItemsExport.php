<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @param  array<string,mixed>  $filters
     */
    public function __construct(private readonly array $filters = [])
    {
    }

    public function collection()
    {
        return Item::query()
            ->with(['category', 'lab', 'labTable.group'])
            ->when($this->filters['category_id'] ?? null, fn ($q, $v) => $q->where('category_id', $v))
            ->when($this->filters['lab_id'] ?? null, fn ($q, $v) => $q->where('lab_id', $v))
            ->when($this->filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($this->filters['q'] ?? null, fn ($q, $v) => $q->search($v))
            ->orderBy('nama')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Barang', 'Kategori', 'Lokasi', 'Jumlah', 'Status', 'Keterangan', 'Diperbarui'];
    }

    /**
     * @param  Item  $item
     */
    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->nama,
            $item->category?->nama,
            $item->lokasi_lengkap,
            $item->jumlah_total,
            $item->status->label(),
            $item->keterangan,
            $item->updated_at?->format('d-m-Y H:i'),
        ];
    }
}
