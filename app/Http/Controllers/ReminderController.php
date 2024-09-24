<?php

namespace App\Http\Controllers;

use App\Services\ReminderService;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    protected $reminderService;

    public function __construct(ReminderService $reminderService)
    {
        $this->reminderService = $reminderService;
    }

    public function changeStatus(Request $request, string $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,sent,visualized',
            ]);

            $this->reminderService->changeStatus($id, $request->status);

            return response()->json(['message' => 'Status changed successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
