@extends($adminTheme)

@section("title")
    Users
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Users</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                        @can('user-write')
                            <a href="{{ route('user.create') }}" class="btn btn-success head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>State</th>
                            <th>City</th>
                            <th style="min-width: 140px;">Status</th>
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
                ajax: "{{ route('user.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data:'name', name: 'name'},
                    {data:'email', name: 'email'},
                    {data:'contact_number', name: 'contact_number'},
                    {data:'state_name', name: 'state_name'},
                    {data:'city_name', name: 'city_name'},
                    {data:'status', name: 'status'},
                    {data:'created_at', name: 'created_at'},
                    {data:'action', name: 'action'},
                ]
            });
        });
        $.fn.dataTable.ext.errMode = 'throw';
      });
    </script>
@endsection
