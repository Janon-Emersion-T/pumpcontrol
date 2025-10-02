<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountTransferController extends Controller
{
    /**
     * Display a list of transfers.
     */
    public function index()
    {
        $transfers = AccountTransfer::with(['user', 'fromAccount', 'toAccount'])->latest()->paginate(15);
        return view('dashboard.account_transfers.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new transfer.
     */
    public function create()
    {
        $accounts = Account::where('is_active', true)->get();
        return view('dashboard.account_transfers.create', compact('accounts'));
    }

    /**
     * Store a newly created transfer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_account_id' => 'required|exists:accounts,id|different:to_account_id',
            'to_account_id'   => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:0.01',
            'description'     => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $from = Account::lockForUpdate()->findOrFail($validated['from_account_id']);
                $to = Account::lockForUpdate()->findOrFail($validated['to_account_id']);

                if ($from->current_balance < $validated['amount']) {
                    Log::channel('account_transfers')->warning('Transfer failed - Insufficient funds', [
                        'user_id' => Auth::id(),
                        'from_account_id' => $from->id,
                        'to_account_id' => $to->id,
                        'attempted_amount' => $validated['amount'],
                        'from_balance' => $from->current_balance,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                    abort(400, 'Insufficient funds in source account.');
                }

                $from->decrement('current_balance', $validated['amount']);
                $to->increment('current_balance', $validated['amount']);

                $transfer = AccountTransfer::create([
                    'user_id'         => Auth::id(),
                    'from_account_id' => $from->id,
                    'to_account_id'   => $to->id,
                    'amount'          => $validated['amount'],
                    'description'     => $validated['description'] ?? null,
                ]);

                Log::channel('account_transfers')->info('Transfer completed successfully', [
                    'transfer_id' => $transfer->id,
                    'user_id' => $transfer->user_id,
                    'from_account_id' => $transfer->from_account_id,
                    'to_account_id' => $transfer->to_account_id,
                    'amount' => $transfer->amount,
                    'description' => $transfer->description,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            });

            return redirect()->route('account-transfers.index')->with('success', 'Transfer completed successfully.');
        } catch (\Throwable $e) {
            Log::channel('account_transfers')->error('Transfer failed with system error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return back()->withErrors('Something went wrong during the transfer process.')->withInput();
        }
    }
}
