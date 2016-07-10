--- 
layout: post
title: "Performance Tuning-CPU Usage" 
date: 2016-7-7 18:50:56 +1030 
categories: PerformanceTuning 
tags: [PerformanceTuning] 
---	

> The goal in performance is to drive CPU usage **as high as possible** for as **short a time as possible**.
<!--summary break-->

`vmstat` will show % of the time executing user code, % f the time executing system code, and % of idle time.
CPU can be idle for a number of reasons:

* Be blocked on a **synchronization** primitive and unable to execute till the lock is released
* Waiting for something, such as response to come back from a call to the **database**
* The application do have nothing to do at all

