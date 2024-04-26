# 1.　はじめに
利用するためには、BPMクレジット決済サービスに申し込みが必要となります。

**申込みはこちらから(https://bpmc.co.jp/)**

# 2.　プラグインの概要
WoocommerceにてBPMクレジット決済をするためのプラグインになります。

# 3.　サポート環境
- WordPress `v6+`
- Woocommerce `v8+`
- PHP `v7.4` `v8+`

# 4．ライセンスに関する表記

MITライセンスとなっております。

ソースコードの変更に伴ういかなる責任も弊社は負いません。

# 5．導入手順
## 5.1．事前に必要なもの
弊社との契約時に発行される、「API Token」 が必要になります。
      
IPアドレスの登録も必要になります。（別途登録方法は案内いたします）。

## 5.2．プラグインのダウンロード方法
- こちらのURL(https://github.com/bpmc-tech/woocommerce-bpm-payment/releases)
  から「woocommerce-bpm-payment_v⚪︎⚪︎.zip」をクリックしてダウンロードします。
  
![スクリーンショット 2024-04-25 17 01 38](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/f9176932-7282-457a-81e6-5dfa2a783d72)

## 5.3．プラグインのインストール方法
- プラグインの項目から「新規プラグインを追加」を選択します。
  
![スクリーンショット 2023-11-21 12 29 56](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/3b306ccb-6f35-4c61-ad5e-71dc385eb9d8)

- 「プラグインのアップロード」から5.2でダウンロードしたzipファイルを選択し、「今すぐインストール」をクリックします。
  
![スクリーンショット 2024-04-25 17 04 46](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/35bbbdd0-52c1-48a3-9ee4-3b692ff8191d)

- 画面が切り替わるので「プラグインを有効化」を選択し、プラグインを有効化します。
  
![スクリーンショット 2024-04-25 17 05 17](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/acb79b46-51f5-491d-9995-f1a3666f08b6)

## 5.4．設定方法
- 左側のメニューバーの「woocommerce」から「設定」を選択します。
  
![スクリーンショット 2023-11-21 12 37 56](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/144ddb4d-a537-43c5-ab94-9990e608d039)

- 「決済」を選択し、BPM Paymentの「管理」を選択します。

![スクリーンショット 2023-11-21 12 38 19](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/93211488-fb3d-4223-a559-34dd19e17712)

- 設定画面が開くので、「API_TOKEN」を設定します。

| 設定項目 | 設定内容 |
|:----|:----|
| 支払い名称 | 支払いページに表示されるタイトルになります。 |
| API Token | 弊社から発行された値を入力してください。 |
| ログ出力をする | 開発者向けの項目になります。詳しくは6.1に記載します。 |

![スクリーンショット 2024-04-25 18 00 28](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/a29c243f-d27d-467f-afa3-a6b6f6e63934)

## 5.5．IPNの設定

- 店舗管理サイトにログインし、結果通知設定(HTTP)より設定します。<br>
  送信状態は「送信する(POST)」を設定していただき、<br>
  HTTP結果通知の欄には`（御社ECサイトドメイン）/wc-api/bpm_payment_ipn/` とご入力ください。
  
![スクリーンショット 2024-04-25 17 52 10](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/dca574e9-8411-46ba-a7f6-5c221ee3a539)

## 5.6．実際の決済フォームの説明
- 購入画面で「BPM Payment」を選択します。

![スクリーンショット 2024-04-26 12 45 49](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/d29f0ed3-9855-4d02-a0cd-1c23da5bba08)

- 「注文する」を押すことでBPM決済ページに遷移します。
  
![スクリーンショット 2024-04-25 18 05 40](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/ad93eec4-1d1e-4cca-9096-be7ca707d1c4)

## 5.7．オーダー画面の確認方法
- 左側のメニューバーの「woocommerce」から「注文」を選択します。

| 注文名称 | 状態 |
|:----|:----|
| 支払い待ち | 支払いページに遷移したあと、まだ決済が行われていない状態です。 |
| キャンセル | 支払いページに遷移したあと、決済が行われる前に注文ページに戻ってきた状態です。 |
| 処理中| BPM決済サイトでの支払いが終わった状態です。承認番号などを確認することができます。 |

![スクリーンショット 2024-04-26 12 31 24](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/0c4441e1-dc29-43cd-8037-5266087c170a)

- 各注文を選択することで注文の詳細を確認できます。
  
  承認番号を用いることで、BPM側のサイトで検索することも可能です。

![スクリーンショット 2023-11-21 17 07 05](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/245b526a-ec04-4f71-8281-85467921c727)

# 6．開発者向け
## 6.1．作成されるデータベースについて
プラグインを有効化し、BPM-Paymentにて決済を行うと、「wp_wc_bpm_payment_tran」テーブルが作成されます。

また、5.4の設定画面にて「ログ出力をする」にチェックを付けると、ログファイルを出力します。ログファイルには
 
 - 決済URL
 - 送信パラメータ
 - Response
  
が格納されます。

# 7． サポートについて
info@bpmc.co.jpまで問い合わせください。
