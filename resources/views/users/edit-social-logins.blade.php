@section('userSocialLoginData')
    @foreach($user->socialProviders as $socialProvider)
        [
        "{{ $socialProvider->id }}",
        "{{ $socialProvider->getName() }}",
        "{{ $socialProvider->pivot->avatar_url }}",
        "{{ $socialProvider->pivot->uid }}",
        "{{ $socialProvider->pivot->email }}"
        ]
    @endforeach
@endsection

@section('userSocialLoginColumns')
    { visible: false },
    { title: "Social" },
    { title: "Avatar", orderable: false, searchable: false },
    { title: "User ID" },
    { title: "Email" },
    { title: "Delete", orderable: false, searchable: false },
@endsection

@section('userSocialLoginColumnDefs')
    {
    render: function (data, type, row) {
    if (data) {
    return '<img src="' + data + '" width="40" height="40" title="' + data + '" />';
    } else {
    return '<em>None</em>';
    }
    },
    targets: 2
    },
    {
    render: function (data, type, row) {
    return '<button type="button" class="btn btn-xs btn-danger delete-confirm-button" data-id="'+row[0]+'" data-token="{{ csrf_token() }}" data-record-ident="'+row[1]+'"><i class="fa fa-trash-o"></i>&nbsp;Delete</button>';
    },
    width: "40px",
    targets: 5
    }
@endsection

@section('userSocialLoginRowAddData')
    var socialProviderSelected = form.find("#social_provider_id option:checked");
    var rowAddData = [
        socialProviderSelected.val(),
        socialProviderSelected.text(),
        form.find("input[name=avatar_url]").val(),
        form.find("input[name=uid]").val(),
        form.find("input[name=email]").val()
    ];
@endsection

@section('userSocialLoginForm')
    <div class="form-group">
        <label class="control-label">User</label>
        <p>{{ $user->person->email }}</p>
    </div>
    <div class="form-group">
        <label class="control-label" for="social_provider_id">Social Provider</label>
        <select class="form-control" style="width: 100%;" name="social_provider_id" id="social_provider_id" required>
            @foreach($socialProviders as $socialProvider)
                <option value="{{ $socialProvider['id'] }}"{{ old('social_provider_id') == $socialProvider['id'] ? ' selected' : '' }}>{{ $socialProvider->getName() }}</option>
            @endforeach()
        </select>
    </div>
    <div class="form-group">
        <label class="control-label" for="uid">Social User ID</label>
        <input type="text" name="uid" class="form-control" placeholder="Social User ID" required />
    </div>
    <div class="form-group">
        <label class="control-label" for="email">Social User Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email Address" required />
    </div>
    <div class="form-group">
        <label class="control-label" for="avatar_url">Avatar URL (Optional)</label>
        <input type="url" name="avatar_url" class="form-control" placeholder="Avatar URL" />
    </div>
@endsection

@include('wmcms::crud.related-box-ajax', [
    'relatedAjaxTableId' => 'userSocialLogin',
    'boxTitle' => "Social Logins: <em>".$user->person->email."</em>",
    'addText' => "Add Social Login",
    'deleteUrl' => "location.pathname.replace(/\/?$/, '') + '/social-logins/' +id",
    'postUrl' => route('users.social.attach', ['user_id' => $user->id])
])