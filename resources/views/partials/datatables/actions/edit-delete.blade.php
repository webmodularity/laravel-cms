<form action="{{ route('regions.destroy', ['id' => $id]) }}" method="post">
{!! csrf_field() !!}
{{ method_field('DELETE') }}
    <a href="{{ route('regions.edit', ['id' => $id])  }}" class="btn btn-sm btn-primary">Edit</a>
    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i>&nbsp;Delete</button>
</form>