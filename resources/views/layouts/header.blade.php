<div class="header-wrapper">
    <div class="container">
        <div class="flex-row justify-space-between align-center">
            <div class="header-section-title">
                <h1>@yield('title')</h1>
            </div>
            <div class="user-wrapper">
                <button id="userDropdownBtn"><i class="fa-solid fa-circle-user"></i>Super Admin</button>
                <div id="userDropdown" class="dropdown-content">
                    <a href="{{route('logout')}}">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>