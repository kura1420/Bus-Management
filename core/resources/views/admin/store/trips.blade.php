@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.store.ticket-search')}}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="pickup" id="pickup" class="form-control">
                                    <option value=""></option>
                                    @foreach ($stoppages as $stoppage)
                                    <option value="{{ $stoppage->id }}" @if(request()->pickup == $stoppage->id) selected @endif>{{ __($stoppage->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="destination" id="destination" class="form-control">
                                    <option value=""></option>
                                    @foreach ($stoppages as $stoppage)
                                    <option value="{{ $stoppage->id }}" @if(request()->destination == $stoppage->id) selected @endif>{{ __($stoppage->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_of_journey" id="date_of_journey" class="form-control" value="{{ request()->date_of_journey }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary" id="btnFilter">Find Ticket</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    @forelse ($trips as $trip)
                    @php
                    $start = Carbon\Carbon::parse($trip->schedule->start_from);
                    $end = Carbon\Carbon::parse($trip->schedule->end_at);
                    $diff = $start->diff($end);
                    $ticket = App\Models\TicketPrice::where('fleet_type_id', $trip->fleetType->id)->where('vehicle_route_id', $trip->route->id)->first();
                    @endphp
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <h4 style="font-weight: bold;">{{ __($trip->title) }}</h4>
                                    <small>@lang('Seat Layout - ') {{ __($trip->fleetType->seat_layout) }}</small> <br>
                                    <small>{{ __($trip->fleetType->name) }}</small>
                                </div>
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>{{ showDateTime($trip->schedule->start_from, 'h:i A') }}</strong>
                                            <span>{{ __($trip->startFrom->name) }}</span>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <i class="fa fa-arrow-right"></i> <br>

                                            {{ $diff->format('%H:%I min') }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>{{ showDateTime($trip->schedule->end_at, 'h:i A') }}</strong>
                                            <span>{{ __($trip->endTo->name) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <h4>{{ __($general->cur_sym) }}{{ showAmount($ticket->price) }}</h4>
                                    
                                    @if($trip->day_off)
                                    <div class="seats-left mt-2 mb-3 fs--14px">
                                        @lang('Off Days'): <div class="d-inline-flex flex-wrap" style="gap:5px">
                                            @foreach ($trip->day_off as $item)
                                            <span class="badge badge--primary">{{ __(showDayOff($item)) }}</span>
                                            @endforeach
                                        </div>
                                        @else
                                        @lang('Every day available')
                                        @endif
                                    </div>
                                    
                                    <a class="btn btn-primary" href="{{ route('admin.store.show-seat', [$trip->id, slug($trip->title)]) }}">@lang('Select Seat')</a>
                                </div>
                            </div>
                        </div>
                        @if ($trip->fleetType->facilities)
                        <div class="card-footer">
                            <strong>@lang('Facilities - ')</strong>
                            @foreach ($trip->fleetType->facilities as $item)
                                <span class="badge badge-dark">{{ __($item) }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="ticket-item">
                        <h5>{{ __($emptyMessage) }}</h5>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    $('#pickup').select2({
        placeholder: 'Select Pickup Point',
        allowClear: true,
    });

    $('#destination').select2({
        placeholder: 'Select Dropping Point',
        allowClear: true,
    });
});
</script>
@endpush