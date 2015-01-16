Adult.Midnight [![Build Status](https://travis-ci.org/app2641/AdultMidnight.svg?branch=develop)](https://travis-ci.org/app2641/AdultMidnight) [![Coverage Status](https://coveralls.io/repos/app2641/AdultMidnight/badge.png?branch=develop)](https://coveralls.io/r/app2641/AdultMidnight?branch=develop)
=======

## Description

AdultMidnight は、アダルトサイトを巡回し、その日に更新されたコンテンツを自動でまとめるツールです。

## Requirement

PHP >= 5.4

## Usage

Crawl コマンドを実行するのみです。  
ロボットがクロールを始め、コンテンツ収集後 public_html/index.html を更新します。  
バーチャルホストなどでコンテンツを閲覧出来ます。  

```
$ bin/midnight Crawl
```

## Install

Composer で必要なライブラリを取得するのみです。

```
$ composer.phar install
```

## How to use Aws?

AdultMidnight は Aws でコンテンツを公開出来る機能を備えています。  
仕組みは簡単で、クローラーが集めたコンテンツを S3 で静的ページとして公開しているのみです。  


まず、Aws 用の設定ファイルを生成します。

```
$ cp data/config/aws.ini.orig data/config/aws.ini
```

```
key=your_aws_access_key
secret=your_aws_secret_key
bucket=s3_bucket_name
```

必要なリソース(cssやimgなど)を S3 に同期します。

```
$ bin/midnight S3sync public_html/
```

同期が完了したら適当な EC2 インスタンスを立ち上げ、 Crawl コマンドを実行します。  
EC2 上で収集したコンテンツは S3 に自動的に保存されます。  
EC2 以外の場所で実行しても S3 には保存されません。

```
$ bin/midnight Crawl
```

なお、定期的にクロールしたい場合は Aws のオートスケールを使うのがベストです。  
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


