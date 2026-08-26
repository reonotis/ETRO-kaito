<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TicketInfoController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('ticket_info.index');
    }
}
