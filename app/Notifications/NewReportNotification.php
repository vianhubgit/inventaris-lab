<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification
{
    use Queueable;

    public function __construct(public Report $report)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'report',
            'icon' => 'report',
            'title' => 'Laporan '.$this->report->type->label().' baru',
            'message' => trim(($this->report->item?->nama ?? 'Barang').' di '.($this->report->lab?->nama ?? '-')
                .' dilaporkan oleh '.($this->report->user?->name ?? 'Sekretaris')),
            'url' => route('admin.reports.show', $this->report->id),
        ];
    }
}
