@include('layouts.head')
    <body class="tooltips">
        <div class="container">
            <div class="logo-brand header sidebar rows">
                <div class="logo">
                    <h1><a href="/">{{config('config.application_name')}}</a></h1>
                </div>
            </div>

            @if(Auth::check())
                @include('layouts.sidebar')
            @endif

            <div class="{{(Auth::check()) ? 'right' : ''}} content-page">

                @if(Auth::check())
                    @include('layouts.header')
                @endif

                <div class="body content rows scroll-y">

                    @yield('breadcrumb')

                    @include('global.message')
                    
                    @yield('content')

                    @include('layouts.footer')

                </div>

            </div>

            @if($right_sidebar)
                <div class="col-xs-7 col-sm-3 col-md-3 sidebar sidebar-right sidebar-animate">
                    @yield('right_sidebar')
                </div>
            @endif
            
        <img id="loading-img" src="/images/loading.gif" />

        <div class="overlay"></div>
        <div class="modal fade-scale" id="myModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                </div>
            </div>
        </div>

    </div>

    @include('layouts.foot')