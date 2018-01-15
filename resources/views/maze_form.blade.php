

<div style="margin:0 auto; width:80%; text-align:center;">
<h1> Upload a maze to solve</h1>
<h3>Upload a rectangular maze, from <a href="http://www.mazegenerator.net/" target="_blank">http://www.mazegenerator.net/</a>, downloaded as png</h3>
    @if (Session::has("message"))
       {{ Session::get("message") }}
     @endif
     <hr />

    <form  method="post" enctype="multipart/form-data" action="{{url('maze-solve')}}">
        <input type="file" name="file">
        <input type="submit">
         {{ csrf_field() }}
    </form>
</div>
