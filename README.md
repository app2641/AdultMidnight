Adult.Midnight [![Build Status](https://travis-ci.org/app2641/AdultMidnight.svg?branch=develop)](https://travis-ci.org/app2641/AdultMidnight) [![Coverage Status](https://coveralls.io/repos/app2641/AdultMidnight/badge.png?branch=develop)](https://coveralls.io/r/app2641/AdultMidnight?branch=develop)
=======

## Description

AdultMidnight は、アダルトサイトを巡回し、その日に更新されたコンテンツを自動でまとめるツールです。

## Requirement

PHP >= 5.4

## Usage

Crawl コマンドを実行するのみです。  
ロボットがクロールを始め、コンテンツ生成後 AmazonS3 へ保存をします。

```
$ bin/midnight Crawl
```

## Install

まず、Composer で必要なライブラリを取得します。

```
$ composer.phar install
```

次に Aws への接続設定を aws.ini ファイルに記載します。

```
$ cp data/config/aws.ini.orig data/config/aws.ini
```

```
key=your_aws_access_key
secret=your_aws_secret_key
bucket=s3_bucket_name
```

必要リソース(cssやimgなど)を AmazonS3 に同期します。

```
$ bin/midnight S3sync public_html/
```

同期が完了したら Crawl コマンドを使ってクロールを開始します。  
クロール完了後、収集したコンテンツは AmazonS3 に保存されます。

```
$ bin/midnight Crawl
```

なお、クロールは Aws のオートスケールで行うのがベストです。  
AdultMidnight ではオートスケールの設定を容易に行うコマンドも揃っています。

UpdateAutoScaling コマンドでは指定した AMI-ID で AutoScalingGroup を更新します。  
その際、グループ名は ModernAdultMidnightCrawlerGroup となります。

```
$ bin/midnight UpdateAutoScaling ami-12345678
```

UpdateScheduledAction コマンドではオートスケールの ScheduledAction を更新します。  
ScheduledAction を設定することで任意の時間にオートスケールさせることが可能です。

## Licence

[MIT](https://github.com/app2641/AdultMidnight/blob/master/LICENCE)

## Author

[app2641](https://github.com/app2641)


