# インストール

phpのパッケージ管理はcomposerらしい.

npmライクなのでなるほど〜という感じ.

`composer.json`に記述して`composer install`でインストール.

そういえばいつもLAMPで動かしてたけど軽いデバッグなら`php -t www -S localhost:8000` で動くらしい.(しらんかった)

調べてみたら -t オプションは5.4.0から組み込まれて、シングルスレッドで動くやつらしい.rubyでいうWEBRickとかpythonの -m 的なやつなんだろう.

 -t でルートドキュメント、 -S でURI(とPort)らしい.

# アクション

app/action以下に次の命名規則で作成する

- アクション名の先頭を大文字
- '_'を'/'に置き換え(= ディレクトリで区切り)続く文字を大文字

Ethna_ActionClassを継承してperform()をオーバライドしながら記述する

perform()メソッドの戻り値として遷移名(画面を表す名前)を返する

e.g.)

```
<?php
class Sample_Action_Hello extends Ethna_ActionClass
{
    public function perform()
    {
        return 'hello';
    }
}

```

`vendor/bin/ethnam-generator add-action hello`で雛形作成できるらしい

# ビュー

actionと同じでapp/viewに

- '\_'をディレクトリで区切り
- ファイル名は大文字で開始し

という命名規則で作成

`vendor/bin/ethnam-generator add-view hello` でスケルトンファイルが追加できる

- t をつけると同時にテンプレートファイルもジェネレートされる


# テンプレート

テンプレートファイルディレクトリは template/ja_JA

`vendor/bin/ethnam-generator add-template hello`でスケルトンファイル作成


# Smarty

アクションファイル or ビューファイルで`$this->af->setApp('foo', 'bar');`と設定するとテンプレートで{$app.foo}として呼べるようになる。
[Smarty](https://www.smarty.net/docsv2/ja/index.tpl)というやつを採用してるかららしい。

つまりはethnamではMVCモデルの(MC)・Vということか？
→違う。Document-View(M-CV)だった

# アプリケーション開発

## ログイン

- フォーム値の取得方法
- 基本的なエラー処理方法
- ビューへのデータ設定方法

をやっていき

## テンプレからアクション発火

```
<input type="submit" name="action_login_do" value="ログイン">
```

で`"name=action_login_do"`のところが、`login_do`というアクションの発火をさせているらしい。

## アロー演算子

そういえば`->`ってなんだっけと思ったらアロー演算子だった。Cやってたときに使ってた気がするけど記憶が何処かに行ってしまったので復習。

```

<?php

//クラス呼び出し
$myCar = new Car;

//Carクラスのrun関数を呼び出す
$myCar = -> run();


class Car
{
        public $Color = "";
        public $spead = "0";

        function run(){
                //this = pythonでいうselfみたいなもん
                print "${this->color}の車が${this->spead}kmで走行している"
        }
}
?>

```

ドット演算子とは微妙に違うらしい。が、そこまで気にしなくて良さそう。

ちなみに `=>`はダブルアロー演算子といわれ連想配列で使うっぽい

```

<?php

$fruit = array("apple" => "リンゴ", "orange" => "ミカン", "grape" => "ブドウ");

```

## VAR_TYPE_STRINGとは

ethnaからの引き継ぎで入力フォームの自動検証がある。

検証の種類に色々あって、`VAR_TYPE_STRING`は文字列かどうかの検証

種類は

- VAR_TYPE_INT
    - 整数(+/-)
- VAR_TYPE_FLOAT
    - 小数(+/-)
- VAR_TYPE_STRING
    - 文字列
- VAR_TYPE_DATETIME
    - 日付(YYYY/MM/DD HH:MM:SS等)
- VAR_TYPE_BOOLEAN
    - 真偽値(1 or 0)
- VAR_TYPE_FILE
    - ファイル

` 'type' => VAR_TYPE_STRING ` といった具合に投げてあげればよくて、属性情報として

- type
    - フォーム値の型
- required
    - 必須かどうか
- min
    - 最小文字数
- max
    - 最大文字数
- regexp
    - 正規表現のチェック
- mbregexp
    - マルチバイト対応の正規表現
- name
    - name

があるらしい。必須はtypeのみ。

細かくは[ethnaの公式ドキュメント](http://ethna.jp/old/ethna-document-dev_guide-form-validate.html#oc46874f)で

# エラー表示

まずは入力した値を保持して再表示しないとGoodなUXとは呼べない

フォームで入力した値は自動的に前述したSmartyに入れられているのでそれを呼び出す処理をフォームに追加すれば良い

おそらくフォーム周りの記述はテンプレートに書かれているので

```
-   <td><input type="text" name="mailaddress" value=""></td>
+   <td><input type="text" name="mailaddress" value="{$form.mailaddress}"></td>
```

とする。`{$form.mailaddresss}`がみそ味噌Miso

これでテンプレート呼び出し時(画面生成時)にフォームのデフォルト値(Value)にmailaddresが入力されるというわけ。

`{$form.hogehoge}'なんて少し規模あげたらぶつかりそうなんだけどこのフォームだけのnameを見ているのか内部的なのがわからんけどいつかわかるだろ。未来の僕がんばれ。

tutorialだとこういうときの内部処理を書いてくれないドキュメントが多いので結局公式ドキュメント見るしか無いけどethnamの公式ドキュメントこれだけなのでうむ…Smartyの方を見たほうが早いか…

つかぬ疑問だが、`if ($this->af->validate() > 0)` の部分、返り値0=エラーが発生していない・返り値0超過の場合はエラー発生という仕組みだが、この場合想定返り値以外を予測して`if (!($this->af->validate() = 0))`みたいに書くほうが正確性があるのでは？まぁif文は条件マッチを前提に書くほうがキレイといえばキレイだけども。


## ビジネスロジック(設計)
performメソッドちゃんと把握しないと話にならんな！(小並)

