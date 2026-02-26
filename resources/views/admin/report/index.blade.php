@extends($adminTheme)

@section("title")
    Reports
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Reports</h4>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>User Name</th>
                            <th>Post Name</th>
                            <th>Post Type</th>
                            <th>Report Type</th>
                            <th>Report Date</th>
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
                ajax: "{{ route('report.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data:'user_name', name: 'user_name'},
                    {data:'post_name', name: 'post_name'},
                    {data:'module_name', name: 'module_name'},
                    {data:'report_type', name: 'report_type'},
                    {data:'created_at', name: 'created_at'},
                    {data:'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
        $.fn.dataTable.ext.errMode = 'throw';
      });
    </script>
@endsection
