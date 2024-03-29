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

ソースコードに変更に伴ういかなる責任も弊社は負いません。

# 5．導入手順
## 5.1．事前に必要なもの
弊社との契約時に発行される、「API Token」 「API Secret」が必要になります。
      
IPアドレスの登録も必要になります。（別途登録方法は案内いたします）。

## 5.2．プラグインのダウンロード方法
- こちらのURL(https://github.com/bpmc-tech/woocommerce-bpm-payment/releases)
  から「woocommerce-bpm-payment_v⚪︎⚪︎.zip」をクリックしてダウンロードします。
  
![スクリーンショット 2023-11-21 16 45 15](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/b651eff1-b0a0-46ab-bff7-1ab380fdf298)

## 5.3．プラグインのインストール方法
- プラグインの項目から「新規プラグインを追加」を選択します。
  
![スクリーンショット 2023-11-21 12 29 56](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/3b306ccb-6f35-4c61-ad5e-71dc385eb9d8)

- 「プラグインのアップロード」から5.2でダウンロードしたzipファイルを選択し、「今すぐインストール」をクリックします。
  
![スクリーンショット 2023-11-22 11 52 15](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/1eaac4eb-dafa-45e4-91f6-f1c8ea2ff856)

- 画面が切り替わるので「プラグインを有効化」を選択し、プラグインを有効化します。
  
![スクリーンショット 2023-11-22 11 52 25](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/3acda431-988f-4a88-b660-b8880ebcd87a)

## 5.4．設定方法
- 左側のメニューバーの「woocommerce」から「設定」を選択します。
  
![スクリーンショット 2023-11-21 12 37 56](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/144ddb4d-a537-43c5-ab94-9990e608d039)

- 「決済」を選択し、BPM Paymentの「管理」を選択します。

![スクリーンショット 2023-11-21 12 38 19](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/93211488-fb3d-4223-a559-34dd19e17712)

- 設定画面が開くので、「Endpoint URL」「API_TOKEN」「API_SECRET」「対応カードブランド」を設定します。

| 設定項目 | 設定内容 |
|:----|:----|
| 支払い名称 | 支払いページに表示されるタイトルになります。 |
| Endpoint URL | `https://payment.bpmc.jp/gateway/[API_TOKEN]/payment` <br>`[API_TOKEN]`は弊社から発行された値に置換してください |
| API Token | 弊社から発行された値を入力してください。 |
| API Secret | 弊社から発行された値を入力してください。 |
| 利用可能なブランド | 利用可能なカードブランドにチェックをつけてください（契約時に弊社からお知らせいたします） |
| ログ出力をする | 開発者向けの項目になります。詳しくは6.1に記載します。 |

![スクリーンショット 2023-11-21 12 38 25](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/8e43240d-5560-4d87-8fdd-e247441d88da)


## 5.5．実際の決済フォームの説明
- 購入画面で「BPM Payment」を選択します。

![スクリーンショット 2023-11-21 16 58 29](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/1e763ca7-7e2c-4247-949b-b2184774d0d7)

- 必要な項目を入力し、決済します。

![スクリーンショット 2023-11-21 16 58 45](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/b3800260-6da9-4dbc-b674-6f00f0aae5fb)

## 5.6．オーダー画面の確認方法
- 左側のメニューバーの「woocommerce」から「設定」を選択します。

![スクリーンショット 2023-11-21 17 05 53](https://github.com/bpmc-tech/woocommerce-bpm-payment/assets/138442046/a1ec36a3-cb1c-43cf-a457-d53a904afb70)

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
