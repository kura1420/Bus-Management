@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ticket.book', $trip->id) }}" method="POST" id="bookingForm">
                    <div class="form-group">
                        <label for="date_of_journey">Journey Date</label>
                        <input type="date" class="form-control" name="date_of_journey" id="date_of_journey" />
                    </div>
                    <div class="form-group">
                        <label for="pickup_point">@lang('Pickup Point')</label>
                        <select name="pickup_point" id="pickup_point" class="form-control">
                            <option value=""></option>
                            @foreach($stoppages as $item)
                            <option value="{{ $item->id }}" @if (Session::get('pickup')==$item->id)
                                selected
                                @endif>
                                {{ __($item->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dropping_point">@lang('Dropping Point')</label>
                        <select name="dropping_point" id="dropping_point" class="form-control">
                            <option value=""></option>
                            @foreach($stoppages as $item)
                            <option value="{{ $item->id }}" @if (Session::get('destination')==$item->id)
                                selected
                                @endif>
                                {{ __($item->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dropping_point">@lang('Select Gender')</label>
                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="form-group custom--radio">
                                <input id="male" type="radio" name="gender" value="1">
                                <label class="form-label" for="male">@lang('Male')</label>
                            </div>
                            <div class="form-group custom--radio">
                                <input id="female" type="radio" name="gender" value="2">
                                <label class="form-label" for="female">@lang('Female')</label>
                            </div>
                            <div class="form-group custom--radio">
                                <input id="other" type="radio" name="gender" value="3">
                                <label class="form-label" for="other">@lang('Other')</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-none">
                        <label for="selected_seat">@lang('Selected Seats')</label>
                        <div class="card bg-primary" style="border: 1px solid #CFCFCF;">
                            <div class="card-header">
                                @lang('Seat Details')
                            </div>
                            <ul class="list-group list-group-flush">
                                <!-- <li class="list-group-item"></li> -->
                            </ul>
                        </div>
                    </div>
                    <input type="text" name="seats" hidden>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">@lang('Continue')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <h6 class="title">@lang('Click on Seat to select or deselect')</h6>
        @if ($trip->day_off)
        <span style="width: 40%;">
            @lang('Off Days') :
            @foreach ($trip->day_off as $item)
            <span class="badge badge--success">
                {{ __(showDayOff($item)) }}
                @if (!$loop->last)
                ,
                @endif
            </span>
            @endforeach

            @foreach ($trip->fleetType->deck_seats as $seat)
                <div class="seat-plan-inner">
                    <div class="single">

                        @php
                        echo $busLayout->getDeckHeader($loop->index);
                        @endphp

                        @php
                        $totalRow = $busLayout->getTotalRow($seat);
                        $lastRowSeat = $busLayout->getLastRowSit($seat);
                        $chr = 'A';
                        $deckIndex = $loop->index + 1;
                        $seatlayout = $busLayout->sitLayouts();
                        $rowItem = $seatlayout->left + $seatlayout->right;
                        @endphp
                        @for($i = 1; $i <= $totalRow; $i++) @php if($lastRowSeat==1 && $i==$totalRow -1) break; $seatNumber=$chr; $chr++; $seats=$busLayout->getSeats($deckIndex,$seatNumber);
                            @endphp
                            <div class="seat-wrapper">
                                @php echo $seats->left; @endphp
                                @php echo $seats->right; @endphp
                            </div>
                            @endfor
                            @if($lastRowSeat == 1)
                            @php $seatNumber++ @endphp
                            <div class="seat-wrapper justify-content-center">
                                @for ($lsr=1; $lsr <= $rowItem+1; $lsr++) @php echo $busLayout->generateSeats($lsr,$deckIndex,$seatNumber); @endphp
                                    @endfor
                            </div><!-- single-row end -->
                            @endif

                            @if($lastRowSeat > 1)
                            @php $seatNumber++ @endphp
                            <div class="seat-wrapper justify-content-center">
                                @for($l = 1; $l <= $lastRowSeat; $l++) @php echo $busLayout->generateSeats($l,$deckIndex,$seatNumber); @endphp
                                    @endfor
                            </div><!-- single-row end -->
                            @endif
                    </div>
                </div>
                @endforeach

                <div class="seat-for-reserved">
                    <div class="seat-condition available-seat">
                        <span class="seat"><span></span></span>
                        <p>@lang('Available Seats')</p>
                    </div>
                    <div class="seat-condition selected-by-you">
                        <span class="seat"><span></span></span>
                        <p>@lang('Selected by You')</p>
                    </div>
                    <div class="seat-condition selected-by-gents">
                        <div class="seat"><span></span></div>
                        <p>@lang('Booked by Gents')</p>
                    </div>
                    <div class="seat-condition selected-by-ladies">
                        <div class="seat"><span></span></div>
                        <p>@lang('Booked by Ladies')</p>
                    </div>
                    <div class="seat-condition selected-by-others">
                        <div class="seat"><span></span></div>
                        <p>@lang('Booked by Others')</p>
                    </div>
                </div>
        </span>
        @endif
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{asset('assets/admin/css/buslayout.css')}}">
@endpush

@push('script')
<script>
$(document).ready(function () {
    $('#pickup_point').select2({
        placeholder: 'Select Pickup Point',
        allowClear: true,
    });

    $('#dropping_point').select2({
        placeholder: 'Select Dropping Point',
        allowClear: true,
    });
});
</script>
@endpush