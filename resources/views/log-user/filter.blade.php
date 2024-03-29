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
    $logRequestMethodsColumnFilter['method'][] = [
        'value' => '=' . $logRequestMethod['method'],
        'display' => studly_case($logRequestMethod['method'])
    ];
}
$socialProvidersColumnFilter = ['type' => []];
foreach ($socialProviders as $socialProvider) {
    $socialProvidersColumnFilter['social'][] = [
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
        ['name' => 'ip', 'display' => 'IP'],
        ['name' => 'url', 'display' => 'URL'],
        ['name' => 'session', 'display' => 'Session ID'],
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