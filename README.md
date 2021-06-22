# Simutrans Makeobj API

Makeobjをいい感じに使えるようにした公開APIです。

# 制限

各APIとも毎分10回までのリクエスト制限があります。


# API一覧
基本的な使い方は makobj の引数と同じです。

### [GET] /api/v1/version

使用しているmakobjのバージョン情報を返します。

```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/version' | jq
{
  "version": "Makeobj version 60.2 for Simutrans 120.4 and higher\n"
}
```

### [GET] /api/v1/capabilities

pak化可能な使用しているmakobjのバージョン情報を返します。

```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/capabilities' | jq
{
  "capabilities": [
    "bridge",
    ...
    "way-object"
  ]
}
```

### [POST] /api/v1/list

POSTしたpakファイルに含まれているアドオン一覧を返します。
```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/list' -X POST -F 'file=@example.all.pak' | jq
{
  "list": [
    {
      "type": "building",
      "name": "example1",
      "nodes": 3,
      "size": 2278
    },
    {
      "type": "building",
      "name": "example2",
      "nodes": 3,
      "size": 2278
    }
  ]
}
```

### [POST] /api/v1/dump

POSTしたpakファイルのダンプ情報を返します。
```

curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/dump' -X POST -F 'file=@example.all.pak'| jq
{
  "node": {
    "level": "1",
    "name": "ROOT-node",
    "type": "root",
    "size": "0",
    "value": null,
    "children": [
      {
        "level": "2",
        "name": "BUIL-node",
        "type": "building",
        "size": "39",
        "value": null,
        "children": [
              ...
```

### [POST] /api/v1/pak

POSTしたdat、画像を利用してpak化をします。
`指定ファイル名.pak` 形式でpak化されたファイルのURLが返されます。

```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/pak' -X POST -F "images[0]=@1xL.png" -F 'filename=test' -F 'size=64' -F 'dat="obj=building"' | jq
{
  "pakfile": "https://makeobj-api.128-bit.net/storage/pak/xxxxxxxxxxx/test.pak"
}
```

### [POST] /api/v1/merge

POSTしたpakファイルをマージします。
`指定ファイル名.pak` 形式でpak化されたファイルのURLが返されます。

```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/merge' -X POST -F 'files[0]=@example1.pak
' -F 'files[1]=@example2.pak' -F 'filename=merged' | jq
{
  "pakfile": "https://makeobj-api.128-bit.net/storage/pak/xxxxxxxxxxx/merged.pak"
}
```

### [POST] /api/v1/extract

POSTしたマージ済みのpakファイルからアドオンを抽出します。
抽出化されたファイルのURL一覧が返されます。

```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/extract' -X POST -F 'file=@example.all.pa k' | jq
{
  "pakfiles": [
    "https://makeobj-api.128-bit.net/storage/extract/xxxxxxxxxxx/building.example1.pak",
    "https://makeobj-api.128-bit.net/storage/extract/xxxxxxxxxxx/building.example2.pak"
  ]
}
```
