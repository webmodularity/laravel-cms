<div style="white-space: nowrap">
    <a href="{{ route(preg_replace("/\.".Route::current()->getActionMethod()."$/", '.show', Route::current()->getName()), ['id' => $id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-search-plus"></i></a>
</div>