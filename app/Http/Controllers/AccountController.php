<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function index()
    {
        Log::channel('account')->info('Account index viewed by user: ' . auth()->id());
        $accounts = Account::with('parent')->latest()->paginate(15);
        return view('dashboard.accounts.index', compact('accounts'));
    }

    public function create()
    {
        Log::channel('account')->info('Account create form accessed by user: ' . auth()->id());
        $parents = Account::orderBy('code')->get();
        return view('dashboard.accounts.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code|max:20',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Income,Expense,Equity',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'current_balance' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        $account = Account::create($validated);
        Log::channel('account')->info('Account created', ['user_id' => auth()->id(), 'account' => $account]);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load('parent', 'children');
        Log::channel('account')->info('Account viewed', ['user_id' => auth()->id(), 'account_id' => $account->id]);
        return view('dashboard.accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        Log::channel('account')->info('Account edit form accessed', ['user_id' => auth()->id(), 'account_id' => $account->id]);
        $parents = Account::where('id', '!=', $account->id)->orderBy('code')->get();
        return view('dashboard.accounts.edit', compact('account', 'parents'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Income,Expense,Equity',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'current_balance' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        $account->update($validated);
        Log::channel('account')->info('Account updated', ['user_id' => auth()->id(), 'account_id' => $account->id]);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        Log::channel('account')->warning('Account deleted', ['user_id' => auth()->id(), 'account_id' => $account->id]);
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted.');
    }
}
