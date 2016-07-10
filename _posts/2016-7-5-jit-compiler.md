--- 
layout: post
title: "Java-JIT compiler tuning" 
date: 2016-7-5 18:50:56 +1030 
categories: PerformanceTuning
tags: [Java, PerformanceTuning] 
---
The simple choice here is to **use the server compiler with tiered compilation for virtually everything**, just make sure that the code cache is sized large enough.
<!--summary break-->

#### Compiled language & Interpreted language
A **compiled language** is one where the program, once compiled, is expressed in the **native instructions** of target machine, such as C++. An **interpreted language** is one where the instructions are read and executed by an **interpreter** (which is normally written in the language of native machine). 

Generally speaking, the advantages of compiled languages are:

* **Faster performance** by directly using the native code of the target machine
* Opportunity to apply quite powerful optimizations during the compile stage
 
Meanwhile, advantages of interpreted languages are:
  
* Portable and easier to implement
* No need to run a compilation stage: can **execute code directly** "on the fly"
* Can be more convenient for dynamic languages
 
Java attempts to find a balance here. Java code is compiled into Java bytecodes and `java` binary will run them just like `php` binary interprets PHP script. 

 
#### Hot Spot Compilation
The **just-in-time(JIT) compiler** is the heart of JVM. Nothing in the JVM affects performance more than the compiler. The Java **HotSpot** Virtual Machine is a core component of the Java SE platform. In a typical program, only a small subset of code is executed frequently and the performance of the whole application depends primarily on how fast those sections of code are executed. These critical sections are known as the hot spots.

Hence, when the JVM executes code, it does not begin compiling the code immediately. There are two basic reasons for this. 

First, if the code is going to be executed only once, then compiling it is essentially a wasted effort; it will be faster to interpret the Java bytecodes than to compile them and execute (only once) the compiled code.

But if the code in question is a frequently called method, or a loop that runs many iterations, then compiling it is worthwhile: the cycles it takes to compile the code will be outweighed by the savings in multiple executions of the faster compiled code. That trade-off is one reason that the compiler executes the interpreted code first—the compiler can figure out which methods are called frequently enough to warrant their compilation.

The second reason is one of optimization: the more times that the JVM executes a particular method or loop, the more information it has about that code. This allows the JVM to make a number of optimizations when it compiles the code.

`flag = obj1.equals(obj2)` 

For example, `equals()` method exists in every Java object and is often overridden. The interpreter must look up the class of `obj1` in order to know which `equals()` method to execute. This dynamic lookup can be somewhat time-consuming.

Over time, say that the JVM notices that each time this statement is executed, `obj1` is of type `java.lang.String`. Then the JVM can produce compiled code that directly calls the `String.equals()` method. Now the code is faster because it can skip the lookup of which method to call.

* Java is designed to take advantage of the platform independence of scripting languages and the native performance of compiled languages.
* A Java class file is compiled into an intermediate language (Java bytecodes) that is then further compiled into assembly language by the JVM.
* Compilation of the bytecodes into assembly can greatly improve performance.

#### Registers & Main Memory
One of the most important optimizations a compiler can make involves when to use values from main memory and when to store values in a register. Retrieving values from main memory is an expensive operation.That kind of optimization can only be made after running the code for a while and observing what it does: this is the second reason why JIT compilers wait to compile sections of code.

{% highlight java %}
{% include java/RegisterTest.java %}
{% endhighlight %}

If the value of sum were to be retrieved from (and stored back to) main memory on every iteration of this loop, performance would be dismal. Instead, the compiler will load a register with the initial value of sum, perform the loop using that value in the register, and then (at an indeterminate point in time) store the final result from the register back to main memory.
This optimization is effective, but thread synchronization are crucial if so. One thread cannot set the value of a variable stored in the register by another thread.

#### Client Compiler & Server Compiler
The primary difference between the two compilers is their aggressiveness in compiling code. The client compiler begins compiling sooner than the server compiler does but leads to less optimized compilation. This means that during the beginning of code execution, the client compiler will be faster and client compiler has a shorter startup time, while we should use server compiler with huge batch operations or long-running applications. 

The obvious question here is why there needs to be a choice at all: couldn’t the JVM start with the client compiler, and then use the server compiler as code gets hotter? That technique is known as tiered compilation. With tiered compilation, code is first compiled by the client compiler; as it becomes hot, it is recompiled by the server compiler.

In Java 7, tiered compilation has a few quirks, and so it is not the default setting. To use tiered compilation, specify the server compiler (either with -server or by ensuring it is the default for the particular Java installation being used), and ensure that the Java command line includes the flag -XX:+TieredCompilation (the default value of which is false). In Java 8, tiered compilation is enabled by default. Tiered compilation still has a longer startup time than client compiler, but is expected to have best performance with huge batch or long-running apps.

<img src="https://cmudream.files.wordpress.com/2016/07/throughput_of_server_applications.png" width="396" height="130" />

Client-class machines are any 32-bit JVM running on Microsoft Windows (regardless of the number of CPUs on the machine), and any 32-bit JVM running on a machine with one CPU (regardless of the operating system). All other machines (including all 64-bit JVMs) are considered server class.

#### Inlining
In the early days of Java, performance tips often argued against encapsulation (e.g. setters and getters) precisely because of the performance impact of all those method calls.
{% highlight java %}
Point p = getPoint();
p.x = p.x * 2;
{% endhighlight %}
is faster than
{% highlight java %}
Point p = getPoint();
p.setX(p.getX() * 2);
{% endhighlight %}
Nowadays, however, JVMs routinely perform code inlining for these kinds of methods, and these two snippets are actually the same thanks to the compiler.

#### Escape Analysis
The server compiler performs some very aggressive optimizations if escape analysis is enabled (-XX:+DoEscapeAnalysis, which is true by default). For example, consider this class to work with factorials:

{% highlight java %}
{% include java/Factorial.java %}
{% endhighlight %}

The factorial object is referenced only inside that loop; no other code can ever access that object. Hence, the JVM is free to perform a number of optimizations on that object:

* No need to get a synchronization lock when calling the getFactorial() method.
* No need to store the field n in memory; it can keep that value in a register. Similarly
it can store the factorial object reference in a register.
* No need to allocate an actual factorial object at all; it can just keep track of the individual fields of the object.

Escape analysis is the most sophisticated of the optimizations the compiler can perform and it can often introduce “bugs” into improperly synchronized code.

#### "final" keyword

It is a persistent rumor that `final` keyword can improve Java performance a lot, but it has not been true for many, many years (if it ever was). You should use `final` whenever it makes sense: for an immutable object or primitive value you don’t want to change, for parameters to certain inner classes, and so on. But the presence or absence of the final keyword will not affect the performance of an application.

#### Summary

* The simple choice here is to **use the server compiler with tiered compilation for virtually everything**, just make sure that the code cache is sized large enough.
* Don't be afraid of small methods, especially setters and getters, because they are easily inlined by the compiler.
* The simpler the code, the more optimizations that can be performed on it, but complex loop structures and large methods limit their effectiveness.



