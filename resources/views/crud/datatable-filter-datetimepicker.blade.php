<?php
$datepickerColName = isset($datepickerColName) ? $datepickerColName : 'updated_at';
?>
<script>
    $(function () {
        $('#datepicker-{{ $datepickerColName }}').daterangepicker({
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
                return WMCMS.DT.FILTER.getAllKeywords(val, '{{ $datepickerColName }}').concat(
                    [
                        '{{ $datepickerColName }}:>=' + picker.startDate.format('MM/DD/YYYYHHmmss'),
                        '{{ $datepickerColName }}:<=' + picker.endDate.format('MM/DD/YYYYHHmmss')
                    ]
                ).join(" ");
            }).trigger('keyup');
        });
    });
</script>