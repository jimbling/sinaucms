@can('atur_ringkas')
    <li class="nav-item">
        <a href="{{ route('ringkas.index') }}" class="nav-link {{ request()->is('admin/ringkas*') ? 'active' : '' }}">
            <i class="fas fa-project-diagram"></i>
            <p>{{ __('Ringkas') }}</p>
        </a>
    </li>
@endcan
