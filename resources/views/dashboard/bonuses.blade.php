@extends('layouts.dashboard')
@section('dashboard_content')
    <div>
        <p>
            <span>Total Bonus: <b>{{to_currency($user->totalBonus())}}</b></span> |
            <span>Paid Bonus: <b>{{to_currency($user->paidBonus())}}</b></span> |
            <span>Unpaid Bonus: <b>{{to_currency($user->unpaidBonus())}}</b></span>
        </p>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Reference</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Credited On</th>
                <th>Paid</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bonuses as $bonus)
                <tr class="">
                    <td>{{$bonus->reference}}</td>
                    <td>{{to_currency($bonus->amount)}}</td>
                    <td>{{$bonus->name}}</td>
                    <td>{{$bonus->created_at->format('M j, Y - g:i a')}}</td>
                    <td>{{$bonus->used?'Yes':'No'}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">You haven't earned any bonus yet</td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5">{{$bonuses->links()}}</td>
            </tr>
            </tfoot>
        </table>
    </div>


@endsection
