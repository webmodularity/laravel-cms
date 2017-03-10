@if(session()->has('update_success'))
    <div class="callout callout-success">
        <h4>You have successfully updated this record!</h4>

        <p>{{ session('update_success') }}</p>
    </div>
@endif()