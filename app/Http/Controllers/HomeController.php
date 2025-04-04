<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\support\Carbon;
Use App\Models\Income;
Use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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

    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // Fetch data based on the year and month
        $data['monthly_incomes'] = Income::where('user_id', Auth::id())
            ->whereYear('income_date', $year)
            ->whereMonth('income_date', $month)
            ->sum('income_amount');

        $data['monthly_expenses'] = Expense::where('user_id', Auth::id())
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->sum('expense_amount');

        $data['monthly_balance'] = number_format($data['monthly_incomes'] - $data['monthly_expenses'], 2, '.', '');

        // Group expenses by type and fetch type names
        $monthly_expenses = Expense::where('user_id', Auth::id())
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->get();

        $monthly_expense_by_type = $monthly_expenses->groupBy('type_id')->map(function ($group) {
            return number_format($group->sum('expense_amount'), '2','.','');
        });

        $monthly_type_ids = $monthly_expense_by_type->keys();

        $monthly_type_names = Type::whereIn('id', $monthly_type_ids)->pluck('name', 'id');

        $data['monthly_type_names'] = $monthly_type_names;
        $data['monthly_expense_by_type'] = $monthly_expense_by_type;
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;

        $total_incomes = Income::where('user_id', Auth::id())->get();

        $total_expenses = Expense::where('user_id', Auth::id())->get();


        // Questo serve per mostrare il rettangolo per le spese totali
        $data['total_incomes'] = number_format($total_incomes->sum('income_amount'), '2','.' ,'');

        $data['total_expenses'] = number_format( $total_expenses->sum('expense_amount') , '2', '.', '');

        $data['total_balance'] =  number_format($data['total_incomes'] - $data['total_expenses'],'2','.','');


        $data['all_incomes'] = Income::where('user_id', Auth::id())->get()->sum('income_amount');

        // Variabili usati per fare i chart pie
        $total_expense_by_type = $total_expenses->groupBy('type_id')->map(function ($group) {
            return number_format($group->sum('expense_amount'),'2','.','');
        });

        $total_type_ids = $total_expense_by_type->keys();

        $total_type_names = Type::whereIn('id', $total_type_ids)->pluck('name', 'id');

        $data['total_type_names'] = $total_type_names;
        $data['total_expense_by_type'] = $total_expense_by_type;

        return view('pages.dashboard', $data);
    }

    public function summary()
    {
        $userId = Auth::User()->id;

        // Fetch incomes and expenses as collections
        $incomes = Income::where('user_id', $userId)->orderby('income_date', 'asc')->get();
        $expenses = Expense::where('user_id', $userId)->orderBy('expense_date', 'desc')->get();

        // Add type to each entry
        $incomes->each(function ($item) {
            $item->type = 'income';
        });

        $expenses->each(function ($item) {
            $item->type = 'expense';
        });

        // Merge collections and sort them by date
        $results = $incomes->merge($expenses)->sortByDesc('transaction_date')->values();


        // Total income, expense, and balance calculations
        $totalIncome = Income::where('user_id', $userId)->sum('income_amount');
        $totalExpense = Expense::where('user_id', $userId)->sum('expense_amount');
        $balance = $totalIncome - $totalExpense;
        $balance= number_format($balance,2,'.','');

        // Prepare data for the view
        $data = [
            'results' => $results,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,
        ];

        return view('pages.summary', $data);
    }
}
