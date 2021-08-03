<div class="row justify-content-center my-5">
    <div class="col-md-12">
        <div class="card shadow bg-light">
            <div class="card-body bg-white px-5 py-3 border-bottom rounded-top">
                <div class="mx-3 my-3">
                    <div>
                        <x-jet-application-logo style="width: 317px;" />
                    </div>

                    <h3 class="h3 my-4">
                        Welcome {{Auth::user()->name}}
                    </h3>

                    <div class="text-muted">
                        Laravel Jetstream provides a beautiful, robust starting point for your next Laravel application. Laravel is designed
                        to help you build your application using a development environment that is simple, powerful, and enjoyable. We believe
                        you should love expressing your creativity through programming, so we have spent time carefully crafting the Laravel
                        ecosystem to be a breath of fresh air. We hope you love it.
                    </div>
                </div>
            </div>
            <div class="row g-0">
                    <div class="card-body  border-bottom p-3 h-100">
                        <div class="flex-row bd-highlight mb-3">                            <div class="pl-3">
                                <div class="mb-2">
                                    <a class="h5 font-weight-bolder text-decoration-none text-dark">Updates</a>
                                </div>
                                @foreach($posts as $post)
                                <div class="card w-100">
                                <div class="card-header">
                                {{$post->updated_at}}
                                </div>
                                <div class="card-body">
                                <blockquote class="blockquote mb-0">
                                <a href="route('post.show')">{{$post->title}}</a>
                                <footer class="blockquote-footer">{{$post->creator}}</footer>
                                </blockquote>
                                </div>
                                </div>
                                </div>
                                @endforeach


                            </div>
                        </div>   
                    </div>         
            </div>
        </div>
    </div>
</div>
