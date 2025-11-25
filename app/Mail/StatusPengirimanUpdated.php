<?php

namespace App\Mail;

use App\Models\TransaksiPenjualan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusPengirimanUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(TransaksiPenjualan $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Update Status Pengiriman')
                    ->view('emails.status_pengiriman');
    }
}
