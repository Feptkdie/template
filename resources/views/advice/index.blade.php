@extends("layouts.app")

@section("content")
<div class="container">
    @include("flash_message")
    
    <div class="card border-0 shadow-sm">
        
        <div class="card-body">
            
            <a href="{{ route("advice.create") }}" class="btn btn-success rounded-pill mb-3">Create advice</a>
            
            <h4 class="card-title">
                Categories <small class="text-muted">({{ count($advices) }})</small>
            </h4>
            <table class="table table-striped">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Cover</th>
                    <th>Views</th>
                    <th>Likes</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                @foreach ($advices as $advice)
                    <tr>
                        <td>{{ $advice->title }}</td>
                        <td>{{ $advice->category_text }}</td>
                        <td>
                            @if ($advice->cover)
                                <img src="{{ $advice->cover }}" class="img-fluid" style="max-width: 100px; max-height: 50px">        
                            @endif   
                        </td>
                        <td>{{ $advice->views }}</td>
                        <td>{{ $advice->likes }}</td>
                        <td>{{ $advice->created_at }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route("advice.edit", $advice->id) }}">Edit</a>
                            <confirm-delete
                                :data-id="{{ json_encode($advice->id) }}" 
                                :data-title="{{ json_encode($advice->title) }}" 
                                :data-url="{{ json_encode('/advice/' . $advice->id) }}" 
                                data-redirect-url="/advice">
                            </confirm-delete>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection