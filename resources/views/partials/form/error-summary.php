@if (count($errors) > 0)
<div class="callout callout-danger">
    <h4>Validation Error! Record was NOT saved !</h4>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif