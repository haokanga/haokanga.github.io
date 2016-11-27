---
layout: post
title:  "Java Pitfalls-Long Division & Silent Overflow"
date:   2016-11-26 23:21:56 +1030
categories: Java
tags: [Java]
---
Add explicit casting in your calculations with long/double to prevent implicit int operations.
<!--summary break-->

### Naive solution

{% highlight java%}
final long MICROS_PER_DAY = 24 * 60 * 60 * 1000 * 1000;
final long MILLIS_PER_DAY = 24 * 60 * 60 * 1000;
System.out.println(MICROS_PER_DAY / MILLIS_PER_DAY);
{% endhighlight %}
Believe it or not, it prints 5. The computation of the constant MICROS_PER_DAY does overflow. Although the
result of the computation fits in a long with room to spare, it doesn't fit in an int. The computation
is performed entirely in `int` arithmetic, because all the factors that are multiplied together are `int` values, and only after the computation completes is the result promoted to a long. By then, it's too late. 

Java does not have target typing, a language feature wherein the type of the variable in which a result is to be
stored influences the type of the computation.

### Correct solution

{% highlight java%}
final long MICROS_PER_DAY = 24L * 60 * 60 * 1000 * 1000;
final long MILLIS_PER_DAY = 24L * 60 * 60 * 1000;
System.out.println(MICROS_PER_DAY / MILLIS_PER_DAY);
{% endhighlight %}

### Float/Double division
Similarly, when you try to get a division from two ints and get a float/double value.
Do not use these:
{% highlight java%}
double ratio = 1 / 2;
double ratio = (double) (1 / 2);
{% endhighlight %}

Use this instead.
{% highlight java%}
ratio = (double) 1 / 2;
{% endhighlight %}