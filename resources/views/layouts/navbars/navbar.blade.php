@if(auth()->check() && !in_array(request()->route()->getName(), ['welcome']))
    @include('layouts.navbars.navs.auth')
@endif
    
@if(!auth()->check() || in_array(request()->route()->getName(), ['welcome']))
    @include('layouts.navbars.navs.guest')
@endif