@if(session()->has('create_success'))
    <div class="callout callout-success">
        <h4>You have successfully created a new record!</h4>

        <p>{{ session('create_success') }}</p>
    </div>
@endif()