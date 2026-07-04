<?php

namespace App\Notifications;

use App\Models\Procurement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProcurementNotification extends Notification
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
            'type' => 'procurement',
            'icon' => 'procurement',
            'title' => 'Pengajuan barang baru',
            'message' => $this->procurement->nama_barang.' ('.$this->procurement->jumlah.' unit) diajukan oleh '
                .($this->procurement->user?->name ?? 'Sekretaris'),
            'url' => route('admin.procurements.show', $this->procurement->id),
        ];
    }
}
