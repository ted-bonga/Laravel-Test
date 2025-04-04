@extends('layouts.master')

@section('content')

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="container-fluid">
        <form action="{{ route('index') }}" method="GET" class="mb-3">
            <div class="form-group row">
                <label for="year" class="col-sm-2 col-form-label">{{ __('messages.select_year') }}</label>
                <div class="col-sm-4">
                    <select name="year" id="year" class="form-control">
                        @foreach(range(date('Y'), date('Y') - 5) as $year)
                            <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <label for="month" class="col-sm-2 col-form-label">{{ __('messages.select_month') }}</label>
                <div class="col-sm-4">
                    <select name="month" id="month" class="form-control">
                        @foreach([
                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
                            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                        ] as $num => $name)
                            <option value="{{ $num }}" {{ request('month', date('m')) == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
        </form>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">{{ __('messages.dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('messages.overview') }}</li>
        </ol>

        <div class="row">
            <!-- Monthly Data Column -->
            <div class="col-xl-6 col-sm-12 mb-3">
                <ul class="list-group">
                    <li class="list-group-item bg-info text-center text-white">
                        <span>{{ __('messages.this_month_cost') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('messages.total_income') }}
                        <span class="badge badge-primary badge-pill incomeValue">{{ $monthly_incomes }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('messages.total_expense') }}
                        <span class="badge badge-danger badge-pill expenseValue">{{ $monthly_expenses }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('messages.balance') }}
                        <span class="badge badge-primary badge-pill">{{ $monthly_balance }}</span>
                    </li>
                </ul>
            </div>

            <!-- Total Data Column -->
            <div class="col-xl-6 col-sm-12 mb-3">
                <ul class="list-group">
                    <li class="list-group-item bg-info text-center text-white">
                        <span>{{ __('messages.total_cost') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('messages.total_income') }}
                        <span class="badge badge-primary badge-pill incomeTotalValue">{{ $total_incomes }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('messages.total_expense') }}
                        <span class="badge badge-danger badge-pill expenseTotalValue">{{ $total_expenses }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('messages.balance') }}
                        <span class="badge badge-primary badge-pill">{{ $total_balance }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Icon Cards-->
        <div class="row">

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-primary o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-fw fa-table"></i>
                        </div>
                        <div class="mr-5">{{ __('messages.total_summary') }}</div>
                    </div>
                    <a class="nav-link text-white text-center card-footer clearfix small z-1" href="{{ route('notes.index') }}"  class="card-footer text-white clearfix small z-1" href="#">
                        <span class="float-left">{{__('messages.view_all_summary') }}</span>
                        <span class="float-right">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-success o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-fw fa-dollar-sign"></i>
                        </div>
                        <div class="mr-5">{{ __('messages.income.count', ['count' => App\Models\Income::where('user_id', Auth::user()->id)->count()]) }}</div>
                    </div>
                    <a class="nav-link text-white text-center card-footer clearfix small z-1" href="{{ route('incomes.index') }}"  class="card-footer text-white clearfix small z-1" href="#">
                        <span class="float-left">{{__('messages.view_all') }}</span>
                        <span class="float-right">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-danger o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-fw fa-money-bill"></i>
                        </div>
                        <div class="mr-5">{{ __('messages.expense.count', ['count' => App\Models\Expense::where('user_id', Auth::user()->id)->count()]) }}</div>
                    </div>
                    <a class="nav-link text-white text-center card-footer clearfix small z-1" href="{{ route('expense.index') }}" href="#">
                        <span class="float-left">{{__('messages.view_all') }}</span>
                        <span class="float-right">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card text-white bg-info o-hidden h-100">
                    <div class="card-body">
                        <div class="card-body-icon">
                            <i class="fas fa-fw fa-sticky-note"></i>
                        </div>
                        <div>{{__('messages.note.count', ['count' => App\Models\Note::where('user_id', Auth::user()->id)->count()])}}</div>
                    </div>
                    <a class="nav-link text-white text-center card-footer clearfix small z-1" href="{{ route('notes.index') }}"  class="card-footer text-white clearfix small z-1" href="#">
                        <span class="float-left">{{__('messages.view_all') }}</span>
                        <span class="float-right">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="row">
            <!-- Income vs Expense Chart -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        {{ __('messages.income_vs_expense') }} <small class="badge badge-info">({{__('messages.this_month_data')}})</small>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeExpenseChart" style="width: 100%; height: 30vh;"></canvas>
                    </div>
                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                </div>
            </div>

            <!-- Expense by Category Chart -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        {{ __('messages.expense_by_category') }}
                        <small class="badge badge-info">({{__('messages.this_month_data')}})</small>
                    </div>
                    <div class="card-body">
                        @php
                            // Generate random colors for each category
                            $randomColors = [];
                            for ($i = 0; $i < count($monthly_type_names); $i++) {
                                $randomColors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Random hex color
                            }
                        @endphp
                        @foreach($monthly_type_names as $key => $monthly_type_name)
                            <p>
                                <!-- Color Square -->
                                <span style="display: inline-block; width: 15px; height: 15px; background-color: {{ $randomColors[$loop->index] }}; margin-right: 10px;"></span>
                                {{ $monthly_type_name }}: {{ $monthly_expense_by_type[$key] }} €
                            </p>
                        @endforeach
                        <canvas id="categoryExpenseChart" style="width: 100%; height: 30vh;"></canvas>
                    </div>
                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                </div>
            </div>
        </div>

        <!-- Expense Categories Chart All data -->
        <div class="row">
            <!-- Income vs Expense Chart -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        {{ __('messages.income_vs_expense') }} <small class="badge badge-info">({{__('messages.all_data')}})</small>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeExpenseTotalChart" style="width: 100%; height: 30vh;"></canvas>
                    </div>
                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                </div>
            </div>
            <!-- Expense by Category Chart -->
{{--            <div class="col-lg-6 col-md-12 mb-3">--}}
{{--                <div class="card mb-3">--}}
{{--                    <div class="card-header">--}}
{{--                        <i class="fas fa-chart-pie"></i>--}}
{{--                        {{ __('messages.expense_by_category') }}--}}
{{--                        <small class="badge badge-info">({{__('messages.all_data')}})</small>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        @php--}}
{{--                            // Generate random colors for each category--}}
{{--                            $randomColors = [];--}}
{{--                            for ($i = 0; $i < count($typeNames); $i++) {--}}
{{--                                $randomColors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Random hex color--}}
{{--                            }--}}
{{--                        @endphp--}}
{{--                        @foreach($typeNames as $key => $typeName)--}}
{{--                            <p>--}}
{{--                                <!-- Color Square -->--}}
{{--                                <span style="display: inline-block; width: 15px; height: 15px; background-color: {{ $randomColors[$loop->index] }}; margin-right: 10px;"></span>--}}
{{--                                {{ $typeName }}: {{ $expenseByType[$key] }}--}}
{{--                            </p>--}}
{{--                        @endforeach--}}
{{--                        <canvas id="categoryExpenseChart" style="width: 100%; height: 30vh;"></canvas>--}}
{{--                    </div>--}}
{{--                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>


        @endsection

        @push('js')
            <script src="{{ asset('dashboard/vendor/chart/chart.min.js') }}"></script>
            <script>
                //Income expense Pie Chart
                var ctx = document.getElementById("incomeExpenseChart");
                var income = $(".incomeValue").html();
                var expense = $(".expenseValue").html();
                var income_vs_expense_chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["Income", "Expense"],
                        datasets: [{
                            data: [income, expense],
                            backgroundColor: ['#007bff', '#dc3545'],
                        }],
                    },
                });
                // Function to generate a random color
                function getRandomColor() {
                    var letters = '0123456789ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                }

                var categoryNames = @json($monthly_type_names); // Categories (names)
                var categoryAmounts = @json($monthly_expense_by_type); // Amounts by category
                var randomColors = @json($randomColors); // Random colors for each category

                // Prepare the data for the chart
                var categories = [];
                var amounts = [];

                for (var key in categoryAmounts) {
                    if (categoryAmounts.hasOwnProperty(key)) {
                        categories.push(categoryNames[key]);  // Add the category name
                        amounts.push(categoryAmounts[key]);  // Add the total expense for that category
                    }
                }

                // Create the Expense Categories Pie Chart
                var categoryCtx = document.getElementById("categoryExpenseChart");
                var category_expense_chart = new Chart(categoryCtx, {
                    type: 'pie',
                    data: {
                        labels: categories,
                        datasets: [{
                            data: amounts,
                            backgroundColor: randomColors, // Use the random colors array here
                        }],
                    },
                });

                //Income expense Total Pie Chart
                var ctx = document.getElementById("incomeExpenseTotalChart");
                var income = $(".incomeTotalValue").html();
                var expense = $(".expenseTotalValue").html();
                var income_vs_expense_chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["Income", "Expense"],
                        datasets: [{
                            data: [income, expense],
                            backgroundColor: ['#007bff', '#dc3545'],
                        }],
                    },
                });
            </script>
    @endpush
