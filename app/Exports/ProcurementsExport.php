<?php

namespace App\Exports;

use App\Models\Procurement;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProcurementsExport implements FromCollection, WithHeadings
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
        $rows = Procurement::query()
            ->with('user')
            ->when(
                $this->filters['status'] ?? null,
                fn ($q, $v) => $q->where('status', $v)
            )
            ->orderBy('requested_at')
            ->get();

        $groups = collect();

        foreach ($rows as $row) {

            $last = $groups->last();

            if (
                $last &&
                $last['user_id'] === $row->user_id &&
                Carbon::parse($last['requested_at'])
                    ->diffInMinutes($row->requested_at) <= 10
            ) {

                $last['items'][] = $row;

                $groups->pop();
                $groups->push($last);

            } else {

                $groups->push([
                    'requested_at' => $row->requested_at,
                    'user_id'      => $row->user_id,
                    'user'         => $row->user,
                    'items'        => [$row],
                ]);
            }
        }

        $export = collect();
        $no = 1;

        foreach ($groups as $group) {

            $first = true;

            foreach ($group['items'] as $item) {

                $export->push([
                    'No' => $first ? $no : '',
                    'Tanggal' => $first
                        ? $group['requested_at']->format('d-m-Y H:i')
                        : '',
                    'Peminjam' => $first
                        ? $group['user']?->name
                        : '',
                    'Barang' => $item->nama_barang,
                    'Jumlah' => $item->jumlah,
                    'Status' => $item->status->label(),
		    'Catatan_Admin' => $item->catatan_admin ?? '-',
                ]);

                $first = false;
            }

            $no++;
        }

        return $export;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Peminjam',
            'Barang',
            'Jumlah',
            'Status',
	    'Catatan Admin',
        ];
    }
}
