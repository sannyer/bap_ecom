<?php

namespace App\Http\Controllers;

use App\Services\DueDateCalculatorService;
use DateTime;
use Illuminate\Http\Request;

class DueDateCalculatorController extends Controller
{
    public function calculateDueDate(Request $request)
    {
        $submitDate = new DateTime($request->input('submit_date'));
        $turnaroundTime = $request->input('turnaround_time');

        $calculator = new DueDateCalculatorService();
        $dueDate = $calculator->calculateDueDate($submitDate, $turnaroundTime);

        return response()->json([
            'due_date' => $dueDate->format('Y-m-d H:i:s'),
        ]);
    }
}