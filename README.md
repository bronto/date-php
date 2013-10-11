# bronto/Date #

bronto/Date's goal is provide an intuitive and easy to use API for date/datetime work in PHP. Dates, datetimes, time zones and timestamps can be confusing and difficult to work with, so it is important for any API dealing with these topics to be clear and well documented. bronto/Date's api is modeled after [Joda-Time](http://www.joda.org/joda-time/) in java and adapted for PHP.

## Prerequisites ##

bronto/Date requires PHP 5.3.0. This library has been tested with PHP versions 5.3.6 and 5.3.23, although it may work fine with earlier versions. bronto/Date uses PHP's \DateTime and \DateTimeZone classes under the hood and is vulnerable to some of those classes' bugs.

The unit tests have been run with PHPUnit 3.7.9 and Mockery 0.8.0, but earlier versions may work.

## Known Issues / Workarounds ##

bronto/Date uses PHP's \DateTime and \DateTimeZone classes under the hood and is vulnerable to some of those classes' bugs. bronto/Date works around the following bugs/deficiencies:

* "getTimestamp() affected by setTimezone() on DST transition" (https://bugs.php.net/bug.php?id=63459, https://bugs.php.net/bug.php?id=63442) - See section [1.2 Timestamp corruption] in \Bronto\Date\DateTime class documentation for details on this issue.
* "microseconds are missing in DateTime class" (https://bugs.php.net/bug.php?id=52514) - bronto/Date keeps track of microseconds explicitly.

## Future Work ##

* Locale support - this would allow for parse and toString to understand and output other languages. Ideally, this would allow for ambiguous time zone abbreviations to be resolved reliably.
* Date, time, duration, interval and period classes
