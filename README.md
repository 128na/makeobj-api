# Simutrans Makeobj API

Makeobjをいい感じに使えるようにした公開APIです。

# 仕様

## 制限

各APIともIPごとに毎分60回までのリクエスト制限があります。
POSTされたファイルや生成されたファイルは1週間程度で自動削除されます。


## ステータスコード一覧

|ステータスコード|状態|レスポンスボディ|
|---|---|---|
|200|処理に成功|各APIの項目を参照|
|400|makeobj処理エラー|`{code:makeobj終了コード, output:標準出力, error:標準エラー出力}`|
|422|入力値バリデーションエラー|`{errors:{項目名:[エラー内容, ...], ...}}`|
|429|リクエスト頻度制限||
|500|システムエラー|各APIの項目を参照|


## API一覧
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
※ ファイルを送信するため、POST時の `enctype` を `multipart/form-data` にする必要があります。


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
※ ファイルを送信するため、POST時の `enctype` を `multipart/form-data` にする必要があります。


### [POST] /api/v1/pak

POSTしたdat、画像を利用してpak化をします。
`指定ファイル名.pak` 形式でpak化されたファイルのURLが返されます。

#### 画像をファイルで送信する
```
curl -H 'X-Requested-With: XMLHttpRequest' -sS 'https://makeobj-api.128-bit.net/api/v1/pak' -X POST -F "images[0]=@1xL.png" -F 'filename=test' -F 'size=64' -F 'dat="obj=building"' | jq
{
  "pakfile": "https://makeobj-api.128-bit.net/storage/pak/xxxxxxxxxxx/test.pak"
}
```
※ ファイルを送信するため、POST時の `enctype` を `multipart/form-data` にする必要があります。

#### 画像をURLで送信する

画像がweb上にアップロードされている場合はこちらが便利です。

```
curl -X POST -H "Content-Type: application/json" -d '{"filename": "test","dat": "obj=building","size": 64,"imageUrls": [{"filename" : "hoge.png","url" : "https://raw.githubusercontent.com/128na/pak64.map/master/src/dat/building/1xL.png"}]}' https://makeobj-api.128-bit.net/api/v1/pak | jq
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
※ ファイルを送信するため、POST時の `enctype` を `multipart/form-data` にする必要があります。


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
※ ファイルを送信するため、POST時の `enctype` を `multipart/form-data` にする必要があります。

