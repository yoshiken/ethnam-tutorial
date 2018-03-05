# インストール

phpのパッケージ管理はcomposerらしい.
npmライクなのでなるほど〜という感じ.
`composer.json`に記述して`composer install`でインストール.

そういえばいつもLAMPで動かしてたけど軽いデバッグなら`php -t www -S localhost:8000` で動くらしい.(しらんかった)
調べてみたら -t オプションは5.4.0から組み込まれて、シングルスレッドで動くやつらしい.rubyでいうWEBRickとかpythonの -m 的なやつなんだろう.
 -t でルートドキュメント、 -S でURI(とPort)らしい.


