<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReorderService;

class CheckStockLevels extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Check stock levels and create orders for low stock products';

    protected $reorderService;

    public function __construct(ReorderService $reorderService)
    {
        parent::__construct();
        $this->reorderService = $reorderService;
    }

    public function handle()
    {
        $this->reorderService->checkStockLevels();
        $this->info('Stock levels checked and orders placed if necessary.');
    }
}
