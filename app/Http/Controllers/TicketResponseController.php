<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TicketResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ticket.response.create', ['only' => ['store']]);
        $this->middleware('permission:ticket.response.delete', ['only' => ['destroy']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $request->message,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Response added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, TicketResponse $response): RedirectResponse
    {
        $response->delete();
        return redirect()->route('tickets.show', $ticket)->with('success', 'Response deleted successfully.');
    }
}
