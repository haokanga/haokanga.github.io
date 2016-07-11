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

We have a **XAMPP based website** where the game gets embedded in. We want to make use of MySQL database of the XAMPP architecture. Suppose we have already developed **a Unity game** and everything works quite well, but you cannot store game record permanently, all the data gets lost when you exit the game. In this post, we will integrate MySQL with Unity to upload and retrieve highscore.

####  Basic Logic

Write PHP code to handle web request, one handler function to upload and another for retrieval.
    
Use C# to let Unity send web request to php handler (and process the response if needed). 

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
Upload highscore:
{% highlight C# %}
/*  upload highscore to database, no need to process response */
StartCoroutine(databaseHandler.updateScore(scoreArray));
{% endhighlight %}

Retrieve highscore:
{% highlight C# %}
/* retrieve highscore from database and process response */
StartCoroutine(databaseHandler.getSavedScore((storedHSArray) =>
{
    //lambda function to process retrieved result and edit in-game values
    processStoredHSArray(storedHSArray);
}));
{% endhighlight %}