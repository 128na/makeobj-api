<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Makeobj API demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

</body>
<div class="container">
    <div class="mb-3">
        <h2>Makobj API demo</h2>
    </div>

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li class="ms-3">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="post" action="{{ route('demo.pak') }}" enctype="multipart/form-data">
        @csrf

        <div class=" mb-3">
            <label for="filename" class="form-label">ファイル名</label>
            <div class="input-group">
                <input type="text" class="form-control" id="filename" name="filename"
                    value="{{ old('filename', 'example') }}">
                <span class="input-group-text">.pak</span>
            </div>
        </div>

        <div class="mb-3">
            <label for="dat" class="form-label">dat
            </label>
            <textarea class="form-control" id="dat" name="dat"
                rows="10">{{ old(
    'dat',
    'Obj=building
Name=example1
Copyright=128Na
type=res
chance=100
Level=2
clusters=1,11
BackImage[0][0][0][0][0]=1xL.0.7
',
) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">画像ファイル一覧（複数可）</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept=".png">

            <div class="mt-2">
                テスト用画像はこちら<a target="_blank" rel="noopener"
                    href="https://github.com/128na/pak64.map/blob/master/src/dat/building/1xL.png">1xL.png</a>
            </div>

            <div id="preview" class="border mt-3 position-relative overflow-scroll"></div>
        </div>
        <div class="mb-3">
            <label for="filename" class="form-label">pak サイズ</label>
            <input type="number" class="form-control" id="size" name="size" placeholder="output"
                value="{{ old('size', 64) }}">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary btn-lg">Pak化</button>
        </div>

    </form>

    <div class="mb-3">
        <a target="_blank" rel="noopener" href="https://github.com/128na/makeobj-api">Github</a>,
        <a target="_blank" rel="noopener" href="https://twitter.com/128Na">Twitter</a>
    </div>
</div>

<script>
    const images = document.getElementById('images');
    const preview = document.getElementById('preview');
    const size = document.getElementById('size');

    const handlePreview = e => {
        console.log(size.value)
        preview.innerHTML = `<div id="grid" style="background-size:${size.value || 64}px"></div>`;
        [...e.target.files].map(f => {
            const r = new FileReader();
            r.onload = () => preview.innerHTML += `<img src="${r.result}">`;
            r.readAsDataURL(f)
        });
    };

    const handleSize = e => {
        const el = document.getElementById('grid');
        if (el) {
            el.style = `background-size:${size.value || 64}px`;
        }
    };

    images.addEventListener('change', handlePreview);
    size.addEventListener('change', handleSize);
</script>

<style>
    #grid {
        background-image: url(/grid.png);
        margin: 0;
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        z-index: 1024;
    }

</style>

</html>
