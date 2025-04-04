<ul class="sidebar navbar-nav">
    <li class="nav-item {{ Route::currentRouteName() == 'index' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('messages.dashboard') }}</span>
        </a>
    </li>
    <li class="nav-item {{ Route::currentRouteName() == 'incomes.index' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('incomes.index') }}">
        <i class="fas fa-fw fa-dollar-sign"></i>
        <span>{{ __('messages.income.index') }}</span></a>
    </li>
    <li class="nav-item {{ Route::currentRouteName() == 'expense.index' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('expense.index') }}">
        <i class="fas fa-fw fa-money-bill"></i>
        <span>{{ __('messages.expense.index') }}</span></a>
    </li>
    <li class="nav-item {{ Route::currentRouteName() == 'summary' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('summary') }}">
        <i class="fas fa-fw fa-table"></i>
        <span>{{ __('messages.all_summary') }}</span></a>
    </li>
    <li class="nav-item {{ Route::currentRouteName() == 'notes.index' ? 'active' : '' }}" title="This is not calculate in Income/Expense">
        <a class="nav-link" href="{{ route('notes.index') }}">
        <i class="fas fa-fw fa-sticky-note"></i>
        <span>{{ __('messages.note.index') }}</span></a>
    </li>
</ul>
