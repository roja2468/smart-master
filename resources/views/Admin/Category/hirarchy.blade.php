@foreach ($parentCategory as $category)
    <option value="{{ $category->id }}">{{$dashes}}{{ $category->name }}</option>
    @if(count($category->children))
        @php $newDashes = $dashes . '--' @endphp 
        @include('partials/hirearchy', ['parentCategory'=>$category->children, 'dashes'=>$newDashes])
    @endif
@endforeach