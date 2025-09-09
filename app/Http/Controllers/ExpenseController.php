<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['account', 'user'])->latest()->paginate(15);
        return view('dashboard.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = Account::active()->orderBy('code')->get();
        return view('dashboard.expenses.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id'  => 'required|exists:accounts,id',
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:0',
            'reference'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        DB::transaction(function () use ($validated) {
            $expense = Expense::create($validated);
            $expense->account->decrement('current_balance', $expense->amount);

            Log::channel('expenses')->info('Expense recorded', [
                'expense_id'   => $expense->id,
                'user_id'      => $expense->user_id,
                'account_id'   => $expense->account_id,
                'amount'       => $expense->amount,
                'date'         => $expense->date,
                'reference'    => $expense->reference,
                'description'  => $expense->description,
                'timestamp'    => now()->toDateTimeString(),
            ]);
        });

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['account', 'user']);
        return view('dashboard.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $accounts = Account::active()->orderBy('code')->get();
        return view('dashboard.expenses.edit', compact('expense', 'accounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'account_id'  => 'required|exists:accounts,id',
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:0',
            'reference'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $expense) {
            $originalAmount = $expense->amount;
            $originalAccountId = $expense->account_id;

            $expense->update($validated);

            if ($originalAccountId !== $expense->account_id) {
                Account::where('id', $originalAccountId)->increment('current_balance', $originalAmount);
                Account::where('id', $expense->account_id)->decrement('current_balance', $expense->amount);
            } else {
                $difference = $validated['amount'] - $originalAmount;

                if ($difference > 0) {
                    $expense->account->decrement('current_balance', $difference);
                } elseif ($difference < 0) {
                    $expense->account->increment('current_balance', abs($difference));
                }
            }

            Log::channel('expenses')->info('Expense updated', [
                'expense_id'   => $expense->id,
                'user_id'      => $expense->user_id,
                'account_id'   => $expense->account_id,
                'old_amount'   => $originalAmount,
                'new_amount'   => $validated['amount'],
                'date'         => $expense->date,
                'reference'    => $expense->reference,
                'description'  => $expense->description,
                'timestamp'    => now()->toDateTimeString(),
            ]);
        });

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            $expense->loadMissing('account');

            if ($expense->account) {
                $expense->account->increment('current_balance', $expense->amount);
            }

            Log::channel('expenses')->warning('Expense deleted', [
                'expense_id'   => $expense->id,
                'user_id'      => auth()->id(),
                'account_id'   => $expense->account_id,
                'amount'       => $expense->amount,
                'timestamp'    => now()->toDateTimeString(),
            ]);

            $expense->delete();
        });

        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
