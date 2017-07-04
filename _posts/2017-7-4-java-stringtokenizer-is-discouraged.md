--- 
layout: post
title: "Java-StringTokenizer is discouraged"
date: 2017-7-4 14:50:56 -0500
categories: Java
tags: [Java]
---	
> The behavior of StringTokenizer can be different from String.split(regex, n). 
<!--summary break-->

StringTokenizer is a legacy class that is retained for compatibility reasons although its use is discouraged in new code. It is recommended that anyone seeking this functionality use the split method of String or the java.util.regex package instead. 
{% highlight java %}
String s = "ABC|DEF||GH";
String[] r = s.split("\\|");
System.out.println(r.length); // 4
System.out.println(Arrays.toString(r)); // [ABC, DEF, , GH]
StringTokenizer tokenizer = new StringTokenizer(s, "|");
System.out.println(tokenizer.countTokens()); // 3
// ABC
// DEF
// GH
while (tokenizer.hasMoreElements()) {
    System.out.println(tokenizer.nextElement());
}
{% endhighlight %}