@foreach ($sub_laporan as $sub)
    <option value="{{ $sub['value'] }}">{{ $sub['title'] }}</option>
@endforeach
