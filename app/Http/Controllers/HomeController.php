<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
Use Auth;
use Illuminate\support\Carbon;
Use App\Models\Income;
Use App\Models\Expense;

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
        $data['incomes'] = Income::where('user_id', Auth::id())
            ->whereYear('income_date', $year)
            ->whereMonth('income_date', $month)
            ->sum('income_amount');

        $data['expenses'] = Expense::where('user_id', Auth::id())
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->sum('expense_amount');

        $data['balance'] = number_format($data['incomes'] - $data['expenses'], 2, '.', '');

        // Group expenses by type and fetch type names
        $expenses = Expense::where('user_id', Auth::id())
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->get();

        $expenseByType = $expenses->groupBy('type_id')->map(function ($group) {
            return $group->sum('expense_amount');
        });

        $typeIds = $expenseByType->keys();
        $typeNames = Type::whereIn('id', $typeIds)->pluck('name', 'id');

        $data['typeNames'] = $typeNames;
        $data['expenseByType'] = $expenseByType;
        $data['selectedYear'] = $year;
        $data['selectedMonth'] = $month;

        $incomes = Income::where('user_id', Auth::id())->get();

        $expenses = Expense::where('user_id', Auth::id())->get();

        $data['totalIncomes'] = $incomes->sum('income_amount');

        $data['totalExpenses'] = $expenses->sum('expense_amount');

        $data['totalBalance'] = $data['totalIncomes'] - $data['totalExpenses'];

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
