--- 
layout: post
title: "Java-Global State and Singletons"
date: 2017-8-9 18:50:56 -0500
categories: Java
tags: [Java]
---	
> **TL;DR** Don't use Gang of Four singletons for global state. Don't use "static" for mutable state.
<!--summary break-->


#### Global state & singletons turn APIs into liars

{% highlight java%}
testCreditCardAtADistance() {
  // to test CreditCard, it is intuitive to simply create an instance of CreditCard
  CreditCard = new CreditCard(id, date);
  // and do the test
  assertTrue(card.charge(amount));
  // but test fails: CreditCardProcessor not set, wait wat?
}
{% endhighlight%}

CreditCard API, e.g. `Credit(String id, String date)` is **lying**, just initializing CreditCard object itself is not enough for the initialization. There are more dependencies you need to set. By looking at the API of CreditCard itself, there is NO way to know the global state you have to initialize. Even looking at the source code of CreditCard will not tell you which initialization method to call. If you have the guts to read the source code in the library fully, you may find the global variable being accessed and try to guess how to initialize it.

{% highlight java%}
testCreditCardAtADistanceWithInitialization() {
  // Forced to read the source code,
  // you find global state is needed to get set up first
  // how can you figure out CreditCardProcessor and Database are needed without
  // reading all the code or bugging the author?
  Database.init(dbUrl, user, pwd);
  // init() here is typical example suing static Global state
  CreditCardProcessor.init(processorUrl, secretKey);

  CreditCard = new CreditCard(id, date);
  assertTrue(card.charge(amount));
}
{% endhighlight%}

Notice how it is much clearer to initialize the dependencies of CreditCard explicitly.

{% highlight java%}
testCreditCardAtADistanceWithInitialization() {
  Database db = new Database(dbUrl, user, pwd);
  // Database is passed as a parameter
  // and you know Database is needed to initialize
  CreditCardProcessor processor = new CreditCardProcessor(db, processorUrl, secretKey);
  // CreditCardProcessor is passed as a parameter
  // and you know CreditCardProcessor is needed to initialize
  CreditCard = new CreditCard(processor, id, date);
  assertTrue(card.charge(amount));
}
{% endhighlight%}

If Credit class depends on CreditCardProcessor, `Credit(String id, String date)` is **lying** and `Credit(CreditCardProcessor processor, String id, String date)` is self-explanatory.

#### Global state creates secret communication channels

As the example shows, `static` state creates secret communication channels hidden from API signature. It forces developers to read every line of code. It is harmful for maintenance, collaboration and scalability.

#### Global state kills polymorphism

Static access prevents collaborating with a subclass or wrapped version of another class. By hard-coding the dependency, the power and flexibility of polymorphism is lost.

#### Global state is harmful for test

Global State in *application runtime* may deceptively "seems okay", because it is not common to instantiate two copies of the same application in a single JVM (and this is when people say, "oh, it is time for singletons").

At *test runtime*, global state becomes the real headache.

At test time, each test is supposed to be an isolated partial instantiation of an application. No external state enters the test (there is no external object passed into the tests constructor or test method). And no state leaves the tests (the test methods are void, returning nothing). When an ideal test completes, all state related to that test disappears. This makes tests isolated and all of the objects it created are subject to garbage collection. In addition the state created is confined to the current thread. This means we can run tests in parallel or in any order.

However, when global state/singletons are present all of these nice assumptions break down. State can enter and leave the test and it is not garbage collected. This makes the order of tests matter. You cannot run the tests in parallel and your tests can become flaky due to thread interactions.

True singletons are most likely impossible to test. As a result most developers who try to test applications with singletons often "relax" the singleton property into two ways.

* They remove the final keyword from the static final declaration of the instance. This allows them to substitute different singletons for different tests.

* They provide a second initializeForTest() method which allows them to modify the singleton state.
 Singletons need hacking mutator methods such as `reset()` or `setForTest()` to make test possible.
 It works, but it produce hard to maintain and understand code.

 Test isolation is nearly impossible if running tests in parallel with singletons, which forces test suites to run slower.

#### JVM singleton v/s Application singleton

There is a distinction between global as in “JVM Global State” (i.e. forced to be unique) and global as in “Application Shared State” (i.e. shared by objects).

The root evil with global state is that it is globally accessible. An object should interact only with other objects which were directly passed into it through a constructor, or method call.

JVM Global State occurs when the `static` keyword is used to make accessible a field or a method that returns a shared object. The use of static in order to facilitate shared state is the problem. Because static is enforced as one per JVM, parallelizing and isolating tests becomes a huge problem. From a maintenance point of view, static fields create coupling, hidden collaborators and APIs which lie about their true dependencies. Static access is the root of the problem. Coupling is bad, implicit coupling is worse.

Application Shared State simply means the same instance is shared in multiple places throughout the code. There may or may not be multiple instances of a class instantiated at any one time, depending on whether application logic enforces uniqueness. The shared state is not accessed globally through the use of static. It is not shared state in and of itself that is a problem. There are places in an application that need access to shared state. It’s sharing that state through statics that causes brittle code and difficulty for testing.

Both Spring and Guice can create application singleton, but multiple singletons can exist in the same JVM to make the application testable.

#### Warning Signs

When you notice the following signs, justify them or correct them:

* Adding or using singletons
* Adding or using static fields or static methods
* Adding or using static initialization blocks

#### Avoid or fix the flaw

> Dependency Injection is your Friend. -- Miško Hevery, Google

If a static is used widely in the codebase,there are [some workaround](http://misko.hevery.com/code-reviewers-guide/flaw-brittle-global-state-singletons/) offered by Miško Hevery. The better choice is to get rid of it at the very beginning. Avoid global state and JVM singletons and use DI instead.

