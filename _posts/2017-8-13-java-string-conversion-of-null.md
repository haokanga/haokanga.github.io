--- 
layout: post
title: "Java-String Conversion of Null"
date: 2017-8-13 12:50:56 -0500
categories: Java
tags: [Java]
---	
> null + "File" equals "nullFile", no NullPointerException.
<!--summary break-->

#### No NullPointerException

You can never be too careful of null. Recently I run into it again, this time there is no NullPointerException, but it might be more amusing.

{%highlight java%}
// folder = null
// filename = "file"
Files.write(Paths.get(getFolder() + filename),
    line.getBytes(UTF_8), APPEND, CREATE);
// and a file is created, called "nullfile"
{%endhighlight%}

If you look into the bytecode with `javap -c`, you can find the cause:

{%highlight java%}
// String str = null
// str += "file"
str = new StringBuilder(String.valueOf(str)).append("file").toString();
{%endhighlight%}

`String#valueOf(Object x)` will convert null reference to literal "null"

{%highlight java%}
return (obj == null) ? "null" : obj.toString();
{%endhighlight%}

#### Refer to Java Language Specification

Joel Spolsky [criticizes Java as a harmful teaching language](https://www.joelonsoftware.com/2005/12/29/the-perils-of-javaschools-2/). Despite the arguably controversial opinion, it is true that Java frees most programmers from digging into the low-level operations (e.g. it is uncommon to look into Java bytecode to write a Yet Another Java Accounting Application), which can cause crashes where least expected. Programmers are not tempted enough to go through the Java Language Specification before implementing programs, while most pitfalls (e.g. as collected in one of the amusing books written by Joshua Bloch, *Java puzzlers*) hit when they overlook the details of the implementation of Java itself.

According to [JLS 8 § 15.18.1 "String Concatenation Operator +"](https://docs.oracle.com/javase/specs/jls/se8/html/jls-15.html#jls-15.18.1), if only one operand expression is of type String, then string conversion is performed on the other operand to produce a string at run time. String concatenation operator is required to succeed without failure.

As specified in [JLS 8, § 5.1.11 "String Conversion"](https://docs.oracle.com/javase/specs/jls/se8/html/jls-5.html#jls-5.1.11), **any** type may be converted to type String by string conversion.

* A value x of primitive type T: autoboxing occurs. The reference value is then converted to type String by string conversion.
* **If the reference is `null`, it is converted to the string "null" (four ASCII characters n, u, l, l).**
* Otherwise, the conversion is performed as if by an invocation of the toString method of the referenced object with no arguments; but if the result of invoking the toString method is null, **then the string "null" is used instead.**

#### What happens when System.out.println(null)?
{%highlight bash%}
Error: java: reference to println is ambiguous
  both method println(char[]) in java.io.PrintStream and method println(java.lang.String) in java.io.PrintStream match
{%endhighlight%}

Here are 3 overloaded methods that can possibly accept `null`:
{%highlight java%}
public void println(String x){}
public void println(char[] x){}
public void println(Object x){}
{%endhighlight%}

The results are as follows:
{%highlight java%}
System.out.println((String)null); // null
System.out.println((Object)null); // null
System.out.println((char[])null); // NPE
{%endhighlight%}

#### Explanations

There is no magic, everything is plain in the source code.

`println(String x)` will do string conversion:

{%highlight java%}
// public void println(String x){}
// -> PrintStream#print(String x)
if (s == null) {
    s = "null";
}
write(s);
{%endhighlight%}

`println(Object x)` will invoke `String#valueOf(Object x)`:

{%highlight java%}
// public void println(Object x){}
// String s = String.valueOf(x);
// --> String#valueOf(Object x)
return (obj == null) ? "null" : obj.toString();
// PrintStream#print(String x)
{%endhighlight%}

`println(char[] x)` will stay treated as char array till throw NPE:
{%highlight java%}
write(cbuf, 0, cbuf.length); // NPE
{%endhighlight%}