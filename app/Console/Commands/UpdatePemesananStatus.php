<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePemesananStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pemesanan:update-pemesanan-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // buat agar status pemesanan yang accept ketika melewati tanggal_pemesanan menjadi done
        $pemesanan = \App\Models\Pemesanan::where('status', 'accept')
            ->where('tanggal_pemesanan', '<', date('Y-m-d'))
            ->get();

        foreach ($pemesanan as $p) {
            $p->status = 'done';
            $p->save();
        }

        // buat agar status pemesanan yang pending ketika melewati tanggal_pemesanan menjadi decline
        $pemesanan = \App\Models\Pemesanan::where('status', 'pending')
            ->where('tanggal_pemesanan', '<', date('Y-m-d'))
            ->get();
        
        foreach ($pemesanan as $p) {
            $p->status = 'decline';
            $p->save();
        }
    }
}
