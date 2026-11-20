<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrderController;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid {--days=3 : Batas hari pesanan belum dibayar sebelum dibatalkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan otomatis pesanan berstatus pending yang belum dibayar dalam rentang waktu yang ditentukan (default 3 hari)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) ($this->option('days') ?: 3);
        $this->info("Memeriksa pesanan pending yang belum dibayar lebih dari {$days} hari...");

        $cancelledCount = OrderController::cancelUnpaidOrders($days);

        if ($cancelledCount > 0) {
            $this->info("Berhasil membatalkan {$cancelledCount} pesanan kadaluarsa dan mengembalikan stok produk.");
        } else {
            $this->info("Tidak ada pesanan pending yang melewati batas {$days} hari.");
        }

        return Command::SUCCESS;
    }
}

