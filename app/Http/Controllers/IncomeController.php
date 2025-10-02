<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with(['account', 'user'])->latest()->paginate(15);
        return view('dashboard.incomes.index', compact('incomes'));
    }

    public function create()
    {
        $accounts = Account::active()->orderBy('code')->get();
        return view('dashboard.incomes.create', compact('accounts'));
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
            $income = Income::create($validated);
            $income->account->increment('current_balance', $income->amount);

            Log::channel('incomes')->info('Income recorded', [
                'income_id'    => $income->id,
                'user_id'      => $income->user_id,
                'account_id'   => $income->account_id,
                'amount'       => $income->amount,
                'date'         => $income->date,
                'reference'    => $income->reference,
                'description'  => $income->description,
                'timestamp'    => now()->toDateTimeString(),
            ]);
        });

        return redirect()->route('incomes.index')->with('success', 'Income recorded successfully.');
    }

    public function show(Income $income)
    {
        $income->load(['account', 'user']);
        return view('dashboard.incomes.show', compact('income'));
    }

    public function edit(Income $income)
    {
        $accounts = Account::active()->orderBy('code')->get();
        return view('dashboard.incomes.edit', compact('income', 'accounts'));
    }

    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'account_id'  => 'required|exists:accounts,id',
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:0',
            'reference'   => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $income) {
            $originalAmount = $income->amount;
            $originalAccountId = $income->account_id;

            $income->update($validated);

            if ($originalAccountId !== $income->account_id) {
                Account::where('id', $originalAccountId)->decrement('current_balance', $originalAmount);
                Account::where('id', $income->account_id)->increment('current_balance', $income->amount);
            } else {
                $difference = $income->amount - $originalAmount;
                $income->account->increment('current_balance', $difference);
            }

            Log::channel('incomes')->info('Income updated', [
                'income_id'      => $income->id,
                'user_id'        => $income->user_id,
                'account_id'     => $income->account_id,
                'old_amount'     => $originalAmount,
                'new_amount'     => $income->amount,
                'date'           => $income->date,
                'reference'      => $income->reference,
                'description'    => $income->description,
                'timestamp'      => now()->toDateTimeString(),
            ]);
        });

        return redirect()->route('incomes.index')->with('success', 'Income updated successfully.');
    }

    public function destroy(Income $income)
    {
        DB::transaction(function () use ($income) {
            $income->account->decrement('current_balance', $income->amount);

            Log::channel('incomes')->warning('Income deleted', [
                'income_id'    => $income->id,
                'user_id'      => auth()->id(),
                'account_id'   => $income->account_id,
                'amount'       => $income->amount,
                'timestamp'    => now()->toDateTimeString(),
            ]);

            $income->delete();
        });

        return redirect()->route('incomes.index')->with('success', 'Income deleted.');
    }
}
