<?php
$datepickerColName = isset($datepickerColName) ? $datepickerColName : null;
?>
<div class="input-group" style="width:35vw;min-width:400px;">
    <div class="input-group-btn">
        <button type="button" class="btn btn-sm btn-default" id="dataTableFilterReset" title="Click to Reset Filters"><span class="text-muted">Filter Results:</span></button>
    </div>
    <input type="text" id="dataTableSearch" class="form-control input-sm" placeholder="Search...">
    <div class="input-group-btn">
        @if($datepickerColName)
            <button id="datepicker-{{ $datepickerColName }}" type="button" class="btn btn-sm btn-primary" title="{{ ucwords(str_replace('_', ' ', $datepickerColName)) }}">
                <span class="fa fa-calendar"></span>
                &nbsp;
                <span class="fa fa-caret-down"></span>
            </button>
        @endif
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
    @if($datepickerColName)
        @include('wmcms::crud.datatable-filter-datetimepicker', [
            'datepickerColName' => $datepickerColName
        ])
    @endif
@endpush