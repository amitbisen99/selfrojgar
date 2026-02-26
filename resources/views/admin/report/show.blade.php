@extends($adminTheme)

@section('title')
Report Details
@endsection

@section('wrapper')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 order-1 order-md-0">
        <!-- Report Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class=" d-flex align-items-center flex-column">
                        <div class="user-info text-center mt-2 border-bottom w-100">
                            <h4 class="mb-2">Report Info</h4>
                        </div>
                    </div>
                </div>

                <div class="info-container pt-2">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="fw-bolder me-25">User Name:</span>
                            <span>{{ $report->user ? $report->user->name : 'N/A' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-bolder me-25">Module Name:</span>
                            <span>{{ $report->module_name }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-bolder me-25">Post ID:</span>
                            <span>{{ $report->post_id }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-bolder me-25">Report Type:</span>
                            <span>{{ $report->report_type }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-bolder me-25">Report Date:</span>
                            <span>{{ $report->created_at->format('Y-m-d H:i:s') }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="fw-bolder me-25">Other Details:</span>
                            <span>{{ $report->other ?? 'None' }}</span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-center pt-2">
                        <form action="{{ route('report.destroy', $report->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this report?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger me-1">Delete Report</button>
                        </form>

                        <form action="{{ route('report.destroyPost', $report->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete the associated post? This will also delete this report.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning me-1">Delete Target Post</button>
                        </form>

                        @if(isset($postUrl) && $postUrl)
                            <a href="{{ $postUrl }}" class="btn btn-info me-1" target="_blank">Go to Post</a>
                        @endif

                        <a href="{{ route('report.index') }}" class="btn btn-secondary">Back to Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
