<?php
$logUserActionsColumnFilter = ['type' => []];
foreach ($logUserActions as $logUserAction) {
    $logUserActionsColumnFilter['action'][] = [
        'value' => '=' . $logUserAction['slug'],
        'display' => studly_case($logUserAction['slug'])
    ];
}
$logRequestMethodsColumnFilter = ['type' => []];
foreach ($logRequestMethods as $logRequestMethod) {
    $logRequestMethodsColumnFilter['action'][] = [
        'value' => '=' . $logRequestMethod['method'],
        'display' => studly_case($logRequestMethod['method'])
    ];
}
?>
@include('wmcms::crud.datatable-filter', [
    'daterangepicker' => 'created_at',
     'columnFilters' => [
        'HEADER:Columns',
        'ID',
        'user',
        ['value' => 'ip', 'display' => 'IP'],
        ['value' => 'url', 'display' => 'URL'],
        ['value' => 'session', 'display' => 'Session ID'],
        'SEPARATOR',
        'HEADER:Action',
        $logUserActionsColumnFilter,
        'SEPARATOR',
        'HEADER:Request Method',
        $logRequestMethodsColumnFilter
     ]
])