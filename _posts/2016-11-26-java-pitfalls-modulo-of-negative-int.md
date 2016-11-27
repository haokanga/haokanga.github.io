---
layout: post
title:  "Java Pitfalls-Modulo Of Negative Int"
date:   2016-11-26 21:21:56 +1030
categories: Java
tags: [Java]
---
When the remainder operation returns a nonzero result, it has the same sign as its left operand.
<!--summary break-->

### Naive solution

{% highlight java%}
public static boolean isOdd(int i) {
    return i % 2 == 1;
}
{% endhighlight %}

It fails with negative odd numbers.
The definition of Java's remainder operator (%) is as follows:
{% highlight java%}
(a / b) * b + (a % b) == a
{% endhighlight %}
When the remainder operation returns a nonzero result, it has the same sign as its left operand.
When i is a negative odd number, i % 2 is equal to -1 rather than 1.

### Correct solution

{% highlight java%}
public static boolean isOdd(int i) {
    return i % 2 != 0;
}
{% endhighlight %}

### Efficient solution

Modulo operation is wildly slow in CPU. The bitwise AND operator (&) is much faster:
{% highlight java%}
public static boolean isOdd(int i) {
    return (i & 1) != 0;
}
{% endhighlight %}