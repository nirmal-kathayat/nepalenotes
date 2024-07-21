<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepali Enotes-@yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    @stack('style')
</head>

<body>
    <main class="main-section">
        @include('layouts.sidebar')
        <div class="main-content">
            @include('layouts.header')
            <div class="main-section-content">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</body>

</html>
<!-- profile -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('userDropdownBtn');
        const dropdownContent = document.getElementById('userDropdown');

        dropdownBtn.addEventListener('click', function() {
            dropdownContent.classList.toggle('show');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.matches('#userDropdownBtn')) {
                if (dropdownContent.classList.contains('show')) {
                    dropdownContent.classList.remove('show');
                }
            }
        });
    });
</script>