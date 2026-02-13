@extends($adminTheme)

@section("title")
    Jobs
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Jobs</h4>
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
                            <th>User</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Status</th>
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
                ajax: "{{ route('job.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data:'user_id', name: 'user_id'},
                    {data:'company_name', name: 'company_name'},
                    {data:'role', name: 'role'},
                    {data:'created_at', name: 'created_at'},
                    {data:'status', name: 'status'},
                    {data:'action', name: 'action'},
                ]
            });
        });
        $.fn.dataTable.ext.errMode = 'throw';
      });
    </script>
@endsection 