<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @if(isset($page_title))
                    <div class="col-md-6">
                        <h4 class="card-title"> {{ $page_title }} </h4>
                    </div>
                    @endif
                    @if(!empty($add))
                    <div class="col-md-6">
                        <a href="{{ isset($path) ? URL($path) : '#' }}">
                            <button type="button" class="btn btn-success float-right">{{__('default_label.add')}} <i class="nc-icon nc-simple-add"></i> </button>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(!empty($data) && count($data) > 0)
                @component('partials.' . $partials, ['data' => (isset($data) && !empty($data)) ? $data : '']) @endcomponent
                @else
                <center> {{ __('default_label.no_record_found') }} </center>
                @endif
            </div>
        </div>
    </div>
</div>
