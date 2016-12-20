---
layout: post
title:  "Java Pitfalls-String Equality & Interning"
date:   2016-12-18 21:21:56 +1030
categories: Java
tags: [Java]
---
Java automatically interns String literals and things become tricky.
<!--summary break-->

To save memory and speed up testing for equality, Java supports "interning" of Strings. When the intern() method is invoked, a lookup is performed on a table of interned Strings. If a String object with the same content is already in the table, a reference to the String in the table is returned. Otherwise, the String is added to the table and a reference to it is returned. The result is that after interning, all Strings with the same content will point to **the same object** and `==` will return true.

### A wave of "same" strings
`== aLiteralString` will return true, only if the other string is literal (not object) and known in compile time instead of runtime, or intern() is invoked.

{% highlight java %}
private static char[] chars = 
        {'A', ' ', 'S', 't', 'r', 'i', 'n', 'g'};

public static void main(String[] args) {
    // (0) base case: String literal
    String aString = "A String";
    
    // (1) case 1, construct a String by concatenating several literals. 
    // Note, however, that all parts of the string are known at compile time.
    String aCompileTimeConcatString = "A" + " " + "String";
    // aString == aCompileTimeConcatString ==> true
    
    // (2) case 2, construct the same String, but in a way such that it's contents cannot be known until runtime.
    String aRuntimeString = new String(chars);
    // aString == aRuntimeString ==> false
    
    // (3) case 3, create a String object by invoking the intern() method.
    String anInternedString = aRuntimeString.intern();
    // aString == anInternedString ==> true
    
    // (4) case 4, we explicitly construct String object around a literal. 
    String anStringObject = new String("A String");
    // aString == anStringObject ==> false
    
    // (5) case 5, another runtime string
    String firstArg = args[0];
    // aString == firstArg ==> false
    // (6) case 6, another interned string
    String firstArgInterned = firstArg.intern();
    // aString == firstArgInterned ==> true
}
{% endhighlight %}


### Java Puzzler
Given these rules, we can solve this java puzzler. `pig.length()` is not a constant expression and string interning is not used.
{% highlight java %}
public class AnimalFarm {
    public static void main(String[] args) {
        final String pig = "length: 10";
        final String dog = "length: " + pig.length();
        System.out.println("Animals are the same: "
            + pig == dog);
    }
}
{% endhighlight %}
If so, it should print `Animals are the same: false`, right?
No, because it is evaluated like this `System.out.println(("Animals are the same: " + pig) == dog);` and it prints only `false` itself.

When using the string concatenation operator, always parenthesize nontrivial operands.

`System.out.println("Animals are the same: " + (pig == dog));`

