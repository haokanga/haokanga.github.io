--- 
layout: post
title: "Git-Global .gitignore Configuration"
date: 2017-5-11 14:50:56 -0500
categories: Git
tags: [Git]
---	

> Configure it once, use it everywhere.
<!--summary break-->

### Configuration

 A global .gitignore file, which is a list of rules for ignoring files in every Git repository is configured with Git `core.excludesfile` option.

You should check if `core.excludesfile` is already set.

{% highlight bash %}
git config --get core.excludesfile
{% endhighlight %}

If it is already set, we can directly edit the config file; otherwise we need to create a new ignore file such as `~/.gitignore` and set it to `core.excludesfile`.

{% highlight bash %}
git config --global core.excludesfile '~/.gitignore'
{% endhighlight %}


### Useful gitignore rules

#### Rules specific for languages

GitHub maintains [templates](https://github.com/github/gitignore) for many languages and frameworks, such as templates for [Java](https://github.com/github/gitignore/blob/master/Java.gitignore) and [Maven](https://github.com/github/gitignore/blob/master/Maven.gitignore).



#### IDE files

I am a big fan of JetBrains IDEs such as Intellij, to filter all the JetBrains project files that are user specific, check [this template](https://github.com/github/gitignore/blob/master/Global/JetBrains.gitignore) by JetBrains.

