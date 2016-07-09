--- 
layout: post_page
title: "Jekyll-Syntax Highlighting for Jekyll" 
date: 2016-7-7 18:50:56 +1030 
categories: Jekyll 
tags: [Jekyll] 
---	

> Beautify code in your blog with Syntax Highlighting.
<!--summary break-->

#### Install & Configuration
Starting May 1st, 2016, GitHub Pages will only support kramdown, Jekyll's default choice, as markdown engine and Rouge as syntax highlighter. 
{% highlight bash %}
gem install rouge
{% endhighlight %}

Add these snippet in `_config.yml` at the root directory of your Jekyll blog.
{% highlight ruby %}
markdown: kramdown
highlighter: rouge
{% endhighlight %}

#### Add Custom Highlight CSS Rules
<ol>
<li>Add <a href="https://gist.github.com/demisx/025698a7b5e314a7a4b5">syntax highlighter CSS file</a> as
<code>css/syntax.css</code> to your existing or newly generated Jekyll site</li>
<li><p>Load CSS inside of a corresponding layout file (e.g. _layouts/default.html) 
</p><figure class="highlight"><pre><code class="language-html" data-lang="html"><span class="nt">&lt;head&gt;</span>
...
<span class="nt">&lt;link</span> <span class="na">href=</span><span class="s">"/css/syntax.css"</span> <span class="na">rel=</span><span class="s">"stylesheet"</span><span class="nt">&gt;</span>
...
<span class="nt">&lt;/head&gt;</span></code></pre></figure><p></p></li>
<li>
Input liquid syntax highlighting snippet like this:
<figure class="highlight">
<pre>{% raw %}
{% highlight js %}
alert('Syntax Highlighting for Jekyll');
{% endhighlight %}{% endraw %}
</pre></figure>
</li>
<li>and here is the result:
{% highlight js %}
alert('Syntax Highlighting for Jekyll');
{% endhighlight %}</li>
</ol>

#### Alternative: Embedded GitHub Gists

Another alternative to using Jekyll's built-in highlighter is to use GitHub Gists. They are directly
embeddable into your posts with <code>{% raw %}{% gist [gist_number] %}{% endraw %}</code> tag.
