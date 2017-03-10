<a href="{{ route('regions.edit', ['id' => $id])  }}" class="btn btn-primary">Edit</a>

<form action="{{ route('regions.destroy', ['id' => $id]) }}" method="post">
{!! csrf_field() !!}
{{ method_field('DELETE') }}
    <button type="submit" class="btn btn-danger pull-right"><i class="fa fa-trash-o"></i>&nbsp;Delete</button>
</form>