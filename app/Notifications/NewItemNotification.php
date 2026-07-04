<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewItemNotification extends Notification
{
    use Queueable;

    public function __construct(public Item $item)
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
            'type' => 'item',
            'icon' => 'item',
            'title' => 'Barang baru ditambahkan',
            'message' => $this->item->nama.' ('.($this->item->category?->nama ?? '-').') di '
                .($this->item->lab?->nama ?? '-').' — jumlah '.$this->item->jumlah_total,
            'url' => route('sekretaris.inventory.show', $this->item->id),
        ];
    }
}
