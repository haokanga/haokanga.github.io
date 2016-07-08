--- 
layout: post_page
title: "Performance Tuning-The database is always the bottleneck" 
date: 2016-7-6 18:50:56 +1030 
categories: PerformanceTuning 
tags: [PerformanceTuning] 
---	
> If the database is the bottleneck (and here’s a hint for CMU 15619: it is), then tuning the Java code itself won’t help overall performance at all.
<!--summary break-->

#### What should you do if you get stuck with 619 project

If you are developing standalone Java applications that use no external resources, the performance of that application is (mostly) all that matters. Once an external resource (e.g. a database) is added, then the performance of both programs is important. And in **a distributed environment**, say with **a Java application server, a load balancer, a database** (e.g. CMU 15619 Cloud Computing project), the performance of the Java application server itself may be the **least** of the performance issues.

If the database is the bottleneck, when something is changed in the Java application that makes itself more efficient — which only increases the load on an already-overloaded database — overall performance may actually go down.

This principle that **increasing load to a component in a system that is already performing badly will make the entire system slower** — isn’t confined to a database. It applies when load is added to an application server that is CPU-bound, or if more threads start accessing a lock that already has threads waiting for it, or any of a number of other scenarios.

Applications that access a database are subject to non-Java performance issues: if a database is **I/O-bound**, or if it is executing **SQL queries that require full table scans because an index is missing**, no amount of Java tuning or application coding is going to solve the performance issues. 

#### Prepared Statements (VITAL)
In most circumstances, code should **use a `PreparedStatement` rather than a `Statement`** for its JDBC calls. The difference is that prepared statements allow the database to reuse information about the SQL that is being executed. That saves work for the database on subsequent executions of the prepared statement.

#### MySQL db engine (VITAL)
`InnoDB` is designed for complex relations with ACID and foreign key support, while `MyISAM` is lightweight and full-text indexed without foreign key involved. `MyISAM` is especially good for **single-table read-intensive** case. `InnoDB` supports high-database-concurrency and it is the best choice against highly **concurrent read-write** operations.

#### Pre-sort data (VITAL)
Before you load data into table, consider sorting data based on the columns you used in `WHERE` clause. The boost can be significant because latency difference between random I/O and sequential I/O is huge.
{% highlight sql %}
{% include sql/presort-example.sql %}
{% endhighlight %}



 
 
 

