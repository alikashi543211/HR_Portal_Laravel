<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <span class="close-bar cursor-pointer d-none float-right"><i class="fas fa-times-circle"></i></span>
        <a href="{{url('admin/dashboard')}}" class="simple-text logo-mini">
            <div class="logo-image-small">
                @if(!empty(Auth::user()->picture))
                <img class="rounded-circle" src="{{asset('uploads/employee/'.Auth::user()->picture)}}" alt="picture">
                @else
                <img src="{{asset('assets/image/logo-small.png')}}">
                @endif
            </div>
            <!-- <p>CT</p> -->
        </a>
        <a href="{{url('admin/dashboard')}}" class="simple-text logo-normal">
            {{ Auth::user()->full_name }}
        </a>
    </div>

    <div class="sidebar-wrapper">
        <ul class="nav">

            @foreach(config('data.admin.sideMenu') as $key => $m)
                @if(checkPermission($m['permission'],READ))
                    @if(isset($m['show']) AND $m['show'] == 1)
                        <li class=" {{ strpos(Request::path(), (explode('/', $m['url'])[1])) !== false ? 'active':'' }} ">
                            <a href="{{url($m['url'])}}">
                                <i class="nc-icon {{ $m['icon'] }}"></i> <b><span>{{ ucfirst(str_replace('_', ' ', $m['title'])) }}</span></b>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>
</div>
