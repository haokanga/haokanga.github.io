--- 
layout: post
title: "Java-Dependency Injection"
date: 2017-8-9 21:50:56 -0500
categories: Java
tags: [Java]
---	
> Dependency Injection is your Friend.
<!--summary break-->

Dependency injection is a technique whereby one object supplies the dependencies of another object. A dependency is an object that can be used (a service). An injection is the passing of a dependency to a dependent object (a client) that would use it. The service is made part of the client's **state**. Passing the service to the client, rather than allowing a client to build or find the service, is the fundamental requirement of the pattern.

The general concept behind dependency injection is called Inversion of Control. According to this concept a class should not configure its dependencies statically but should be configured from the outside.

A dependency container could analyze the dependencies of the class and create an instance of the class and inject the objects into the defined dependencies via Java reflection.