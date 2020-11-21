@extends('layouts.dashboard')
@section('dashboard_content')

    <div class="card">
        <div class="card-body">
            <div class="row margin-btm-1em">
                <div class="col-sm-12">
                    <span class="pull-left"><strong>Total Investment:</strong> {{to_currency($deposits)}}</span>
                    @if($plan = Auth::user()->suggestReinvestPlan())
                        <a class="btn btn-info btn-rounded btn-icon pull-right margin-l-5"
                           href="{{route('dashboard.invest')}}?reinvest=1&plan={{$plan->id}}&amount={{Auth::user()->withdrawableBalance()}}">
                            Reinvest
                            <i class="fa fa-recycle"></i>
                        </a>
                    @endif
                    <a class="btn btn-primary btn-rounded btn-icon pull-right"
                       href="{{route('dashboard.invest')}}">
                        Deposit
                        <i class="fa fa-check"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Reference</th>
                        <!--<th>Created</th>-->
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Verified</th>
                        <th>ROI</th>
                        <th>Due Date</th>
                        <th>Ends</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($investments as $h)
                        <tr class="">
                            <td>{{$h->reference}}</td>
                        <!--<td>{{$h->created_at->format('M j, Y - g:i a')}}</td>-->
                            <td>{{to_currency($h->amount)}}</td>
                            <td>
                                @if($h->paid_at)
                                    {{$h->paid_at->format('M j, Y - g:i a')}}<br/>
                                <!--                    <a href="{{route('dashboard.receipts').'?reference='.$h->reference}}" target="_blank"
                                           onclick="fetchReceipts(this, event)">
                                            view files
                                        </a>-->
                                @else
                                    No
                                @endif
                            </td>
                            <td>
                                {{$h->verified_at?$h->verified_at->format('M j, Y - g:i a'):'Pending'}}
                            </td>
                            <td>{{to_currency($h->roi)}}</td>
                            <td>{{$h->due_at->format('M j, Y - g:i a')}}</td>
                            <td>{{$h->expire_at->format('M j, Y - g:i a')}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">You haven&apos;t deposited yet</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="8">{{$investments->links()}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('head')
    @parent

@endsection

@section('scripts')
    @parent

@endsection