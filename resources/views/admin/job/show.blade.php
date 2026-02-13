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
                            <a href="{{ route('job.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>User</th>
                        <td>{{ $job->getUser->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ $job->role ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Company</th>
                        <td>{{ $job->company_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Company Logo</th>
                        <td>{{ $job->company_logo ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Salary</th>
                        <td>{{ $job->salary ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Job type</th>
                        <td>{{ $job->type ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Work type</th>
                        <td>{{ $job->work_type ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Mobile No.</th>
                        <td>{{ $job->mobile ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $job->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $job->city_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $job->state_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $job->country_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Skills</th>
                        <td>{{ $job->skills ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>About</th>
                        <td>{{ $job->about ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Job description</th>
                        <td>{{ $job->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Salary Type</th>
                        <td>{{ $job->salary_type ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Employe Level</th>
                        <td>{{ $job->employe_level ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>{{ $job->latitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>{{ $job->longitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $job->created_at ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
@endsection