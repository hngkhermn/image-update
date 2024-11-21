@extends('templates.default')

@section('content')

<div class="container">
    <div class="table-wrapper">
      <div class="table-title">
        <div class="row">
          <div class="col-sm-6">
            <h2>Files Table</h2>
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
          </div>
          <div class="col-sm-6">
            <a href="#addImageModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New </span></a>
            
          </div>
        </div>
      </div>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>
              <span class="custom-checkbox">
                                  <input type="checkbox" id="selectAll">
                                  <label for="selectAll"></label>
                              </span>
            </th>
            <th>Name</th>
            <th>Extension</th>
            <th>Path</th>
            <th>Size</th>
            <th>Thumbnail</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($files as $file)   
        <tr>
          <td>
            <span class="custom-checkbox">
                                <input type="checkbox" id="checkbox1" name="options[]" value="1">
                                <label for="checkbox1"></label>
                            </span>
          </td>
          <td>{{ $file->name }}</td>
          <td>{{ $file->extension }}</td>
          <td>{{ $file->path }}</td>
          <td>{{ $file->size }}</td>
          @if ($file->extension === 'jpg' || $file->extension === 'png' || $file->extension === 'jpeg')
              <td><img src="{{ asset('assets/img/img.png') }}" alt="" width="50"></td>
          @elseif ($file->extension === 'pdf')
            <td><img src="{{ asset('assets/img/pdf.png') }}" alt="" width="50"></td>
          @elseif($file->extension === 'xlsx')
            <td><img src="{{ asset('assets/img/xls.png') }}" alt="" width="50"></td>
          @else
            <td><img src="{{ asset('assets/img/file.png') }}" alt="" width="50"></td>
          @endif
          <td>{{ $file->created_at }}</td>
          <td>
            <a href="{{ $file->path }}" target="_blank"><i class="material-icons" title="View">&#xe8f4;</i></a>
            <a href="#deleteImageModal{{ $file->id }}" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
  <!-- Delete Modal HTML -->
  @foreach ($files as $file)      
  <div id="deleteImageModal{{ $file->id }}" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('delete.file',$file->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <div class="modal-header">
            <h4 class="modal-title">Delete File</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete these Records?</p>
            <p class="text-warning"><small>This action cannot be undone.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" class="btn btn-danger" value="Delete">
          </div>
        </form>
      </div>
    </div>
  </div>
  @endforeach
  <!-- Add Modal HTML -->
  <div id="addImageModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('store.file')  }}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="modal-header">
            <h4 class="modal-title">Add File</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>File</label>
              <input name="file[]" id="file" type="file" class="form-control" multiple required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" class="btn btn-success" value="Add">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Edit Modal HTML -->
  {{-- <div id="editImageModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form>
          <div class="modal-header">
            <h4 class="modal-title">Edit File</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>File</label>
              <input type="file" class="input-group-text" id="inputGroupFileAddon01" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
            <input type="submit" class="btn btn-info" value="Save">
          </div>
        </form>
      </div>
    </div>
  </div> --}}

    
@endsection