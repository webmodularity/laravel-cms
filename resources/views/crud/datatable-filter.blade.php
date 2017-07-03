<?php
$daterangepickers = isset($daterangepicker) ? (array) $daterangepicker : [];
?>
<div class="input-group" style="width:35vw;min-width:400px;">
    <div class="input-group-btn">
        <button type="button" class="btn btn-sm btn-default" id="dataTableFilterReset" title="Click to Reset Filters"><span class="text-muted">Filter Results:</span></button>
    </div>
    <input type="text" id="dataTableSearch" class="form-control input-sm" placeholder="Search...">
    <div class="input-group-btn">
        @foreach($daterangepickers as $daterangepickerKey => $daterangepicker)
            <?php
                $daterangepickerColName = is_array($daterangepicker) ? $daterangepickerKey : $daterangepicker;
            ?>
            <button id="daterangepicker_{{ $daterangepickerColName }}" type="button" class="btn btn-sm btn-primary" title="{{ ucwords(str_replace('_', ' ', $daterangepickerColName)) }}">
                <span class="fa fa-calendar"></span>
                &nbsp;
                <span class="fa fa-caret-down"></span>
            </button>
        @endforeach
        <button id="filter" type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-search"></span>
            &nbsp;
            <span class="fa fa-caret-down"></span>
        </button>
            @if(isset($columnFilters))
                <ul class="dropdown-menu dropdown-menu-right" id="columnFilterHelper">
                    @include('wmcms::crud.datatable-filter-columns')
                </ul>
            @endif
    </div>
</div>

@push('js')
<script>
    $(function () {
        @foreach($daterangepickers as $daterangepickerKey => $daterangepicker)
        <?php
            if (is_array($daterangepicker)) {
                $useTime = isset($daterangepicker['time']) && !$daterangepicker['time'] ? 'false' : 'true';
                $daterangepickerColName = $daterangepickerKey;
            } else {
                $useTime = 'true';
                $daterangepickerColName = $daterangepicker;
            }
        ?>
        $('#daterangepicker_{{ $daterangepickerColName }}').daterangepicker({
            "timePicker": {{ $useTime }},
            "timePickerIncrement": 1,
            "linkedCalendars": false,
            "opens": "left",
            "autoUpdateInput": false,
            "alwaysShowCalendars": true,
            "timePickerSeconds": true,
            "ranges": {
                "Today": [
                    moment().startOf('day'),
                    moment().endOf('day')
                ],
                "Yesterday": [
                    moment().subtract(1, 'days').startOf('day'),
                    moment().subtract(1, 'days').endOf('day')
                ],
                "Last 7 Days": [
                    moment().subtract(7, 'days').startOf('day'),
                    moment().endOf('day')
                ],
                "Last 30 Days": [
                    moment().subtract(30, 'days').startOf('day'),
                    moment().endOf('day')
                ],
                "Last 365 Days": [
                    moment().subtract(365, 'days').startOf('day'),
                    moment().endOf('day')
                ],
                "This Month": [
                    moment().startOf('month'),
                    moment().endOf('month')
                ],
                "This Year": [
                    moment().startOf('year'),
                    moment().endOf('year')
                ]
            },
        }).on('apply.daterangepicker', function(ev, picker) {
            $('#dataTableSearch').val(function(index, val) {
                return WMCMS.DT.FILTER.getAllKeywords(val, '{{ $daterangepickerColName }}').concat(
                    [
                        '{{ $daterangepickerColName }}:>=' + picker.startDate.format('MM/DD/YYYY{{ $useTime == 'true' ? 'HHmmss' : '' }}'),
                        '{{ $daterangepickerColName }}:<=' + picker.endDate.format('MM/DD/YYYY{{ $useTime == 'true' ? 'HHmmss' : '' }}')
                    ]
                ).join(" ");
            }).trigger('keyup');
        });
        @endforeach
    });
</script>
@endpush