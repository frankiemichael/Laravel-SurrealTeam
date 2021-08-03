<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Label Requests') }}
        </h2>

    </x-slot>
    <x-jet-validation-errors />
    <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Deadline</th>
      <th scope="col">Creator</th>
      <th scope="col">Amount</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  @foreach($requests as $request)
    <tr>    
      <th scope="row"><a href="{{route('tremenheere.labels.requestshow', $request->id)}}">
      @if ($request->deadline)
      {{$request->deadline}}
      @else
      None
      @endif
      </a></th>
      <td><a href="{{route('tremenheere.labels.requestshow', $request->id)}}">{{$request->user->name}}</a></td>
      <td><a href="{{route('tremenheere.labels.requestshow', $request->id)}}">
      <?php
        $count = 0;
      ?>
      
      @foreach($request->items as $item)
        <?php
            $count += $item->quantity;
        ?>
        
      @endforeach
      {{$count}}
      </a></td>
      <td>
        <form method="post" action="{{route('tremenheere.labels.completerequest', $request->id)}}">
          @csrf
          @method('put')
        <button type="submit" class="complete btn btn-success">Mark Completed</a></td>

        </form>
    </tr>
    @endforeach
  </tbody>
</table>
<script>
  $('.complete').click(function(e){
    confirm('Are you sure?')
  })

</script>
</x-app-layout>