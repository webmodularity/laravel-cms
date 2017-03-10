@if(session()->has('delete_success'))
    <div class="callout callout-danger">
        <h4>You have successfully deleted a record!</h4>

        <p>{{ session('delete_success') }}</p>
    </div>
@endif()