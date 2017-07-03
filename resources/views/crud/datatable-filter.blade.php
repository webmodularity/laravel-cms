<?php
$daterangepickers = (array) $daterangepicker;
?>
<div class="input-group" style="width:35vw;min-width:400px;">
    <div class="input-group-btn">
        <button type="button" class="btn btn-sm btn-default" id="dataTableFilterReset" title="Click to Reset Filters"><span class="text-muted">Filter Results:</span></button>
    </div>
    <input type="text" id="dataTableSearch" class="form-control input-sm" placeholder="Search...">
    <div class="input-group-btn">
        @foreach($daterangepickers as $daterangepicker)
            <button id="daterangepicker_{{ $daterangepicker }}" type="button" class="btn btn-sm btn-primary" title="{{ ucwords(str_replace('_', ' ', $daterangepicker)) }}">
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
        <ul class="dropdown-menu dropdown-menu-right" id="columnFilterHelper">
            <li class="dropdown-header">Columns</li>
            <li><a data-column-filter-name="name" href="javascript:void(0)">Name</a></li>
            <li><a data-column-filter-name="email" href="javascript:void(0)">Email</a></li>
            <li><a data-column-filter-name="branch" href="javascript:void(0)">Branch</a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Order Status</li>
            <li><a data-column-filter-name="status" data-column-filter-value="=open" href="javascript:void(0)">Open</a></li>
            <li><a data-column-filter-name="status" data-column-filter-value="=shipping" href="javascript:void(0)">Shipping</a></li>
            <li><a data-column-filter-name="status" data-column-filter-value="=shipped" href="javascript:void(0)">Shipped</a></li>
            <li><a data-column-filter-name="status" data-column-filter-value="=closed" href="javascript:void(0)">Closed</a></li>
        </ul>
    </div>
</div>

@push('js')
<script>
    $(function () {
        @foreach($daterangepickers as $daterangepicker)
        $('#daterangepicker_{{ $daterangepicker }}').daterangepicker({
            "timePicker": true,
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
                return WMCMS.DT.FILTER.getAllKeywords(val, '{{ $daterangepicker }}').concat(
                    [
                        '{{ $daterangepicker }}:>=' + picker.startDate.format('MM/DD/YYYYHHmmss'),
                        '{{ $daterangepicker }}:<=' + picker.endDate.format('MM/DD/YYYYHHmmss')
                    ]
                ).join(" ");
            }).trigger('keyup');
        });
        @endforeach
    });
</script>
@endpush