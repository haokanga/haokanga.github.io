--- 
layout: post
title: "Performance Tuning-Premature Optimization" 
date: 2016-7-4 18:50:56 +1030 
categories: PerformanceTuning 
tags: [PerformanceTuning] 
---	

> Premature optimization is the root of all evil -- DonaldKnuth
<!--summary break-->

#### Premature optimization
In DonaldKnuth's paper, he wrote: "Programmers waste enormous amounts of time thinking about, or worrying about, the speed of noncritical parts of their programs, and these attempts at efficiency actually have a strong negative impact when debugging and maintenance are considered. **We should forget about small efficiencies**, say about 97% of the time: premature optimization is the root of all evil. Yet we should not pass up our opportunities in that critical 3%."

**Premature optimization** is a phrase used to describe a situation where a programmer **lets performance considerations affect the design** of a piece of code. This can result in a design that is not as clean as it could have been or code that is incorrect, because the code is complicated by the optimization and the programmer is distracted by optimizing.

#### Amdahl's law
In computer architecture, [Amdahl's law](https://en.wikipedia.org/wiki/Amdahl%27s_law) gives the theoretical speedup in latency of the execution of a task at fixed workload that can be expected of a system whose resources are improved. It proves that the theoretical speedup is always limited by the part of the task that cannot benefit from the improvement of the resources (e.g. parallel computing).

<img src="//upload.wikimedia.org/wikipedia/commons/thumb/4/40/Optimizing-different-parts.svg/400px-Optimizing-different-parts.svg.png">

Amdahl's law also gives guide for speedup in a serial program. In a task with two independent parts. A(75% time taken in the whole computation) and B(25%). Tuning A can make much more sense than B.

Therefore, when deciding whether to optimize a specific part of the program, Amdahl's Law should always be considered: the impact on the overall program depends very much on how much time is actually spent in that specific part, which is not always clear from looking at the code without a performance analysis.

A better approach is therefore to design first, code from the design and then profile/benchmark the resulting code to see which parts should be optimized. A simple and elegant design is often easier to optimize at this stage, and profiling may reveal unexpected performance problems that would not have been addressed by premature optimization.

In practice, it is often necessary to keep performance goals in mind when first designing software, but the programmer balances the goals of design and optimization.

#### Art of optimization
You should not sacrifice readability for trivial performance improvement. If there are two straightforward ways of programming, you can consider the more efficient one. For example, this code does a string concatenation that is likely not necessary. 
{% highlight java %}
log.log(Level.FINE, "I am here, and the value of X is "
+ calcX() + " and Y is " + calcY());
{% endhighlight %}
If the message is not printed because of low logging level, the concatenation should be avoided. The better choice should be written like this:
{% highlight java %}
if (log.isLoggable(Level.FINE)) {
    log.log(Level.FINE,
        "I am here, and the value of X is {} and Y is {}",
        new Object[]{calcX(), calcY()});
}
{% endhighlight %}
There is no longer string concatenation called unless logging has been enabled. Writing code in this way is still clean to read and it is different from premature optimization. 
