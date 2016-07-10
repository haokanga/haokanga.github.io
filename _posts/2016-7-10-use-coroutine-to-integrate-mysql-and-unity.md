---
layout: post
title: "C#-Use Coroutine to integrate MySQL and Unity"
date: 2016-7-10 19:10:56 +1030 
categories: CSharp
tags: [CSharp]
---

Unity + C# + PHP + MySQL to build web embedded Unity games with database support
<!--summary break-->
    
#### Introduction

The idea covered in this post is not confined in Unity games. It is suitable for any web communication with SQL database with the help of PHP (or any other server language).

####  Task

Suppose we have already developed **a Unity game** and everything works quite well, except the fact that you cannot store game data, each time you close the game, all the data gets lost. We also have a **XAMPP based website** where the game get embedded in. We want to make use of MySQL database of the XAMPP architecture. In this post, we will try to upload and retrieve highscore.

####  Basic Logic

Write PHP code to handle web request, one handler function to upload and another for retrieval.
    
Use C# to let Unity send web request to php handler. 

* **Upload highscore**: After one game played by the player, it will gather current in-game highscore and set them to server.
* **Retrieve highscore**: When the player opens the game next time, the game will try to get highscore stored in database and override the default score record.

#### PHP handler

**game-handler.php** for core func

{% highlight php %}
{% include php/game-handler.php %}
{% endhighlight %}

**mysql-lib.php** for helper func, it is always a good practice to modularize your code to improve reuse

{% highlight php %}
{% include php/mysql-lib.php %}
{% endhighlight %}

#### C# handler

{% highlight C# %}
{% include csharp/DatabaseHandler.cs %}
{% endhighlight %}

#### C# handler usage example
