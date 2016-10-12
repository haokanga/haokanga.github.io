---
layout: post
title:  "Linux-Handy Bash Commands"
date:   2016-10-03 00:21:56 +1030
categories: Linux
tags: [Linux]
---
Handy bash commands when I hang around EC2 instances.
<!--summary break-->


#### Create tar archives, an entry can be a file or a folder
{%highlight bash%}
tar -cvzf <tar_name>.tgz entry1 entry2 entry3 ...
{%endhighlight%}

#### #xtract tar archives
{%highlight bash%}
tar -xvf <tar_name>.tgz -C /path/to/destination/
{%endhighlight%}

#### Print the output to StdOut and redirect it to a file at the same time
{%highlight bash%}
... | tee <output_file>
{%endhighlight%}

#### Find difference between files
{%highlight bash%}
diff file1 file2
{%endhighlight%}

#### Share EC2 instances
edit authorized_keys and append the public key, then you can use corresponding private key to SSH in the instance
{%highlight bash%}
/home/<user>/.ssh/authorized_keys
{%endhighlight%}

#### Search for a matching command previously typed in BASH
CTRL+R

#### SIGINT: cancel the current running command
CTRL+C

#### SIGQUIT: If SIGINT doesn't work
CTRL+\

#### Find process ID (PID)
{%highlight bash%}
ps
{%endhighlight%}

#### Kill process
{%highlight bash%}
kill -KILL <PID>
kill -9 <PID>
{%endhighlight%}