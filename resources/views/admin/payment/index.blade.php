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
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>User</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section("script")
    <script>
      $(document).ready(function(){
        $(function () {
          var table = $('.user-list-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('payment.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data:'user_id', name: 'user_id'},
                    {data:'payment_id', name: 'payment_id'},
                    {data:'amount', name: 'amount'},
                    {data:'created_at', name: 'created_at'},
                    {data:'action', name: 'action'},
                ]
            });
        });
        $.fn.dataTable.ext.errMode = 'throw';
      });
    </script>
@endsection 