---
layout: post
title:  "Java Pitfalls-Silent Overflow & Explicit Casting"
date:   2016-11-26 23:21:56 +1030
categories: Java
tags: [Java]
---
Add explicit casting in your calculations with long/double to prevent implicit int operations. Be REALLY careful when you add integers when writing algorithms.
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

### Overflow in Binary Search
Joshua Bloch mentioned in Google Research Blog that nearly all binary searches and mergesorts are broken for decades.
This was once the code in `java.util.Arrays`
{% highlight java%}
1:     public static int binarySearch(int[] a, int key) {
2:         int low = 0;
3:         int high = a.length - 1;
4:
5:         while (low <= high) {
6:             int mid = (low + high) / 2;
7:             int midVal = a[mid];
8:
9:             if (midVal < key)
10:                 low = mid + 1
11:             else if (midVal > key)
12:                 high = mid - 1;
13:             else
14:                 return mid; // key found
15:         }
16:         return -(low + 1);  // key not found.
17:     }
{% endhighlight %}
`int mid = (low + high) / 2;` fails if the sum of low and high is greater than the maximum positive int value (231 - 1). The sum overflows to a negative value, and the value stays negative when divided by two. In C this causes an array index out of bounds with unpredictable results. In Java, it throws ArrayIndexOutOfBoundsException.

One way to fix the bug:
`int mid = low + ((high - low) / 2);`
Probably faster, and arguably as clear is:

`int mid = (low + high) >>> 1;`
This one is also used in Java source code.
