<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @param  array<string,mixed>  $filters
     */
    public function __construct(private readonly array $filters = [])
    {
    }

    public function collection()
    {
        return Report::query()
            ->with(['user', 'lab', 'group', 'labTable', 'item'])
            ->when($this->filters['type'] ?? null, fn ($q, $v) => $q->where('type', $v))
            ->when($this->filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($this->filters['lab_id'] ?? null, fn ($q, $v) => $q->where('lab_id', $v))
            ->latest('reported_at')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Jenis', 'Barang', 'Lab', 'Kelompok', 'Meja', 'Jumlah', 'Status', 'Pelapor', 'Keterangan'];
    }

    /**
     * @param  Report  $report
     */
    public function map($report): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $report->reported_at?->format('d-m-Y H:i'),
            $report->type->label(),
            $report->item?->nama,
            $report->lab?->nama,
            $report->group?->display_name,
            $report->labTable?->display_name,
            $report->jumlah,
            $report->status->label(),
            $report->user?->name,
            $report->keterangan,
        ];
    }
}
