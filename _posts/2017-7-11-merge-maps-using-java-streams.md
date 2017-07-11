---
layout: post
title: "Java-Merge maps using Java streams"
date: 2017-7-11 10:50:56 -0500
categories: Java
tags: [Java]
---
> Less noise, more elegance. Solve problems in a declarative functional manner if you can.
<!--summary break-->

When we need to merge multiple Map instances into one and handle duplicate keys properly, we can easily make it work in a naive fashion using nested loops and if-else statements. However, Java streams can solve such problems in a way more declarative and graceful fashion.

{% highlight java %}
scores1, scores2 : Map<String, Integer> // maps to merge
{% endhighlight %}

First, combine all maps into a unified Stream using `Stream.concat()` which is an intermediate operation.

{% highlight java %}
Stream.concat(scores1.entrySet().stream(), scores2.entrySet().stream());
{% endhighlight %}

Second, collecting the stream. Collecting is a terminal operation which takes a given stream, applies all the intermediate operations and outputs an instance of a common Java collection, e.g. List, Set, Map, etc. Most common collectors reside in the `java.utils.stream.Collectors` factory class.

The default implementation of `Collectors.toMap()` takes two lambda parameters. It is suitable when we can guarantee that no duplicates exist. If the mapped keys contains duplicates, an IllegalStateException is thrown when the collection operation is performed.

{% highlight java %}
Map<String, Integer> mergedScores = Stream.concat(scores1.entrySet().stream(), scores2.entrySet().stream())
                .collect(Collectors.toMap(
                        entry -> entry.getKey(), // The key
                        entry -> entry.getValue() // The value
                        )
                );
{% endhighlight %}

To handle duplicates, we need to use the other implementation of `Collectors.toMap()` which takes one additional lambda parameters, `BinaryOperator<U> mergeFunction`. You can use set the merge function as either a lambda function or a method reference, but don't get confused and mix them up.

{% highlight java %}
Map<String, Integer> mergedScores = Stream.concat(scores1.entrySet().stream(), scores2.entrySet().stream())
        .collect(Collectors.toMap(
                entry -> entry.getKey(), // The key
                entry -> entry.getValue(), // The value
                // the merge function as a lambda function
                (a, b) -> Math.min(a, b)
                )
        );

Map<String, Integer> mergedScores = Stream.concat(scores1.entrySet().stream(), scores2.entrySet().stream())
        .collect(Collectors.toMap(
                Entry::getKey,
                Entry::getValue,
                // the merge function as a method reference
                Math::min
                )
        );
{% endhighlight %}

Less noise, more elegance.