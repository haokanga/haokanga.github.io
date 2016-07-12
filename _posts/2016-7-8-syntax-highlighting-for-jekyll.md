--- 
layout: post
title: "Jekyll-Syntax Highlighting for Jekyll" 
date: 2016-7-8 20:50:56 +1030 
categories: Jekyll 
tags: [Jekyll] 
---	

> Beautify code in your Jekyll blog with Syntax Highlighter and GitHub Gists.
<!--summary break-->

### Install & Configuration
Starting May 1st, 2016, GitHub Pages will support only [**Rouge**](https://github.com/jneen/rouge#rouge) in Ruby as syntax highlighter. You may also use [pygments](http://jekyll-windows.juthilo.com/3-syntax-highlighting/) highlighter, the Jekyll default highlighter in Python, since Rouge is compatible with it, but you will receive a [warning](https://help.github.com/articles/page-build-failed-config-file-error/#fixing-highlighting-errors) email each time you push blog repo to github pages. If you don't mind the warning, that's fine.

{% highlight bash %}
gem install rouge
{% endhighlight %}

Add these snippet in `_config.yml` at the root directory of your Jekyll blog.
{% highlight ruby %}
highlighter: rouge
{% endhighlight %}

### Add Custom Highlight CSS Rules
<ol>
<li>Add syntax highlighter CSS file as
<code>css/syntax.css</code> to your existing or newly generated Jekyll site. One highlighter theme can be <a href="https://gist.github.com/demisx/025698a7b5e314a7a4b5">downloaded here</a>.</li>
<li><p>Load CSS inside of post layout file (e.g. _layouts/default.html) 
</p><figure class="highlight"><pre><code class="language-html" data-lang="html"><span class="nt">&lt;head&gt;</span>
...
<span class="nt">&lt;link</span> <span class="na">href=</span><span class="s">"/css/syntax.css"</span> <span class="na">rel=</span><span class="s">"stylesheet"</span><span class="nt">&gt;</span>
...
<span class="nt">&lt;/head&gt;</span></code></pre></figure><p></p></li>
<li>
Input liquid syntax highlighting snippet like this:
<figure class="highlight">
<pre>{% raw %}
{% highlight js %} // you need to set proper language args here
alert('Syntax Highlighting for Jekyll'); // your code here
{% endhighlight %}{% endraw %}
</pre></figure>
</li>
<li>and here is the result:
{% highlight js %}
alert('Syntax Highlighting for Jekyll');
{% endhighlight %}</li>
</ol>

### Alternative: Embedded GitHub Gists

Another alternative to using Jekyll's built-in highlighter is to use GitHub Gists. They are directly
embeddable into your posts with <code>{% raw %}{% gist [gist_number] %}{% endraw %}</code> tag.

#### Installation

Add this line to your blog's `Gemfile`:
{% highlight ruby %}
gem 'jekyll-gist'
{% endhighlight %}

And then execute it in cmd, under your blog root directory:
{% highlight bash %}
bundle
{% endhighlight %}

Or just use `gem`:
{% highlight bash %}
gem install jekyll-gist
{% endhighlight %}

#### Configuration
Add the following to your site's _config.yml.
{% highlight yaml %}
gems:
  ...
  - [your other plugins]
  - jekyll-gist
{% endhighlight %}
The alternative yml array grammar is as follows:
{% highlight yaml %}
gems: [...[your other plugins], jekyll-gist]
{% endhighlight %}



#### Usage
`{% raw %}
{% gist haokanga/0256910ff04183118707863645cf7fd3 %}
{% endraw %}`

#### Demo
{% gist haokanga/0256910ff04183118707863645cf7fd3 %}

**Handle Liquid Exception: SSL_connect**

{% highlight bash %}
Liquid Exception: SSL_connect returned=1 errno=0 state=SSLv3 read server certificate B: certificate verify failed
{% endhighlight %}

Solution in Windows OS:

* Download [SSL Certificate](http://curl.haxx.se/ca/cacert.pem)
* Set environment variables in the current cmd session.
```
set SSL_CERT_FILE= [download directory]\cacert.pem 
```

* To make this a permanent setting, edit your control panel. 

{% highlight ruby %}
# Start Menu - Right click Computer - Properties - Advanced system settings - Environment Variables - New...
Variable name: SSL_CERT_FILE
Variable value: [download directory]\cacert.pem
{% endhighlight %}
Remember to **restart cmd** to make the change into effect, otherwise it won't work in your current cmd session if you have already opened one.