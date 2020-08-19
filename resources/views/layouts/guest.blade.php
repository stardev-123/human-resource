@include('layouts.head')
    
    <body class="tooltips full-content">
    <div class="container">
        <img id="loading-img" src="/images/loading.gif" />
        @yield('content')

        @include('global.credit')
    </div>

    <div class="overlay"></div>
    <div class="modal fade-scale" id="myModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

@include('layouts.foot')