<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $data['expenses'] = Expense::where('user_id', Auth::user()->id) ->orderBy('expense_date', 'desc')->paginate(12);
        $data['totalExpenses'] = Expense::where('user_id', Auth::user()->id)->sum('expense_amount');
        $data['expenses_date'] = Expense::where('user_id', Auth::user()->id)->latest()->paginate(12);
        $data['types'] = Type::all();

        return view('pages.expenses.index', $data);
    }

    public function create()
    {
        $types = Type::all(); // Ottieni tutti i tipi dalla tabella types
        return view('pages.expenses.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_title' => 'required',
            'expense_amount' => 'required',
            'expense_date'=> 'required'
        ]);

        $expense = new Expense();
        $expense->expense_title = $request->expense_title;
        $expense->expense_amount = $request->expense_amount;
        $expense->expense_date = $request->expense_date;
        $expense->type_id = $request->expense_type_id;
        $expense->user_id = Auth::user()->id;
        $expense->save();

        return redirect('/expense')->with('message', __('messages.expense.success_add'));
    }

    public function edit($id)
    {
        $types = Type::all();
        $expense = Expense::findOrFail($id);
        return view('pages.expenses.edit', compact('expense', 'types'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'expense_title' => 'required',
            'expense_amount' => 'required',
            'expense_date'=> 'required'
        ]);

        $expense = Expense::findOrFail($request->expense_id);
        $expense->expense_title = $request->expense_title;
        $expense->expense_amount = $request->expense_amount;
        $expense->expense_date = $request->expense_date;
        $expense->type_id = $request->expense_type_id;
        $expense->update();

        return redirect('/expense')->with('message', __('messages.expense.success_update'));
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('message', __('messages.expense.success_delete'));
    }
}
