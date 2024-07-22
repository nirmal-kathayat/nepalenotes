<div class="topbar-wrapper">
    <div class="notification">
        <!-- Your notification content -->
    </div>

    <div class="user-info-dropdown">
        <div class="user-info">
            <div class="user-photo">
                <img src="{{asset('images/user-photo.png')}}" alt="user-photo" />
            </div>
            <p class="user-name">
                <i class="icon-key"></i>
                <span>{{\Auth::guard('admin')->user()->name}}</span>
            </p>
            <div class="dropdown-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6L8 10L12 6" stroke="#434343" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>
        <div class="top-dropdown-menu">
            <ul>
                <li><a href="#" class="change-password-btn">Change Password</a></li>
                <li><a href="{{route('logout')}}">Logout</a></li>
            </ul>
        </div>
    </div>


</div>