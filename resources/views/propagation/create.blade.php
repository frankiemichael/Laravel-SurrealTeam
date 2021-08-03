<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create Plant') }}
        </h2>

    </x-slot>

    <x-jet-validation-errors />
    <form method="post" action="{{route('propagation.store')}}" enctype="multipart/form-data">
    @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" id="name" type="text" name="name" placeholder="{{ old('name', 'Name') }}">
        </div>
        <div class="form-group">
            <label for="site">Site</label>
            <select class="form-control" id="site" name="site">
                @if($source === 'Trereife')
                    <option value="0" selected>Trereife</option>
                    <option value="1">Trewidden</option>

                @elseif($source === 'Trewidden')
                    <option value="0">Trereife</option>
                    <option value="1" selected>Trewidden</option>

                @endif
            </select>
        </div>
        <label for="location">Location</label>
        <div class="input-group input-group-lg mb-3" style="width:300px;">
            <input class="form-control" type="text" name="location" value="{{old('location', '')}}"></input>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" style="height:20vh;" class="form-control" placeholder="{{old('notes', 'Notes')}}">{{old('notes', '')}}</textarea>
        </div>
        <label for="quantity">Quantity</label>
        <div class="input-group input-group-lg mb-3" style="width:300px;">
            <input class="form-control" type="number" name="quantity" value="{{old('quantity', '')}}"></input>
        </div>
        <div class="form-group">
            <label for="method">Method</label>
            <select name="method" class="form-control">
                <option value="Air">Air</option>
                <option value="Grit">Grit</option>
            </select>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="cuttings">
            <label class="form-check-label" for="cuttings">
                Cuttings
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <script>
        $('input[name="cuttings"]').change(function(){
            this.value = (Number(this.checked))
        })
    </script>
</x-app-layout>