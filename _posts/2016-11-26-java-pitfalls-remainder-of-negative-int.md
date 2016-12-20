---
layout: post
title:  "Java Pitfalls-Remainder Of Negative Int"
date:   2016-11-26 21:21:56 +1030
categories: Java
tags: [Java]
---
When the remainder operation (%) returns a nonzero result, it has the same sign as its left operand.
Please note that **% is not modulus** in Java.
<!--summary break-->

### Naive solution

{% highlight java %}
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

### Difference between Remainder and Modulus

-21 mod 4 is 3 because -21 + 4 x 6 is 3.

-21 divided by 4 gives -5 with a remainder of -1.

**In Python, % is modulus operator while Java uses remainder.**

In Java, there is always:

`(a / b) * b + (a % b) == a`

### Correct solution

{% highlight java%}
public static boolean isOdd(int i) {
    return i % 2 != 0;
}
{% endhighlight %}

### Efficient solution

Remainder/Modulus operation is wildly slow in CPU. The bitwise AND operator (&) is much faster:
{% highlight java%}
public static boolean isOdd(int i) {
    return (i & 1) != 0;
}
{% endhighlight %}