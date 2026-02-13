@extends($adminTheme)

@section("title")
    Payments
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Payments</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>User</th>
                        <td>{{ $payment->getUser->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Payment Id</th>
                        <td>{{ $payment->payment_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $payment->amount ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $payment->start_date ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $payment->end_date ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
@endsection