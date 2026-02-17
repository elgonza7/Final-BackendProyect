<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>

    <h2>Comentarios:</h2>
    @foreach($post->comments as $comment)
        <div>
            <strong>{{ $comment->name }}</strong>
            <p>{{ $comment->content }}</p>
        </div>
    @endforeach
    
</body>
</html>