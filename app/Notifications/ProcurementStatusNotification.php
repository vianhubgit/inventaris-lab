<?php

namespace App\Notifications;

use App\Models\Procurement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProcurementStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public Procurement $procurement)
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
            'type' => 'procurement-status',
            'icon' => 'procurement',
            'title' => 'Status pengajuan diperbarui',
            'message' => $this->procurement->nama_barang.' → '.$this->procurement->status->label(),
            'url' => route('sekretaris.procurements.show', $this->procurement->id),
        ];
    }
}
