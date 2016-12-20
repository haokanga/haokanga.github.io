---
layout: post
title:  "Java Pitfalls-Double Calculations"
date:   2016-11-26 20:21:56 +1030
categories: Java
tags: [Java]
---
Not all decimals can be represented exactly using binary floating-point, use BigDecimal instead.
<!--summary break-->

### Naive solution

{% highlight java %}
System.out.println(2.00 - 1.10);
{% endhighlight %}

It prints 0.8999999999999999. 1.1 can't be represented exactly as a double, so it is represented by the closest double value. The program subtracts this value from 2. Unfortunately, the result of this calculation is not the closest double value to 0.9. Binary floating-point is ill-suited to monetary calculations, as it can not represent any negative power of 10.

### Poor solution

{% highlight java %}
System.out.printf("%.2f%n", 2.00 - 1.10);
{% endhighlight %}
It still uses double arithmetic and you are only rounding it and try dodging the problem.

### Correct solution
Use BigDecimal instead, which performs exact decimal arithmetic. It also interoperates with the SQL DECIMAL type via JDBC. There is one caveat: Always use the BigDecimal(String) constructor, never BigDecimal(double).
{% highlight java %}
System.out.println(new BigDecimal("2.00").subtract(new BigDecimal("1.10")));
{% endhighlight %}

### Another flawed solution
{% highlight java %}
System.out.println(new BigDecimal(2.00).subtract(new BigDecimal(1.10)));
{% endhighlight %}
It prints 0.899999999999999911182158029987476766109466552734375.


In summary, avoid float and double where exact answers are required; for monetary calculations, use `int`, `long`, or `BigDecimal`.