<div class="table-responsive mt-3">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name/Title</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($posts) && count($posts) > 0)
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->{$title ?? 'name'} ?? '-' }}</td>
                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($post->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route($route, $post->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No records found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
