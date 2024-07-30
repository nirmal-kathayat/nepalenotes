<!-- Important sidebarcode -->
<div class="sidebar-wrapper">
    <div class="sidebar">
        <div class="logo-wrapper logo-sidebar">
            <img src="{{ asset('images/logo.png') }}" alt="logo" />
        </div>

        <div class="nav-items">
            <!-- <div class="dashboard-close-button">
                <button><i class="fa-solid fa-bars"></i></button>
            </div> -->
            <a href="{{route('admin.dashboard')}}" class="nav-item {{ request()->is('admin/dashboard') ? ' active' : '' }}">
                <div class="nav-item__icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.33333 2.5H2.5V8.33333H8.33333V2.5Z" stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17.5001 2.5H11.6667V8.33333H17.5001V2.5Z" stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M17.5001 11.6667H11.6667V17.5001H17.5001V11.6667Z" stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8.33333 11.6667H2.5V17.5001H8.33333V11.6667Z" stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="nav-item__label">
                    Dashboard
                </div>
            </a>

            <a class="nav-item {{request()->is('admin/grade') ? ' active' : ''  }}" href="{{route('admin.grade')}}">
                <div class="nav-item__icon">
                    <img src="{{asset('images/class.png')}}" style="height: 20px; width:20px;" alt="">
                </div>
                <div class="nav-item__label">Grade</div>
            </a>

            <a class="nav-item {{request()->is('admin/faculty') ? ' active' : ''  }}" href="{{route('admin.faculty')}}">
                <div class="nav-item__icon">
                    <img src="{{asset('images/faculty.png')}}" style="height: 20px; width:20px;" alt="">
                </div>
                <div class="nav-item__label">Faculty</div>
            </a>

            <a class="nav-item {{request()->is('admin/subject') ? ' active' : ''  }}" href="{{route('admin.subject')}}">
                <div class="nav-item__icon">
                    <img src="{{asset('images/subjects.png')}}" style="height: 20px; width:20px;" alt="">
                </div>
                <div class="nav-item__label">Subject</div>
            </a>

            <a class="nav-item {{request()->is('admin/course') ? ' active' : ''  }}" href="{{route('admin.course')}}">
                <div class="nav-item__icon">
                    <img src="{{asset('images/homework.png')}}" style="height: 20px; width:20px;" alt="">
                </div>
                <div class="nav-item__label">Course</div>
            </a>

            <a class="nav-item {{request()->is('admin/user') ? ' active' : ''  }}" href="{{route('admin.user')}}">
                <div class="nav-item__icon">
                    <img src="{{asset('images/user.png')}}" style="width: 20px; height:20px;" alt="">
                </div>
                <div class="nav-item__label">User</div>
            </a>


            <a class="nav-item {{ request()->is('admin/permission') ? ' active' : ''  }}" href="{{route('admin.permission')}}">
                <div class="nav-item__icon">

                    <img src="{{asset('images/permission.png')}}" style="width: 20px; height:20px;" alt="">

                </div>
                <div class="nav-item__label">Permission</div>
            </a>


            <a class="nav-item {{request()->is('admin/role') ? ' active' : ''  }}" href="{{route('admin.role')}}">
                <div class="nav-item__icon">
                    <img src="{{asset('images/role.png')}}" style="width: 20px; height:20px;" alt="">
                </div>
                <div class="nav-item__label">Role</div>
            </a>
        </div>
    </div>
</div>