@can('atur_formulir')
    <li class="nav-item">
        <a href="{{ route('formulir.index') }}" class="nav-link {{ request()->is('admin/formulir*') ? 'active' : '' }}">
            <i class="fas fa-project-diagram"></i>
            <p>{{ __('Formulir') }}</p>
        </a>
    </li>
@endcan
