<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DueDateCalculatorService;
use DateTime;

class CalculateDueDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:due-date "{submitDateTime}" {turnaroundTime}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate due date based on submit date and turnaround time.\nSubmit date format: Y-m-d H:i:s, turnaround time in hours, format: float';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Parse command line arguments
        $submitDate = new DateTime($this->argument('submitDateTime'));
        $turnaroundTime = (float)$this->argument('turnaroundTime');

        // Create calculator service instance
        $calculator = new DueDateCalculatorService();

        try {
            // Calculate due date
            $dueDate = $calculator->calculateDueDate($submitDate, $turnaroundTime);

            // Output result
            $this->info("Due date: " . $dueDate->format('Y-m-d H:i'));
        } catch (\Exception $e) {
            // Output error message
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}