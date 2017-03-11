<div style="white-space: nowrap">
    <form action="{{ route('regions.destroy', ['id' => $id]) }}" method="post">
        {!! csrf_field() !!}
        {{ method_field('DELETE') }}
        <a href="{{ route('regions.edit', ['id' => $id])  }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>&nbsp;Edit</a>
        <button type="button" class="btn btn-xs btn-danger delete-confirm-button" data-record-ident="{{ $name }}"><i class="fa fa-trash-o"></i>&nbsp;Delete</button>
    </form>
</div>