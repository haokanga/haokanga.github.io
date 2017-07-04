--- 
layout: post
title: "Java-Stream is consumable"
date: 2017-5-11 14:50:56 -0500
categories: Java
tags: [Java]
---	

> **TL;DR** You can not reuse a stream. 
<!--summary break-->

### Stream is consumable

`java.util.stream.Stream` is consumable. The elements of a stream are only visited once during the life of a stream. Like an Iterator, a new stream must be generated to revisit the same elements of the source. As soon as you call any terminal operation the stream is closed. A stream implementation may throw IllegalStateException if it detects that the stream is being reused.

{% highlight java %}
Stream<String> stream = Files.lines(Paths.get(INPUT), StandardCharsets.UTF_8);

stream.anyMatch(s -> true);
stream.noneMatch(s -> true);   // java.lang.IllegalStateException: stream has already been operated upon or closed
{% endhighlight %}

If you want to operate on the same data repeatedly, either store it, or structure your operations as Consumers to consume the stream at the same time.

### Multiple consumers
{% highlight java %}
stream.forEach(e -> { consumerA(e); consumerB(e); });
{% endhighlight %}

### Store the stream using Supplier
`java.util.function.Supplier` is a functional interface and can therefore be used as the assignment target for a lambda expression or method reference.

{% highlight java %}
Supplier<Stream<String>> streamSupplier =
    () -> Files.lines(Paths.get(INPUT), StandardCharsets.UTF_8);

streamSupplier.get().anyMatch(e -> true);
streamSupplier.get().noneMatch(e -> true);
{% endhighlight %}

Each call to `get()` constructs a new stream.

### Stream is not iterable

Iterable implies reusability, whereas Stream is something that can only be used once.
    
If Stream extended Iterable then it will throw an Exception the second time when using `for (element : iterable)`.

