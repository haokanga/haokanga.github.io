---
layout: post
title:  "Java Pitfalls-String Concatenation & Addition Operator"
date:   2016-12-18 21:21:56 +1030
categories: Java
tags: [Java]
---
The + operator performs string concatenation if and only if at least one of its operands is of type String.
<!--summary break-->

### Examples
{% highlight java %}
System.out.println(1 + 2 + "abc"); // print 3abc
// 1 + 2 == 3
// 3 + "abc" == "3abc" 
System.out.println("abc" + 1 + 2); // print abc12
// "abc" + 1 == "abc1"
// "abc1" + 2 == "abc12"
{% endhighlight %}
