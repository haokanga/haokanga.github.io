---
layout: post
title:  "Encoding Notes"
date:   2016-11-19 00:21:56 +1030
categories: Java
tags: [Java]
---
All the Encoding pitfalls I found during the work in Cloud Computing. Hope this can help you as well.
<!--summary break-->

### Bash IO
Please care about Locale, use LC_ALL=en_US.UTF-8 when you use bash scripting for File I/O.
{%highlight bash%}
export LC_ALL=en_US.UTF-8
{%endhighlight%}

### Java

#### In you java source code

Check [Java-Set Charset in File I/O]( {{ baseurl | append: '/java/2016/10/10/set-charset-in-file-io.html' }} )

#### Java CMD
{%highlight bash%}
java -jar -Dfile.encoding=UTF-8 jar_name.jar
{%endhighlight%}

#### Maven
{%highlight xml%}
<properties>
    <project.build.sourceEncoding>UTF-8</project.build.sourceEncoding>
    <project.reporting.outputEncoding>UTF-8</project.reporting.outputEncoding>
</properties>
{%endhighlight%}