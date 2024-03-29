@foreach($columnFilters as $columnFilter)
    @if(is_array($columnFilter))
        <?php
        $columnFilterData = [];
        if (array_key_exists('name', $columnFilter) && !is_array($columnFilter['name'])) {
            $columnFilterData = [$columnFilter];
        } else {
            foreach ($columnFilter as $columnFilterKey => $columnFilterValue) {
                foreach ($columnFilterValue as $filterValue) {
                    $columnFilterData[] = array_merge($filterValue, ['name' => $columnFilterKey]);
                }
            }
        }
        ?>
        @foreach ($columnFilterData as $filterData)
            <li><a data-column-filter-name="{{ $filterData['name'] }}"{!! array_key_exists('value', $filterData) ? ' data-column-filter-value="' . $filterData['value'] . '"' : '' !!}{!! array_key_exists('replace', $filterData) && !$filterData['replace'] ? ' data-column-filter-replace="false"' : '' !!} href="javascript:void(0)">{{ array_key_exists('display', $filterData) ? $filterData['display'] : ucwords(str_replace('_', ' ', $filterData['name'])) }}</a></li>
        @endforeach
    @elseif($columnFilter == 'SEPARATOR' || $columnFilter == 'DIVIDER')
        <li role="separator" class="divider"></li>
    @elseif($columnFilter == 'ID')
        <li><a data-column-filter-name="id" data-column-filter-value="=" data-column-filter-replace="false" href="javascript:void(0)">ID</a></li>
    @elseif(substr($columnFilter, 0, 7) == 'HEADER:')
        <li class="dropdown-header">{{ substr($columnFilter, 7) }}</li>
    @else()
        <li><a data-column-filter-name="{{ $columnFilter }}" href="javascript:void(0)">{{ ucwords(str_replace('_', ' ', $columnFilter)) }}</a></li>
    @endif
@endforeach