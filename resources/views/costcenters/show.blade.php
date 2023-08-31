@extends('layout/htmlpage', ['hasGrid' => true])
@section('title', $costcenter->workday_code)
@section('content')

    <div class="row">
        <div class="col-md-9">
            <div class="mb-5">
                @if(hasRole('budget:fiscal'))
                    <div class="float-right">
                        <a href="{{ action('CostcentersController@edit', $costcenter->id) }}">Edit Cost center</a>
                    </div>
                @endif

                @include('costcenters._header')

                <div class="flex-columns mb-2">
                    <div>
                        <div class="top-label-gray">Cost center Approver</div>
                        <div class="p-2 text-lg">
                            {!! eOrEmpty(eFirstLast($costcenter->fiscal_person_id), 'Unknown') !!}
                        </div>
                    </div>


                </div>

                <div class="mb-3">
                    <div class="top-label-gray">Cost center name</div>
                    @if($costcenter->name)
                        <div class="mt-3 px-2 font-weight-bold">{{ $costcenter->name }}</div>
                    @else
                        <div class="p-2 empty">
                            No cost center name.
                        </div>
                    @endif
                </div>

                
            </div>

            

        </div>

        
    </div>

@stop
