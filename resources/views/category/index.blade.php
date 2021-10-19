@extends("layouts.app")

@section("content")
<div class="container">
    @include("flash_message")
    
    <div class="card border-0 shadow-sm">
        
        <div class="card-body">
            
            <a href="{{ route("category.create") }}" class="btn btn-success rounded-pill mb-3">Create category</a>
            
            <h4 class="card-title">
                Categories <small class="text-muted">({{ count($categories) }})</small>
            </h4>
            <table class="table table-striped">
                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->title }}</td>
                        <td>
                            @if ($category->image)
                                <img src="{{ $category->image }}" class="img-fluid" style="max-width: 100px; max-height: 50px">        
                            @endif   
                        </td>
                        <td>{{ $category->created_at }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route("category.edit", $category->id) }}">Edit</a>
                            <confirm-delete
                                :data-id="{{ json_encode($category->id) }}" 
                                :data-title="{{ json_encode($category->title) }}" 
                                :data-url="{{ json_encode('/category/' . $category->id) }}" 
                                data-redirect-url="/category">
                            </confirm-delete>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection