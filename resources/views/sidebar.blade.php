@if (Module::isEnabled('Aminity') && Route::has('admin.listing.aminity.index'))
    <li class="{{ Route::is('admin.listing.aminity.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.listing.aminity.index') }}">
            <span>{{ __('Manage Aminity') }}</span>
        </a>
    </li>
@endif
