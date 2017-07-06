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
$socialProvidersColumnFilter = ['type' => []];
foreach ($socialProviders as $socialProvider) {
    $socialProvidersColumnFilter['action'][] = [
        'value' => '=' . $socialProvider['slug'],
        'display' => $socialProvider->getName()
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
        $logRequestMethodsColumnFilter,
        'SEPARATOR',
        'HEADER:Social Providers',
        $socialProvidersColumnFilter
     ]
])