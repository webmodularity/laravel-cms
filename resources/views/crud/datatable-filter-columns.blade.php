@foreach($columnFilters as $columnFilter)
    @if(is_array($columnFilter))
        @if(isset($columnFilter['header']))
            <li class="dropdown-header">{{ $columnFilter['header'] }}</li>
        @else()
            <?php
            $columnFilterData = [];
            if (array_key_exists('name', $columnFilter) && !is_array($columnFilter['name'])) {
                if (!array_key_exists('display', $columnFilter)) {
                    $columnFilter['display'] = ucwords(str_replace('_', ' ', $columnFilter['name']));
                }
                $columnFilterData = [$columnFilter];
            } else {
                foreach ($columnFilter as $columnFilterKey => $columnFilterValue) {
                    $columnFilterData[] = array_merge($columnFilterValue, ['name' => $columnFilterKey]);
                }
            }
            ?>
            @foreach ($columnFilterData as $filterData)
                <li><a data-column-filter-name="{{ $filterData['name'] }}"{!! array_key_exists('value', $filterData) ? ' data-column-filter-value="' . $filterData['value'] . '"' : '' !!} href="javascript:void(0)">{{ $filterData['display'] }}</a></li>
            @endforeach
        @endif
    @elseif($columnFilter == 'SEPARATOR' || $columnFilter == 'DIVIDER')
        <li role="separator" class="divider"></li>
    @else()
        <li><a data-column-filter-name="{{ $columnFilter }}" href="javascript:void(0)">{{ ucwords(str_replace('_', ' ', $columnFilter)) }}</a></li>
    @endif
@endforeach