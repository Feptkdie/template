@extends("layouts.app")

@section("content")
<div class="container">
    @include("flash_message")
    
    <div class="card border-0 shadow-sm">
        
        <div class="card-body">
            
            <a href="{{ route("banner.create") }}" class="btn btn-success rounded-pill mb-3">Create banner</a>
            
            <h4 class="card-title">
                Banners <small class="text-muted">({{ count($banners) }})</small>
            </h4>
            <table class="table table-striped">
                <tr>
                    <th>Title</th>
                    <th>image</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                @foreach ($banners as $banner)
                    <tr>
                        <td>{{ $banner->title }}</td>
                        <td>
                            @if ($banner->image)
                                <img src="{{ $banner->image }}" class="img-fluid" style="max-width: 100px; max-height: 50px">        
                            @endif   
                        </td>
                        <td>{{ $banner->created_at }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route("banner.edit", $banner->id) }}">Edit</a>
                            <confirm-delete
                                :data-id="{{ json_encode($banner->id) }}" 
                                :data-title="{{ json_encode($banner->title) }}" 
                                :data-url="{{ json_encode('/banner/' . $banner->id) }}" 
                                data-redirect-url="/banner">
                            </confirm-delete>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection