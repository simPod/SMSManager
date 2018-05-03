PHP Client for SmsManager.cz
======================

[![Build Status](https://img.shields.io/travis/simPod/SMSManager/master.svg?style=flat-square)](https://travis-ci.org/simPod/SMSManager)
[![Quality Score](https://img.shields.io/scrutinizer/g/simPod/SMSManager.svg?style=flat-square)](https://scrutinizer-ci.com/g/simPod/SMSManager)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/simPod/SMSManager.svg?style=flat-square)](https://scrutinizer-ci.com/g/simPod/SMSManager)
[![GitHub Issues](https://img.shields.io/github/issues/simPod/SMSManager.svg?style=flat-square)](https://github.com/simPod/SMSManager/issues)

Library for PHP that can send SMS messages via SmsManager.cz gateway. _(not all API methods are implemented for now)_

Installation
------------

```sh
$ composer require simpod/sms-manager
```

Usage
-----

Pass instance of `Sms` to `SmsManager` `sendSms()` method. Instance of `Sms` should include all data like message, message type, receipients or sender.
