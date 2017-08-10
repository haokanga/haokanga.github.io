--- 
layout: post
title: "Java-Global State and Singletons"
date: 2017-8-9 18:50:56 -0500
categories: Java
tags: [Java]
---	
> **TL;DR** Don't use Gang of Four singletons for global state.
<!--summary break-->

Warning Signs:
* Adding or using singletons
* Adding or using static fields or static methods
* Adding or using static initialization blocks

It is recommended to break the hard-coded dependency using dependency injection using libraries.

There is a distinction between global as in “JVM Global State” and global as in “Application Shared State.”

JVM Global State occurs when the `static` keyword is used to make accessible a field or a method that returns a shared object. The use of static in order to facilitate shared state is the problem. Because static is enforced as One Per JVM, parallelizing and isolating tests becomes a huge problem. From a maintenance point of view, static fields create coupling, hidden colaborators and APIs which lie about their true dependencies. Static access is the root of the problem.

Application Shared State simply means the same instance is shared in multiple places throughout the code. There may or may not be multiple instances of a class instantiated at any one time, depending on whether application logic enforces uniqueness. The shared state is not accessed globally through the use of static. It is not shared state in and of itself that is a problem. There are places in an application that need access to shared state. It’s sharing that state through statics that causes brittle code and difficulty for testing.