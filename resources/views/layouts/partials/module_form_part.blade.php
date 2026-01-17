@if(isset($module_form_parts) && !empty($module_form_parts) && is_array($module_form_parts))
  @foreach($module_form_parts as $key => $value)
    @if(!empty($value) && is_array($value) && !empty($value['template_path']))
      @php
        $template_data = isset($value['template_data']) ? $value['template_data'] : [];
      @endphp
      @include($value['template_path'], $template_data)
    @endif
  @endforeach
@endif