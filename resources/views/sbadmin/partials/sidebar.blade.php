<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">Stack Overflow <sup>kw</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @if(Auth::check())
    <div class="info mt-2">
      <a class="d-block">
          <center>Welcome , {{ Auth::user()->name }}</center>
      </a>
    </div>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
      <a class="nav-link" href="/">
        <span>Dashboard</span></a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/question">
        <span>New Question</span></a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/question/index/{{ Auth::user()->id }}">
        <span>My Questions</span></a>
    </li>

    @else
        <p class="text-white text-center mt-2">You're Not Sign In</p>
    @endif
</ul>