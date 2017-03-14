@if(session()->has('success'))
    <?php $successData = session('success') ?>
    <div class="callout callout-success">
        <h4>{{ $successData['title'] or 'Success' }}</h4>

        <p>{{ $successData['message'] or $successData }}</p>
    </div>
@endif()