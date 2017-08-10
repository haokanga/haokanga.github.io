--- 
layout: post
title: "Java-Dependency Injection with Spring Framework"
date: 2017-8-10 12:50:56 -0500
categories: Java
tags: [Java]
---	
> The basics to adopt dependency injection with Spring Framework with XML.
<!--summary break-->

#### Samples to show the advantages with Dependency Injection

Given a drawing application:

A solution with fixed dependencies goes as follows:
{% highlight java%}
// In application class
Triangle triangle = new Triangle();
triangle.draw();
Circle circle = new Circle();
circle.draw();
{% endhighlight %}

To decouple the class dependencies, the first step is to use polymorphism:
{% highlight java%}
// Triangle extends Shape
// Circle extends Shape

// In application class
Shape shape = new Triangle();
shape.draw();
Shape shape = new Circle();
shape.draw();
{% endhighlight %}

The next step is to convert the class depenceny to a class member variable:
{% highlight java%}
class Drawing {
    @Setter
    private Shape shape;

    public drawShape() {
        this.shape.draw();
    }
}
{% endhighlight %}

With the `Drawing` class, if there is yet-another subclass of `Shape`, you can easily adopt it without pain.

{% highlight java %}
// In application class
Drawing drawing = new Drawing();
// the param can change as you wish
drawing.setShape(new Circle());
drawing.drawShape();
{% endhighlight %}

The `Drawing` class doe not own the dependency of the actual `Shape` class. The concerns of `Shape` is fully separated from the `Drawing` class.

This is the manual way of dependecy injection. Spring Framework can make DI much easier.

#### Spring Bean Factory

Bean aims to create reusable software components for Java. It is named after coffee beans (which can be contained in jar).

Spring is a container of Beans, which creates the Beans objects, thus aware of the existence of the objects and capable of managing the lifecycle of them.

Instead of calling the constructors by your own, you will ask the Spring container to create and pass the objects to you. Following the factory pattern, Bean Factory will refer to the configuration, e.g. Spring XML to generate the object.

#### Configure Beans using XML

{% highlight java %}
// In application class
BeanFactory factory = new XmlBeanFactory(new FileSystemResource("spring.xml"));
{% endhighlight %}

The factory will refer to the xml file.

{% highlight xml %}
<bean id="triangle" class="package.Triangle" />
{% endhighlight %}

Now the factory can create the bean.
Note that:
* The casting is needed.
* There is no more constructor calling.

{% highlight java %}
// In application class
Triangle triangle = (Triangle) factory.getBean("triangle");
triangle.draw();
{% endhighlight %}

#### Property Initialization

We can set the values of the properties of the objects via setter.
{% highlight java %}
class Triangle {
    @Setter @Getter
    private String type;

    public draw() {
        ...
    }
}
{% endhighlight %}

{% highlight xml %}
<bean id="triangle" class="package.Triangle">
    <property name="type" value="Equilateral"/>
</bean>
{% endhighlight %}

PropertyPlaceholderConfigurer can help you organize the values of the properties in a specified property file, e.g.

{% highlight xml %}
<bean id="triangle" class="package.Triangle">
    <property name="type" value="${triangle.value}"/>
</bean>

<bean class="org.springframework.beans.factory.PropertyPlaceholderConfigurer">
    <property name="locations" value="values.properties"/>
</bean>
{% endhighlight %}

#### Constructor Injection

As an alternative of the setter, you can also set the values by passing them as constructor args.

{% highlight java %}
class Triangle {
    //-   @Setter @Getter
    private String type;

    public Triangle(String type) {
        this.type = type;
    }
}
{% endhighlight %}

{% highlight xml %}
-   <property name="type" value="Equilateral"/>
+   <constructor-arg value="Equilateral"/>
{% endhighlight %}

In case of constructor overloading, you can specify which constructor is called.

{% highlight java %}
class Triangle {
    private String type;
    private int height;

    public Triangle(String type) {
        this.type = type;
    }

    public Triangle(int height) {
        this.height = height;
    }
}
{% endhighlight %}

{% highlight xml %}
-   <constructor-arg value="20"/>
+   <constructor-arg type="int" value="20"/>
{% endhighlight %}

Indices can also be used.
{% highlight xml %}
    <constructor-arg index="0" value="Equilateral"/>
    <constructor-arg index="1" value="20"/>
{% endhighlight %}

#### Injecting Objects

{% highlight java %}
class Triangle {
    @Setter
    private Point pointA, pointB, pointC;
}
{% endhighlight %}

{% highlight xml %}
<bean id="triangle" class="package.Triangle">
    <property name="pointA" ref="zeroPoint"/>
</bean>
<bean id="zeroPoint" class="package.Point">
    <property name="x" value="0"/>
    <property name="y" value="0"/>
</bean>
{% endhighlight %}

You can also use inner Beans:
{% highlight xml %}
<bean id="triangle" class="package.Triangle">
    <property name="pointA">
        <bean class="package.Point">
            <property name="x" value="0"/>
            <property name="y" value="0"/>
        </bean>
    </property>
</bean>
{% endhighlight %}

#### Alias

You can use multiple names to refer to the same bean.

{% highlight xml %}
<alias name="triangle" alias="triangle-alias"/>
<bean id="triangle" class="package.Triangle" name="triangle-alias"/>
{% endhighlight %}

Id is preferred as reference in most cases due to its uniqueness. The `ref` can be forced to only search id by using `idref`:

{% highlight xml %}
<alias name="triangle" alias="triangle-alias"/>
    <property name="pointA">
        <idref="zeroPoint"/>
    </property>
{% endhighlight %}

#### Autowiring

Autowiring will save us from some configuration and Spring will "smartly guess" the dependencies.

If the name of the Bean is the same as the variable names:
{% highlight java %}
class Triangle {
    @Setter
    private Point pointA, pointB, pointC;
}
{% endhighlight %}

{% highlight xml %}
<bean id="triangle" class="package.Triangle" autowire="byName"/>
<bean id="pointA" class="package.Point">
    <property name="x" value="0"/>
    <property name="y" value="0"/>
</bean>
{% endhighlight %}

If there is only one bean with specified class, autowire with "byType" can work as well.

autowire with "constructor" shares similar behavior as "byType" but it is via constructor instead of setter.

#### Eager Initialization

By default, the Bean objects are created when `ApplicationContext` is created referring to the configuration, not when the `getBean()` is invoked.

#### Basic Bean Scopes

Two major Bean scopes:

* Singleton: the default type, only one per Spring container. All the singletons are initialized and ready before requested. It is different from a Gang of Four singleton which is a JVM singleton thus brittle. You can have multiple Spring singletons in the same JVM.
* Prototype: new bean will be created every reference or request.

{% highlight xml %}
<bean id="triangle" class="package.Triangle" scope="singleton"/>
{% endhighlight %}

#### Bean Definition Inheritance

{% highlight xml %}
<bean id="parentTriangle" class="package.Triangle">
    <property name="pointA" ref="pointA"/>
</bean>
<bean id="triangleA" class="package.Triangle" parent="parentTriangle">
    <property name="pointB" ref="pointB"/>
</bean>
<bean id="triangleB" class="package.Triangle" parent="parentTriangle">
    <property name="pointB" ref="pointB"/>
    <property name="pointC" ref="pointC"/>
</bean>

{% endhighlight %}

#### Useful Interfaces for Beans

Beans can run methods on initialization and destruction by implementing InitializingBean and DisposableBean, configuring the `init-method`/`destroy-method` of the bean tag, or configuring the `default-init-method`/`default-destroy-method` of the global beans tag. If both the interface implementation and bean configuration exist, both of the methods will execute and the method of the interface will execute first.

Beans can get access to the ApplicationContext object by implementing the ApplicationContextAware interface. Beans can get the name of the bean configured in the Spring XML using BeanNameAware interface.

#### BeanPostProcessor

`BeanPostProcessor` method will call once with each bean initialized before/after the initialization.

In the configuration XML, just declare the processor as a Bean so that Spring is aware of it.

#### Coding to Interface

{% highlight java %}
// Triangle implements Shape
// Circle implements Shape
// in the application class
Shape shape = (Shape) context.getBean("shape");
shape.draw();
{% endhighlight %}

The advantages are obvious. For example, when there is a new implementation of the shape, you only need to change the configuration with the name of "shape" without even changing the source code of the application class.
