<?php

namespace App\Http\Controllers;

use App\Models\EmailNotification;
use App\Models\Ticket;
use App\Notifications\TicketClosedNotification;
use App\Notifications\TicketOpenNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ticket.index', ['only' => ['index']]);
        $this->middleware('permission:ticket.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ticket.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:ticket.delete', ['only' => ['destroy']]);
        $this->middleware('permission:ticket.close', ['only' => ['closeTicket']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('customer')->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ticket = Ticket::create([
            'customer_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'open',
        ]);

        // Send email notification to admin
        Notification::send($ticket->customer, new TicketOpenNotification($ticket));

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,closed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ticket->update($request->only('subject', 'description', 'status'));

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function closeTicket(Ticket $ticket)
    {
        // Close the ticket
        $ticket->status = 'closed';
        $ticket->save();

        // Notify the user via email that their ticket has been closed
        if (Auth::check()) {
            $user = Auth::user();

            // Create an email notification record in the database
            EmailNotification::create([
                'user_id' => $user->id,
                'ticket_id' => $ticket->id,
                'type' => 'ticket_closed',
            ]);

            // Send email notification to the ticket's owner
            Notification::send($ticket->customer, new TicketClosedNotification($ticket));
        }

        // Redirect to the tickets index with a success message
        return redirect()->route('tickets.index')->with('success', 'Ticket closed successfully.');
    }
}
