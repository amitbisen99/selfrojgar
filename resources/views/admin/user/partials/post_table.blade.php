<div class="table-responsive mt-3">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name/Title</th>
                <th>User Name</th>
                <th>City</th>
                <th>State</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($posts) && count($posts) > 0)
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $post->{$title ?? 'name'} ?? '-' }}</td>
                        <td>{{ optional(\App\Models\User::find($post->user_id))->name ?? '-' }}</td>
                        <td>{{ optional(\App\Models\City::find($post->city_id))->name ?? '-' }}</td>
                        <td>{{ optional(\App\Models\State::find($post->state_id))->name ?? '-' }}</td>
                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($post->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route($route, $post->id) }}" class="btn btn-primary btn-sm" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button class="btn btn-danger btn-sm remove-crud" data-action="{{ route($destroy_route, $post->id) }}" data-id="{{ $post->id }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">No records found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
