({{ $phone->area_code }})&nbsp;{{ substr($phone->number, 0, 3) }}-{{ substr($phone->number, 3, 4) }}{{ !empty($phone->ext) ? "&nbsp;x" . $phone->ext : '' }}