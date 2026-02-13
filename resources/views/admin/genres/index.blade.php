@extends($adminTheme)

@section("title")
    Genres
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Genres</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                        <a href="{{ route('genres.create') }}" class="btn btn-success head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
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
                ajax: "{{ route('genres.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data:'name', name: 'name'},
                    {data:'status', name: 'status'},
                    {data:'action', name: 'action'},
                ]
            });
        });
        $.fn.dataTable.ext.errMode = 'throw';
      });
    </script>
@endsection 