--- 
layout: post_page
title: "Performance Tuning-The database is always the bottleneck" 
date: 2016-7-6 18:50:56 +1030 
categories: PerformanceTuning 
tags: [PerformanceTuning] 
---	
> If the database is the bottleneck (and here’s a hint: it is), then tuning the Java application accessing the database won’t help overall performance at all.
<!--summary break-->

If you are developing standalone Java applications that use no external resources, the performance of that application is (mostly) all that matters. Once an external resource (e.g. a database) is added, then the performance of both programs is important. And in **a distributed environment**, say with **a Java EE application server, a load balancer, a database, and a backend enterprise information system** (e.g. 15619 team project), the performance of the Java application server may be the **least** of the performance issues.

If the database is the bottleneck (and here’s a hint: it is), then tuning the Java application accessing the database won’t help overall performance at all. If something is changed in the Java application that makes itself more efficient — which only increases the load on an already-overloaded database — overall performance
may actually go down.

This principle that increasing load to a component in a system that is already performing badly will make the entire system slower—isn’t confined to a database. It applies when load is added to an application server that is CPU-bound, or if more threads start accessing a lock that already has threads waiting for it, or any of a number of other scenarios.