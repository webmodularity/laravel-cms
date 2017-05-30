@section('userLogColumns')
    { title: "Time" },
    { title: "IP" },
    { title: "Action" },
    { title: "View", orderable: false, searchable: false }
@endsection

@section('userLogData')
    @foreach($userLogs as $userLog)
        [
        "{{ $userLog->created_at->format('m/d/Y h:i:sa') }}",
        "{{ $userLog->logRequest->ipAddress->ip }}",
        "{{ $userLog->userAction->slug }}",
        "{{ $userLog->id }}"
        ],
    @endforeach
@endsection

@section('userLogColumnDefs')
    {
    render: function (data, type, row) {
    return '<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#userLogModal" data-id="'+data+'"><i class="fa fa-search-plus"></i></button>';
    },
    width: "20px",
    "className": "text-center",
    targets: 3
    }
@endsection