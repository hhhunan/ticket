<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total' => Ticket::count(),
            'new' => Ticket::where('status', 'new')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'processed' => Ticket::where('status', 'processed')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
